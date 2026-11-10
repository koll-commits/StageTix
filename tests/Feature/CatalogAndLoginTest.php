<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Tenant;
use App\Models\TicketCategory;
use App\Models\User;
use App\Models\UserTenantRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAndLoginTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role, string $status = 'active'): User
    {
        $user = User::factory()->create(['email_verified_at' => now(), 'password' => 'secret-pass']);
        $user->forceFill(['role' => $role, 'status' => $status])->save();

        return $user;
    }

    public function test_catalog_is_public_and_hides_drafts(): void
    {
        $this->withoutVite();
        $shown = Event::factory()->create(['title' => 'Konser Terlihat']);
        TicketCategory::factory()->create(['event_id' => $shown->id]);
        Event::factory()->draft()->create(['title' => 'Konser Draft']);

        $this->get(route('catalog.home'))
            ->assertOk()
            ->assertSee('Konser Terlihat')
            ->assertDontSee('Konser Draft');
    }

    public function test_draft_event_detail_is_not_found(): void
    {
        $draft = Event::factory()->draft()->create();

        $this->get(route('catalog.show', $draft->slug))->assertNotFound();
    }

    public function test_eo_login_redirects_to_own_dashboard(): void
    {
        $user = $this->user('eo');
        $tenant = Tenant::factory()->create(['slug' => 'nada-test']);
        UserTenantRole::create(['user_id' => $user->id, 'eo_id' => $tenant->id, 'role' => 'owner']);

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'secret-pass'])
            ->assertRedirect(route('eo.dashboard', ['eo' => 'nada-test']));
    }

    public function test_wrong_password_is_rejected(): void
    {
        $user = $this->user('eo');

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_suspended_account_cannot_log_in(): void
    {
        $user = $this->user('eo', 'suspended');

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'secret-pass'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
