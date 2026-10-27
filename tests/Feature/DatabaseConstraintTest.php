<?php

namespace Tests\Feature;

use App\Models\CommissionScheme;
use App\Models\Event;
use App\Models\Gate;
use App\Models\Tenant;
use App\Models\TicketArea;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class DatabaseConstraintTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_category_cannot_reference_another_tenants_area(): void
    {
        $eventA = Event::factory()->create();
        $eventB = Event::factory()->create();
        $areaB = TicketArea::factory()->create(['event_id' => $eventB->id]);

        $this->expectException(QueryException::class);
        TicketCategory::factory()->create([
            'event_id' => $eventA->id,
            'tenant_id' => $eventA->tenant_id,
            'area_id' => $areaB->id, // area milik EO lain -> composite FK gagal (1452)
        ]);
    }

    public function test_ticket_category_cannot_use_area_of_another_event_in_same_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $event1 = Event::factory()->create(['tenant_id' => $tenant->id]);
        $event2 = Event::factory()->create(['tenant_id' => $tenant->id]);
        $area2 = TicketArea::factory()->create(['event_id' => $event2->id]);

        $this->expectException(QueryException::class);
        TicketCategory::factory()->create([
            'event_id' => $event1->id,
            'tenant_id' => $tenant->id,
            'area_id' => $area2->id, // tenant sama, event beda -> ditolak
        ]);
    }

    public function test_area_cannot_be_created_with_mismatched_tenant_and_event(): void
    {
        $tenantA = Tenant::factory()->create();
        $tenantB = Tenant::factory()->create();
        $eventB = Event::factory()->create(['tenant_id' => $tenantB->id]);

        $this->expectException(QueryException::class);
        // Area mengaku milik tenant A tetapi menunjuk event milik tenant B.
        TicketArea::factory()->create(['tenant_id' => $tenantA->id, 'event_id' => $eventB->id]);
    }

    public function test_duplicate_tenant_code_is_rejected(): void
    {
        Tenant::factory()->create(['code' => 'DUP']);

        $this->expectException(QueryException::class);
        Tenant::factory()->create(['code' => 'DUP']);
    }

    public function test_sold_qty_cannot_exceed_quota(): void
    {
        $this->expectException(QueryException::class);
        TicketCategory::factory()->create(['quota' => 10, 'sold_qty' => 11]);
    }

    public function test_orders_has_no_eo_id_and_supports_multiple_event_orders(): void
    {
        $this->assertFalse(Schema::hasColumn('orders', 'tenant_id'), 'orders TIDAK boleh punya tenant_id');

        $buyer = User::factory()->create();
        $a = Tenant::factory()->create();
        $b = Tenant::factory()->create();
        $ca = CommissionScheme::factory()->create(['tenant_id' => $a->id]);
        $cb = CommissionScheme::factory()->create(['tenant_id' => $b->id]);

        $orderId = $this->insertOrder($buyer, 'ORD-1', 'ck-1');

        foreach ([[$a, $ca], [$b, $cb]] as [$tenant, $commission]) {
            DB::table('event_orders')->insert([
                'order_id' => $orderId, 'tenant_id' => $tenant->id, 'commission_id' => $commission->id,
                'status' => 'pending', 'commission_rate_snapshot' => 0.1000,
                'subtotal_amount' => 250000, 'commission_amount' => 25000, 'net_amount' => 225000,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // Satu order induk memuat dua event_orders (lintas EO) tanpa melanggar scope.
        $this->assertSame(2, DB::table('event_orders')->where('order_id', $orderId)->count());

        // Tetapi hanya SATU event_order per EO per order (NFR-DAT-07).
        $this->expectException(QueryException::class);
        DB::table('event_orders')->insert([
            'order_id' => $orderId, 'tenant_id' => $a->id, 'commission_id' => $ca->id,
            'status' => 'pending', 'commission_rate_snapshot' => 0.1000,
            'subtotal_amount' => 1, 'commission_amount' => 0, 'net_amount' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_event_order_cannot_use_another_tenants_commission(): void
    {
        $buyer = User::factory()->create();
        $a = Tenant::factory()->create();
        $b = Tenant::factory()->create();
        $commissionB = CommissionScheme::factory()->create(['tenant_id' => $b->id]);
        $orderId = $this->insertOrder($buyer, 'ORD-2', 'ck-2');

        $this->expectException(QueryException::class);
        DB::table('event_orders')->insert([
            'order_id' => $orderId, 'tenant_id' => $a->id, 'commission_id' => $commissionB->id,
            'status' => 'pending', 'commission_rate_snapshot' => 0.1000,
            'subtotal_amount' => 1, 'commission_amount' => 0, 'net_amount' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_ticket_qr_code_is_globally_unique(): void
    {
        [$tenantId, $eventId, $itemId, $holderId] = $this->seedOrderItem();

        $row = fn () => [
            'tenant_id' => $tenantId, 'event_id' => $eventId, 'order_item_id' => $itemId,
            'holder_user_id' => $holderId, 'holder_name' => 'Pembeli', 'qr_code' => 'QR-SAMA',
            'status' => 'issued', 'issued_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ];
        DB::table('tickets')->insert($row());

        $this->expectException(QueryException::class);
        DB::table('tickets')->insert($row());
    }

    public function test_ticket_can_only_check_in_once_even_on_different_gate(): void
    {
        [$tenantId, $eventId, $itemId, $holderId] = $this->seedOrderItem();
        $venue = Event::findOrFail($eventId)->venue;
        $gate1 = Gate::factory()->create(['venue_id' => $venue->id, 'code' => 'G1']);
        $gate2 = Gate::factory()->create(['venue_id' => $venue->id, 'code' => 'G2']);
        $scanner = User::factory()->create();

        $ticketId = DB::table('tickets')->insertGetId([
            'tenant_id' => $tenantId, 'event_id' => $eventId, 'order_item_id' => $itemId,
            'holder_user_id' => $holderId, 'holder_name' => 'Pembeli', 'qr_code' => Str::random(48),
            'status' => 'issued', 'issued_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ]);

        $checkin = fn (int $gateId) => [
            'tenant_id' => $tenantId, 'ticket_id' => $ticketId, 'gate_id' => $gateId,
            'scanned_by' => $scanner->id, 'scanned_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ];
        DB::table('ticket_checkins')->insert($checkin($gate1->id));

        $this->expectException(QueryException::class);
        DB::table('ticket_checkins')->insert($checkin($gate2->id)); // percobaan kedua di gate berbeda ditolak
    }

    public function test_payment_event_provider_event_id_is_unique_for_idempotency(): void
    {
        $buyer = User::factory()->create();
        $orderId = $this->insertOrder($buyer, 'ORD-3', 'ck-3');
        $paymentId = DB::table('payments')->insertGetId([
            'order_id' => $orderId, 'payment_reference' => 'PAY-1', 'idempotency_key' => 'pay-key-1',
            'amount' => 250000, 'status' => 'pending', 'created_at' => now(), 'updated_at' => now(),
        ]);

        $event = fn () => [
            'payment_id' => $paymentId, 'provider_event_id' => 'evt-123', 'payload' => json_encode(['ok' => true]),
            'result' => 'verified', 'received_at' => now(), 'created_at' => now(), 'updated_at' => now(),
        ];
        DB::table('payment_events')->insert($event());

        $this->expectException(QueryException::class);
        DB::table('payment_events')->insert($event()); // webhook yang sama dikirim ulang -> ditolak
    }

    /** Membutuhkan migrasi 000107 (trigger). Hapus test ini bila migrasi itu dilewati. */
    public function test_ledger_entries_are_append_only(): void
    {
        $tenant = Tenant::factory()->create();
        $id = DB::table('ledger_entries')->insertGetId([
            'account' => 'tenant', 'tenant_id' => $tenant->id, 'idempotency_key' => 'led-1',
            'type' => 'sale_credit', 'available_delta' => 225000, 'held_delta' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        try {
            DB::table('ledger_entries')->where('id', $id)->update(['available_delta' => 1]);
            $this->fail('UPDATE pada ledger_entries seharusnya ditolak trigger.');
        } catch (QueryException $e) {
            $this->assertStringContainsString('append-only', $e->getMessage());
        }

        $this->expectException(QueryException::class);
        DB::table('ledger_entries')->where('id', $id)->delete();
    }

    public function test_ledger_tenant_account_requires_eo_id(): void
    {
        $this->expectException(QueryException::class);
        DB::table('ledger_entries')->insert([
            'account' => 'tenant', 'tenant_id' => null, 'idempotency_key' => 'led-2',
            'type' => 'sale_credit', 'available_delta' => 1, 'held_delta' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_gate_staff_can_have_only_one_active_event_assignment(): void
    {
        $tenant = Tenant::factory()->create();
        $event1 = Event::factory()->create(['tenant_id' => $tenant->id]);
        $event2 = Event::factory()->create(['tenant_id' => $tenant->id]);
        $staff = User::factory()->create();

        $assign = fn (Event $event, ?int $lock) => [
            'tenant_id' => $tenant->id, 'user_id' => $staff->id, 'event_id' => $event->id,
            'active_user_lock' => $lock, 'created_at' => now(), 'updated_at' => now(),
        ];

        DB::table('event_staff_assignments')->insert($assign($event1, $staff->id));
        // Penugasan yang sudah selesai (lock NULL) boleh banyak.
        DB::table('event_staff_assignments')->insert($assign($event2, null));
        $this->assertSame(2, DB::table('event_staff_assignments')->where('user_id', $staff->id)->count());

        $event3 = Event::factory()->create(['tenant_id' => $tenant->id]);
        $this->expectException(QueryException::class);
        DB::table('event_staff_assignments')->insert($assign($event3, $staff->id)); // event aktif kedua ditolak
    }

    public function test_money_columns_cast_to_integer(): void
    {
        $category = TicketCategory::factory()->create(['price_amount' => 350000]);

        $this->assertIsInt($category->refresh()->price_amount);
        $this->assertSame(350000, $category->price_amount);
    }

    private function insertOrder(User $buyer, string $number, string $checkoutKey): int
    {
        return DB::table('orders')->insertGetId([
            'public_id' => (string) Str::uuid(),
            'order_number' => $number,
            'user_id' => $buyer->id,
            'checkout_key' => $checkoutKey,
            'tracking_token_hash' => random_bytes(32),
            'status' => 'pending_payment',
            'grand_total_amount' => 500000,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    /** @return array{0:int,1:int,2:int,3:int} [tenant_id, event_id, order_item_id, holder_user_id] */
    private function seedOrderItem(): array
    {
        $buyer = User::factory()->create();
        $category = TicketCategory::factory()->create();
        $commission = CommissionScheme::factory()->create(['tenant_id' => $category->tenant_id]);
        $orderId = $this->insertOrder($buyer, 'ORD-'.Str::random(6), 'ck-'.Str::random(8));

        $eventOrderId = DB::table('event_orders')->insertGetId([
            'order_id' => $orderId, 'tenant_id' => $category->tenant_id, 'commission_id' => $commission->id,
            'status' => 'paid', 'commission_rate_snapshot' => 0.1000,
            'subtotal_amount' => $category->price_amount, 'commission_amount' => 0, 'net_amount' => $category->price_amount,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $itemId = DB::table('order_items')->insertGetId([
            'tenant_id' => $category->tenant_id, 'event_order_id' => $eventOrderId, 'event_id' => $category->event_id,
            'ticket_category_id' => $category->id, 'event_title_snapshot' => 'Event', 'category_name_snapshot' => 'Kategori',
            'area_name_snapshot' => 'Area', 'unit_price_snapshot' => $category->price_amount, 'quantity' => 1,
            'line_total' => $category->price_amount, 'created_at' => now(), 'updated_at' => now(),
        ]);

        return [$category->tenant_id, $category->event_id, $itemId, $buyer->id];
    }
}
