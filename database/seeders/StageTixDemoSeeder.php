<?php

namespace Database\Seeders;

use App\Models\CommissionScheme;
use App\Models\Event;
use App\Models\EventStaffAssignment;
use App\Models\Gate;
use App\Models\PromoCode;
use App\Models\Tenant;
use App\Models\TenantBalance;
use App\Models\TicketArea;
use App\Models\TicketCategory;
use App\Models\User;
use App\Models\UserPlatformRole;
use App\Models\UserTenantRole;
use App\Models\Venue;
use App\Models\VenueAreaTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class StageTixDemoSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        $venue = $this->seedVenue();

        $blueprint = [
            'NADA' => [
                'display' => 'Nada Semesta Production',
                'owner' => 'eo1@stagetix.test',
                'event' => ['Senandung Senja Festival', 'senandung-senja-festival', 'Pop', Carbon::create(2026, 12, 12, 19, 0, 0, 'UTC')],
                'categories' => [
                    ['Festival', 'Festival A', 350000, 800],
                    ['Tribun', 'Tribun Barat', 250000, 600],
                    ['VIP', 'VIP Lounge', 900000, 100],
                ],
            ],
            'RIAK' => [
                'display' => 'Riak Rock Entertainment',
                'owner' => 'eo2@stagetix.test',
                'event' => ['Riak Rock Night Vol. 3', 'riak-rock-night-vol-3', 'Rock', Carbon::create(2027, 1, 23, 18, 30, 0, 'UTC')],
                'categories' => [
                    ['Festival', 'Festival A', 300000, 900],
                    ['Tribun', 'Tribun Barat', 200000, 500],
                    ['VIP', 'VIP Lounge', 750000, 120],
                ],
            ],
        ];

        $created = [];
        foreach ($blueprint as $code => $spec) {
            /** @var Tenant $tenant */
            $tenant = $this->upsert(Tenant::class, ['code' => $code], [
                'slug' => strtolower($code).'-production',
                'display_name' => $spec['display'],
                'status' => 'active',
                'deleted_at' => null,
            ], withTrashed: true);

            $this->upsert(TenantBalance::class, ['tenant_id' => $tenant->id], ['available_amount' => 0, 'held_amount' => 0]);

            $this->upsert(
                CommissionScheme::class,
                ['tenant_id' => $tenant->id, 'valid_from' => Carbon::create(2026, 1, 1, 0, 0, 0, 'UTC')],
                ['commission_rate' => 0.1000, 'valid_to' => null],
            );

            [$title, $slug, $genre, $startsAt] = $spec['event'];
            /** @var Event $event */
            $event = $this->upsert(Event::class, ['slug' => $slug], [
                'tenant_id' => $tenant->id,
                'venue_id' => $venue->id,
                'title' => $title,
                'genre' => $genre,
                'description' => 'Event demo '.$title.' pada platform StageTix.',
                'starts_at' => $startsAt,
                'ends_at' => $startsAt->copy()->addHours(5),
                'sales_start_at' => Carbon::create(2026, 10, 1, 0, 0, 0, 'UTC'),
                'sales_end_at' => $startsAt->copy()->subHour(),
                'max_tickets_per_account' => 4,
                'status' => 'published',
                'deleted_at' => null,
            ], withTrashed: true);

            foreach ($spec['categories'] as $i => [$catName, $areaName, $price, $quota]) {
                $template = VenueAreaTemplate::where('venue_id', $venue->id)->where('name', $areaName)->first();

                $area = $this->upsert(
                    TicketArea::class,
                    ['tenant_id' => $tenant->id, 'event_id' => $event->id, 'name' => $areaName],
                    ['venue_area_template_id' => $template?->id, 'capacity' => $quota],
                );

                $this->upsert(
                    TicketCategory::class,
                    ['tenant_id' => $tenant->id, 'event_id' => $event->id, 'name' => $catName],
                    [
                        'area_id' => $area->id,
                        'price_amount' => $price,
                        'quota' => $quota,
                        'sold_qty' => 0,
                        'is_sold_out' => false,
                        'is_active' => true,
                        'sort_order' => $i + 1,
                    ],
                );
            }

            UserTenantRole::firstOrCreate([
                'user_id' => $users[$spec['owner']]->id,
                'tenant_id' => $tenant->id,
                'role' => 'owner',
            ]);

            $created[$code] = [$tenant, $event];
        }

        // staf gate
        [$nada, $nadaEvent] = $created['NADA'];
        $gate = Gate::where('venue_id', $venue->id)->where('code', 'G1')->first();
        UserTenantRole::firstOrCreate([
            'user_id' => $users['gate1@stagetix.test']->id,
            'tenant_id' => $nada->id,
            'role' => 'gate_staff',
        ]);
        $this->upsert(
            EventStaffAssignment::class,
            ['user_id' => $users['gate1@stagetix.test']->id, 'event_id' => $nadaEvent->id],
            ['tenant_id' => $nada->id, 'gate_id' => $gate?->id, 'active_user_lock' => $users['gate1@stagetix.test']->id],
        );

        // promo
        $this->upsert(PromoCode::class, ['code' => 'STAGETIX10'], [
            'tenant_id' => null,
            'discount_type' => 'percent',
            'discount_value' => 10,
            'max_discount_amount' => 50000,
            'usage_limit' => 100,
            'per_user_limit' => 1,
            'valid_from' => Carbon::create(2026, 10, 1, 0, 0, 0, 'UTC'),
            'valid_to' => Carbon::create(2027, 3, 31, 23, 59, 59, 'UTC'),
            'is_active' => true,
        ]);
    }

    private function seedUsers(): array
    {
        
        $spec = [
            'admin@stagetix.test' => ['Admin StageTix', '+6281200000001', 'admin'],
            'pembeli@stagetix.test' => ['Pembeli Demo', '+6281200000002', 'customer'],
            'eo1@stagetix.test' => ['Owner Nada Semesta', '+6281200000003', 'eo'],
            'eo2@stagetix.test' => ['Owner Riak Rock', '+6281200000004', 'eo'],
            'gate1@stagetix.test' => ['Petugas Gate 1', '+6281200000005', 'customer'],
        ];

        // kolom role/status diisi hanya jika ada di tabel users
        $hasRoleColumns = Schema::hasColumn('users', 'role') && Schema::hasColumn('users', 'status');

        $users = [];
        foreach ($spec as $email => [$name, $phone, $role]) {
            $values = [
                'name' => $name,
                'phone_e164' => $phone,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ];
            if ($hasRoleColumns) {
                $values['role'] = $role;
                $values['status'] = 'active';
            }

            /** @var User $user */
            $user = $this->upsert(User::class, ['email' => $email], $values);
            $users[$email] = $user;
        }

        UserPlatformRole::firstOrCreate(['user_id' => $users['admin@stagetix.test']->id, 'role' => 'admin']);

        return $users;
    }

    private function seedVenue(): Venue
    {
        /** @var Venue $venue */
        $venue = $this->upsert(Venue::class, ['name' => 'Gedung Serbaguna Nusantara', 'city' => 'Surabaya'], [
            'address' => 'Jl. Contoh No. 1, Surabaya',
            'capacity' => 2000,
            'layout_type' => 'hall',
            'status' => 'active',
        ]);

        foreach ([['G1', 'Gate 1 (Utara)'], ['G2', 'Gate 2 (Selatan)']] as [$code, $name]) {
            $this->upsert(Gate::class, ['venue_id' => $venue->id, 'code' => $code], ['name' => $name, 'status' => 'active']);
        }

        foreach ([['Festival A', 900, 1], ['Tribun Barat', 600, 2], ['VIP Lounge', 120, 3]] as [$name, $capacity, $order]) {
            $this->upsert(VenueAreaTemplate::class, ['venue_id' => $venue->id, 'name' => $name], ['capacity' => $capacity, 'sort_order' => $order]);
        }

        return $venue;
    }

    // cari berdasarkan $keys, lalu forceFill; aman dijalankan berulang
    private function upsert(string $class, array $keys, array $values = [], bool $withTrashed = false): Model
    {
        $query = $class::query();
        if ($withTrashed) {
            $query->withTrashed();
        }

        /** @var Model $model */
        $model = $query->where($keys)->first() ?? new $class;
        $model->forceFill($keys + $values)->save();

        return $model;
    }
}