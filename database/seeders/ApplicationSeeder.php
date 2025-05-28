<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Makejob;
use App\Models\User;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $makejobs = Makejob::all();
        $users = User::all();

        foreach ($makejobs as $makejob) {
            // Create 1-3 applications for each job
            $numApplications = rand(1, 3);
            $selectedUsers = $users->random($numApplications);

            foreach ($selectedUsers as $user) {
                Application::create([
                    'user_id' => $user->id,
                    'makejob_id' => $makejob->id,
                    'proposed_price' => $makejob->budget * (rand(80, 120) / 100), // Random price between 80% and 120% of budget
                    'cover_letter' => 'I am interested in this job and would like to apply. I have the necessary skills and experience to complete it successfully.',
                    'status' => 'pending',
                    // Add any new required fields here if needed
                ]);
            }
        }
    }
}
