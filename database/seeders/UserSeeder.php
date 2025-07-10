<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create two admin users
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@visionist.com',
            'password' => bcrypt('password'),
            'isAdmin' => '1',
        ]);

        // Create one freelancer user
        User::factory()->create([
            'name' => 'freelancer',
            'email' => 'freelancer@visionist.com',
            'password' => bcrypt('password'),
            'isAdmin' => '0',
        ]);

        User::factory()->create([
            'name' => 'freelancer2',
            'email' => 'freelancer2@visionist.com',
            'password' => bcrypt('password'),
            'isAdmin' => '0',
        ]);
        User::factory()->create([
            'name' => 'freelancer3',
            'email' => 'freelancer3@visionist.com',
            'password' => bcrypt('password'),
            'isAdmin' => '0',
        ]);

        // Create 99 additional users (freelancers)
        User::factory(0)->create([
            'isAdmin' => '0',
        ]);
    }
}
