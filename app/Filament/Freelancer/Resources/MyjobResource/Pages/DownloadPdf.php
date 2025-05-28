<?php

namespace App\Filament\Freelancer\Resources\MyjobResource\Pages;

use App\Filament\Freelancer\Resources\MyjobResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Storage;

class DownloadPdf extends Page
{
    protected static string $resource = MyjobResource::class;

    public function download()
    {
        $path = request()->query('path');
        $filename = request()->query('filename');

        if (!Storage::exists($path)) {
            abort(404, 'File not found.');
        }

        $response = Storage::download($path, $filename, [
            'Content-Type' => 'application/pdf',
        ]);

        // Delete the file after sending
        Storage::delete($path);

        return $response;
    }
}
