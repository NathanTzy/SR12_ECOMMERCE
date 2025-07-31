<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'distributor']);
        Role::firstOrCreate(['name' => 'agen']);
        Role::firstOrCreate(['name' => 'subAgen']);
        Role::firstOrCreate(['name' => 'marketer']);
        Role::firstOrCreate(['name' => 'reseller']);
    }
}
