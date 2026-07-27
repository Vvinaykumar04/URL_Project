<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::factory()->superAdmin()->make([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => 'password',
        ]);
        User::query()->updateOrCreate(
            ['email' => $superAdmin->email],
            $superAdmin->getAttributes()
        );
    }
}
