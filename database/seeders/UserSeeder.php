<?php

namespace Database\Seeders;

use App\Enums\Can;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->withPermission(Can::BE_AN_ADMIN)
            ->create([
                'name'  => 'Test User',
                'email' => 'test@user.com',
            ]);
    }
}
