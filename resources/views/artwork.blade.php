<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $artwork->title }} - Artwork Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; color: #1a1a1a; background: #f5f7fa; min-height: 100vh; }
        .max-w-2xl { max-width: 40rem; margin: 0 auto; }
        .p-6 { padding: 1.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .bg-white { background: #fff; }
        .dark-bg { background: #1f2a44; color: #e2e8f0; }
        .rounded-xl { border-radius: 1rem; }
        .shadow-lg { box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
        .text-primary-600 { color: #6b46c1; }
        .text-primary-800 { color: #553c9a; }
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .text-lg { font-size: 1.125rem; }
        .text-2xl { font-size: 1.5rem; }
        .rounded-lg { border-radius: 0.5rem; }
        .object-cover { object-fit: cover; }
        .block { display: block; }
        .hover\:text-primary-800:hover { color: #553c9a; }
        .text-pink-600 { color: #ec4899; }
    </style>
</head>
<body>
    <div class="max-w-2xl mx-auto p-6">
        <div class="mb-4">
            <a href="/" class="text-primary-600 hover:text-primary-800">&larr; Back to Home</a>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="mb-4 flex flex-col items-center">
                @if(is_array($artwork->image) && count($artwork->image))
                    <img src="{{ asset('storage/' . $artwork->image[0]) }}" alt="{{ $artwork->title }}" class="w-full max-w-md rounded-lg mb-4 object-cover" style="max-height: 320px;">
                @else
                    <img src="{{ asset('artworks/default-artwork.jpg') }}" alt="No Image" class="w-full max-w-md rounded-lg mb-4 object-cover" style="max-height: 320px;">
                @endif
            </div>
            <h1 class="text-2xl font-bold mb-2">{{ $artwork->title }}</h1>
            <div class="text-gray-500 mb-2">by {{ $artwork->user->name ?? 'Unknown' }}</div>
            <div class="mb-2 text-pink-600 font-semibold"><i class="fas fa-heart"></i> {{ $artwork->likes_count ?? $artwork->likes()->count() }}</div>
            <div class="mb-4 text-gray-700">{!! nl2br(e($artwork->description)) !!}</div>
        </div>
    </div>
</body>
</html>
