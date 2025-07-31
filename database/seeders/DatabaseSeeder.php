<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Mimin',
            'email' => 'ad@ex.com',
            'password' => Hash::make('123'),
        ]);

        $this->call([
            RoleSeeder::class,
        ]);

        // Assign role ke user
        $user->assignRole('distributor');
    }
}
