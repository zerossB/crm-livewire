<?php

namespace Database\Seeders;

use App\Enums\Can;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        collect(Can::cases())->each(function (Can $can) {
            Permission::query()->create([
                'name' => $can->value,
            ]);
        });
    }
}
