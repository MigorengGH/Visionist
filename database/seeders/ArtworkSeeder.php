<?php

namespace Database\Seeders;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArtworkSeeder extends Seeder
{
    public function run(): void
    {
        // Get all existing users
        $users = User::all();

        // If no users exist, we should run UserSeeder first
        if ($users->isEmpty()) {
            $this->call(UserSeeder::class);
            $users = User::all();
        }

        $artworks = [
            [
                'title' => 'Cinematic Sunset Scene',
                'description' => 'A dramatic film still capturing a sunset scene with perfect lighting and emotional depth, shot for a feature film.',
                'tags' => ['film', 'cinematography', 'sunset', 'drama'],
                'image' => ['artworks/cinematic-sunset.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Modern Brand Logo',
                'description' => 'A sleek and minimalist logo designed for a tech startup, emphasizing clean lines and bold typography.',
                'tags' => ['graphic-design', 'logo', 'branding', 'modern'],
                'image' => ['artworks/brand-logo.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Edited Urban Montage',
                'description' => 'A skillfully edited video sequence showcasing the energy of city life, with seamless transitions and color grading.',
                'tags' => ['editing', 'video', 'urban', 'montage'],
                'image' => ['artworks/urban-montage.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Black and White Portrait',
                'description' => 'A striking monochrome photograph capturing the raw emotion of a subject in natural light.',
                'tags' => ['photography', 'portrait', 'monochrome', 'emotion'],
                'image' => ['artworks/bw-portrait.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Film Poster Design',
                'description' => 'A bold and visually striking poster created for an upcoming indie film, blending typography and imagery.',
                'tags' => ['graphic-design', 'film', 'poster', 'typography'],
                'image' => ['artworks/film-poster.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Nature Documentary Still',
                'description' => 'A high-resolution still from a nature documentary, showcasing a rare moment in wildlife with vivid detail.',
                'tags' => ['film', 'documentary', 'wildlife', 'nature'],
                'image' => ['artworks/nature-doc.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Digital Magazine Layout',
                'description' => 'A vibrant and modern magazine spread designed with a focus on readability and visual hierarchy.',
                'tags' => ['graphic-design', 'editorial', 'layout', 'modern'],
                'image' => ['artworks/magazine-layout.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Short Film Storyboard',
                'description' => 'A detailed storyboard sequence for a short film, illustrating key scenes with precise composition.',
                'tags' => ['film', 'storyboard', 'illustration', 'planning'],
                'image' => ['artworks/storyboard.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'Macro Flower Photography',
                'description' => 'A close-up photograph of a flower, highlighting intricate details and vibrant colors through macro techniques.',
                'tags' => ['photography', 'macro', 'flowers', 'nature'],
                'image' => ['artworks/macro-flower.jpg'],
                'publish' => true,
            ],
            [
                'title' => 'VFX Breakdown',
                'description' => 'A composite image showing the before and after of a visual effects sequence for a sci-fi film.',
                'tags' => ['editing', 'vfx', 'film', 'sci-fi'],
                'image' => ['artworks/vfx-breakdown.jpg'],
                'publish' => true,
            ],
        ];

        foreach ($artworks as $artwork) {
            // Randomly select a user
            $user = $users->random();

            $createdArtwork = Artwork::create([
                'user_id' => $user->id,
                'title' => $artwork['title'],
                'description' => $artwork['description'],
                'tags' => $artwork['tags'],
                'image' => $artwork['image'],
                'publish' => $artwork['publish'],
            ]);

            // Add random likes to the artwork
            $randomUsers = $users->random(rand(0, $users->count()));
            foreach ($randomUsers as $randomUser) {
                if ($randomUser->id !== $createdArtwork->user_id) { // Don't like your own artwork
                    $createdArtwork->likes()->create([
                        'user_id' => $randomUser->id,
                    ]);
                }
            }
        }
    }
}
