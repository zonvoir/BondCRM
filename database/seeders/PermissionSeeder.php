<?php

namespace Database\Seeders;

use EragPermission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect(config('permissions'))
            ->flatMap(function ($actions) {
                return collect($actions)->flatten();
            })
            ->unique();

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate([
                'name' => $permission,
            ]);
        }
    }
}
