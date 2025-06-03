<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Workshop;
use App\Models\District;
use App\Models\State;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkshopSeeder extends Seeder
{
    public function run()
    {
        $workshops = [
            'Acting Workshop' => 'acting_workshop.jpg',
            'Animation & Motion' => 'animation_motion.jpg',
            'Creative Writing' => 'creative_writing.jpg',
            'Fashion Design' => 'fashion_design.jpg',
            'Film Directing' => 'film_directing.jpg',
            'Game Development' => 'game_development.jpg',
            'Graphic Design' => 'graphic_design.jpg',
            'Music Production' => 'music_production.jpg',
            'Photography Masterclass' => 'photography_masterclass.jpg',
            'Social Media Marketing' => 'social_media_marketing.jpg',
        ];

        // Get all districts with their states
        $districts = District::with('state')->get();

        foreach ($workshops as $title => $image) {
            // Randomly select a district
            $district = $districts->random();

            Workshop::create([
                'name' => $title,
                'description' => 'A comprehensive workshop on ' . strtolower($title) . '.',
                'start_date' => now()->addDays(rand(1, 30)),
                'state_id' => $district->state_id,
                'district_id' => $district->id,
                'price' => rand(0, 500) . (rand(0, 1) ? '.00' : '.'.rand(1, 99)),
                'image' => ["/WorkshopImage/" . $image], // Path inside /public/
                'tags' => ['workshop', strtolower(Str::slug($title))], // Sample tags
                'publish' => rand(0, 1), // Random publish status
            ]);
        }
    }
}
