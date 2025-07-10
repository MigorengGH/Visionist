<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workshop;
use App\Models\Artwork;

class WelcomeController extends Controller
{
    public function workshop($id)
    {
        $workshop = Workshop::with(['state', 'district'])->findOrFail($id);
        return view('workshop', compact('workshop'));
    }

    public function artwork($id)
    {
        $artwork = Artwork::with(['user', 'likes'])->findOrFail($id);
        return view('artwork', compact('artwork'));
    }
}
