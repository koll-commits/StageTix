<?php

namespace Tests\Feature;

use App\Jobs\CountTenantEvents;
use App\Models\Event;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenantRole;
use App\Models\Venue;
use App\Modules\Catalog\Services\PublicCatalogQuery;
use App\Policies\EventPolicy;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Matriks isolasi tenant (allow/deny) A vs B: global scope, scoped binding, policy,
 * public bypass terkontrol, dan context pada job. Dijalankan di database nyata.
 */
class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    /** @return array{0: Tenant, 1: Collection<int, Event>} */
    private function tenantWithEvents(int $count, ?Venue $venue = null, string $status = 'active', string $eventStatus = 'published'): array
    {
        $venue ??= Venue::factory()->create();
        $tenant = Tenant::factory()->create(['status' => $status]);
        $events = Event::factory()->count($count)->create([
            'eo_id' => $tenant->id,
            'venue_id' => $venue->id,
            'status' => $eventStatus,
        ]);

        return [$tenant, $events];
    }

    private function memberOf(Tenant $tenant): User
    {
        $user = User::factory()->create([
            'role' => 'eo',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        UserTenantRole::create([
            'user_id' => $user->id,
            'eo_id' => $tenant->id,
            'role' => 'owner',
        ]);

        return $user;
    }

    public function test_global_scope_returns_only_active_tenant_rows(): void
    {
        [$a] = $this->tenantWithEvents(2);
        $this->tenantWithEvents(3); // tenant B

        app(TenantContext::class)->set($a);

        $this->assertSame(2, Event::query()->count());
        $this->assertTrue(Event::query()->get()->every(fn (Event $e) => $e->eo_id === $a->id));
    }

    public function test_without_context_scope_is_not_applied(): void
    {
        $this->tenantWithEvents(2);
        $this->tenantWithEvents(3);

        // Tanpa context, scope tidak memfilter (alur internal SELALU mengisi context).
        $this->assertSame(5, Event::query()->count());
    }

    public function test_context_is_reset_between_requests(): void
    {
        [$a] = $this->tenantWithEvents(1);
        app(TenantContext::class)->set($a);

        $this->assertTrue(app(TenantContext::class)->has());

        // Simulasi lifecycle request/job berikutnya.
        $this->app->forgetScopedInstances();

        $this->assertFalse(app(TenantContext::class)->has(), 'TenantContext bocor antar request/job');
    }

    public function test_eo_id_is_autofilled_on_create_within_context(): void
    {
        [$a] = $this->tenantWithEvents(0);
        $venue = Venue::factory()->create();

        app(TenantContext::class)->set($a);

        $event = Event::create([
            'venue_id' => $venue->id,
            'title' => 'Auto Filled Event',
            'slug' => 'auto-filled-event',
            'genre' => 'music',
            'starts_at' => now()->addDays(5),
            'ends_at' => now()->addDays(5)->addHours(3),
            'sales_start_at' => now(),
            'sales_end_at' => now()->addDays(4),
            'status' => 'draft',
        ]);

        $this->assertSame($a->id, $event->eo_id);
    }

    public function test_event_index_returns_only_own_tenant(): void
    {
        [$a] = $this->tenantWithEvents(2);
        $this->tenantWithEvents(3); // B

        $this->actingAs($this->memberOf($a));

        $response = $this->getJson(route('eo.events.index', ['eo' => $a->slug]));
        $response->assertOk();
        $this->assertCount(2, $response->json('events'));
    }

    public function test_scoped_binding_blocks_cross_tenant_event(): void
    {
        [$a] = $this->tenantWithEvents(1);
        [, $eventsB] = $this->tenantWithEvents(1);
        $eventB = $eventsB->first();

        $this->actingAs($this->memberOf($a));

        // Event tenant B diakses lewat route tenant A -> tidak ditemukan (scoped binding + scope).
        $this->getJson(route('eo.events.show', ['eo' => $a->slug, 'event' => $eventB->id]))
            ->assertNotFound();
    }

    public function test_member_can_toggle_own_event(): void
    {
        [$a, $eventsA] = $this->tenantWithEvents(1);
        $eventA = $eventsA->first();

        $this->actingAs($this->memberOf($a));

        $this->patchJson(route('eo.events.update', ['eo' => $a->slug, 'event' => $eventA->id]), [
            'status' => 'ongoing',
        ])->assertOk()->assertJson(['ok' => true, 'status' => 'ongoing']);

        $this->assertSame('ongoing', $eventA->refresh()->status);
    }

    public function test_policy_denies_cross_tenant_update(): void
    {
        [$a] = $this->tenantWithEvents(0);
        [, $eventsB] = $this->tenantWithEvents(1);
        $eventB = $eventsB->first();

        app(TenantContext::class)->set($a); // aktif = A

        $this->assertFalse((new EventPolicy)->update(new User, $eventB), 'Policy harus menolak event tenant lain');
    }

    public function test_public_catalog_query_no_cross_venue_or_inactive_leak(): void
    {
        $venue1 = Venue::factory()->create();
        $this->tenantWithEvents(2, $venue1, status: 'active', eventStatus: 'published');
        $this->tenantWithEvents(1, $venue1, status: 'suspended', eventStatus: 'published'); // suspended -> tidak tampil
        $venue2 = Venue::factory()->create();
        $this->tenantWithEvents(4, $venue2, status: 'active', eventStatus: 'published');     // venue lain -> tidak tampil

        $events = app(PublicCatalogQuery::class)->forVenue($venue1);

        $this->assertCount(2, $events, 'Hanya event dari tenant aktif pada venue ini');
    }

    public function test_job_sets_and_clears_context_per_tenant(): void
    {
        [$a] = $this->tenantWithEvents(2);
        [$b] = $this->tenantWithEvents(3);

        $jobA = new CountTenantEvents($a->id);
        $jobA->handle(app(TenantContext::class));

        $this->assertSame(2, $jobA->result);
        $this->assertFalse(app(TenantContext::class)->has(), 'Context harus dibersihkan setelah job');

        $jobB = new CountTenantEvents($b->id);
        $jobB->handle(app(TenantContext::class));

        $this->assertSame(3, $jobB->result); // tidak terkontaminasi tenant A
    }
}

