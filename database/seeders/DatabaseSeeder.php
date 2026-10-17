<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Data demo deterministik. Password demo hanya untuk lokal (bukan secret produksi).
     */
    public function run(): void
    {
        $demo = [
            ['name' => 'Admin StageTix', 'email' => 'admin@stagetix.test', 'role' => 'admin'],
            ['name' => 'Staf EO Demo',   'email' => 'eo@stagetix.test',    'role' => 'eo'],
            ['name' => 'Pembeli Demo',   'email' => 'buyer@stagetix.test', 'role' => 'buyer'],
        ];

        foreach ($demo as $row) {
            // forceFill: role/status sengaja tidak mass-assignable.
            User::firstOrNew(['email' => $row['email']])
                ->forceFill([
                    'name' => $row['name'],
                    'role' => $row['role'],
                    'status' => 'active',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
                ->save();
        }
    }
}
