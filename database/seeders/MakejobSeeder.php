<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Makejob;
use App\Models\User;
use App\Models\State;
use App\Models\District;

class MakejobSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $states = State::all();
        $districts = District::all();

        foreach ($users as $user) {
            // Create 1-3 jobs for each user
            $numJobs = rand(1, 3);
            for ($i = 0; $i < $numJobs; $i++) {
                Makejob::create([
                    'user_id' => $user->id,
                    'title' => 'Job ' . ($i + 1) . ' for ' . $user->name,
                    'description' => 'This is a sample job description. It includes details about the project requirements and expectations.',
                    'budget' => rand(100, 1000),
                    'status' => 'open',
                    'state_id' => $states->random()->id,
                    'district_id' => $districts->random()->id,
                    'tags' => [fake()->word(), fake()->word(), fake()->word()],
                ]);
            }
        }
    }
}
