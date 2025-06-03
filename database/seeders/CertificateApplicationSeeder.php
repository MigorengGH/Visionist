<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CertificateApplication;
use App\Models\User;
use Faker\Factory as Faker;

class CertificateApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create(); // Initialize Faker
        if (User::count() === 0) {
            User::factory()->count(10)->create(); // Ensure users exist
        }

        for ($i = 0; $i < 20; $i++) {
            $status = ['pending', 'approved', 'rejected', 'reapply'][array_rand(['pending', 'approved', 'rejected', 'reapply'])];
            CertificateApplication::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'title' => 'Sample Certificate ' . ($i + 1),
                'type' => ['institute', 'other'][array_rand(['institute', 'other'])],
                'cv_path' => json_encode(["cv" => 'cvs/cv_" . uniqid() . ".pdf']),
                'status' => $status,
                'reapply_count' => rand(0, 1),
               'admin_comment' => in_array($status, ['pending','rejected']) ? $faker->sentence(20) : 'Your application is under review',
                'approved_by' => User::where('isAdmin', true)->inRandomOrder()->first()?->id,
            ]);
        }
    }
}
