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
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            [
                'name' => 'Admin StageTix',
                'email' => 'admin@stagetix.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Event Organizer',
                'email' => 'eo@stagetix.com',
                'role' => 'eo',
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@stagetix.com',
                'role' => 'null',
            ],

        ];

        foreach ($users as $user) {
            User::firstOrNew(['email' => $user['email']])
                ->forceFill([
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'status' => 'active',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ])
                ->save();

        }
    }
}
