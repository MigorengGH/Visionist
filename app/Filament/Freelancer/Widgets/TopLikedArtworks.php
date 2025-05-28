<?php

namespace App\Filament\Freelancer\Widgets;

use App\Models\Artwork;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class TopLikedArtworks extends Widget
{
    protected static string $view = 'filament.freelancer.widgets.top-liked-artworks';

    public function getTopArtworks()
    {
        return Artwork::withCount('likes')
            ->orderByDesc('likes_count')
            ->take(6)
            ->get();
    }
}
