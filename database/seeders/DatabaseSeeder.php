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
        // Default admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@menma.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Products, comments, orders
        $this->call(ProductSeeder::class);
    }
}
