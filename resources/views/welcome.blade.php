<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visionist - Your Creative Job Hub</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper Slider CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">

    <!-- Styles -->
    <style>
        :root {
            --color-text: #1a1a1a;
            --color-background: #f5f7fa;
            --color-primary: #6b46c1;
            --color-primary-hover: #553c9a;
            --color-accent: #f6ad55;
            --color-card: #ffffff;
            --color-section: rgba(255, 255, 255, 0.9);
            --color-secondary-text: #6b7280;
            --color-icon-bg: #edf2f7;
            --color-border: #e2e8f0;
        }

        .dark-mode {
            --color-text: #e2e8f0;
            --color-background: #1f2a44;
            --color-primary: #9f7aea;
            --color-primary-hover: #7c3aed;
            --color-accent: #f6ad55;
            --color-card: #2d3748;
            --color-section: rgba(45, 55, 72, 0.9);
            --color-secondary-text: #a0aec0;
            --color-icon-bg: #4a5568;
            --color-border: #4a5568;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--color-text);
            background: var(--color-background);
            min-height: 100vh;
            overflow-x: hidden;
            transition: all 0.3s ease;
        }

        .page-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 3rem;
        }

        /* Theme toggle */
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            background: var(--color-card);
            border-radius: 8px;
            padding: 8px 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: 1px solid var(--color-border);
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .theme-toggle i { font-size: 16px; color: var(--color-text); }
        .theme-toggle span { font-size: 14px; font-weight: 500; color: var(--color-text); }

        /* Hero Section with Slider */
        .hero-section {
            position: relative;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .swiper {
            width: 100%;
            height: 500px;
        }

        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            position: relative;
            color: white;
            text-align: center;
        }

        .swiper-slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .slide-content {
            position: relative;
            z-index: 2;
            max-width: 600px;
            padding: 2rem;
            opacity: 0;
            transform: translateY(20px);
        }

        .swiper-slide-active .slide-content {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .slide-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .slide-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .slide-button {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            background: var(--color-primary);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .slide-button:hover {
            background: var(--color-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Swiper Pagination and Navigation */
        .swiper-pagination-bullet {
            background: var(--color-accent);
            opacity: 0.7;
        }

        .swiper-pagination-bullet-active {
            background: var(--color-primary);
            opacity: 1;
        }

        .swiper-button-next, .swiper-button-prev {
            color: var(--color-accent);
        }

        /* Features Section */
        .features-section {
            width: 100%;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            color: var(--color-text);
            text-align: center;
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: var(--color-card);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: left;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid var(--color-border);
            opacity: 0;
            transform: translateY(20px);
        }

        .feature-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--color-icon-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .feature-icon:hover {
            transform: scale(1.1);
        }

        .feature-icon i {
            font-size: 1.5rem;
            color: var(--color-primary);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .feature-description {
            font-size: 0.95rem;
            color: var(--color-secondary-text);
            line-height: 1.5;
        }

        .feature-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: var(--color-primary);
            font-weight: 500;
        }

        /* Workshop Section */
        .workshop-section {
            width: 100%;
        }

        .workshop-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .workshop-card {
            background: var(--color-card);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: left;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid var(--color-border);
            opacity: 0;
            transform: translateY(20px);
        }

        .workshop-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .workshop-image {
            width: 100%;
            height: 180px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .workshop-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .workshop-card:hover .workshop-img {
            transform: scale(1.05);
        }

        .workshop-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .workshop-description {
            font-size: 0.95rem;
            color: var(--color-secondary-text);
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .workshop-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--color-primary);
            font-weight: 500;
        }

        /* Call to Action Section */
        .cta-section {
            background: var(--color-card);
            border-radius: 12px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s 0.5s ease-out forwards;
        }

        .cta-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .cta-description {
            font-size: 1.1rem;
            color: var(--color-secondary-text);
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .cta-button {
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            background: var(--color-primary);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background: var(--color-primary-hover);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        .footer {
            width: 100%;
            padding: 2rem;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .footer-link {
            color: var(--color-secondary-text);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .footer-link:hover {
            color: var(--color-primary);
        }

        .footer-social {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .social-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--color-icon-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }

        .social-icon:hover {
            background: var(--color-primary);
            color: white;
            transform: scale(1.1);
        }

        .footer-copyright {
            font-size: 0.9rem;
            color: var(--color-secondary-text);
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Animation Keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Animation Delays */
        .features-grid .feature-card:nth-child(1) { animation: fadeInUp 0.8s 0.1s ease-out forwards; }
        .features-grid .feature-card:nth-child(2) { animation: fadeInUp 0.8s 0.2s ease-out forwards; }
        .features-grid .feature-card:nth-child(3) { animation: fadeInUp 0.8s 0.3s ease-out forwards; }
        .features-grid .feature-card:nth-child(4) { animation: fadeInUp 0.8s 0.4s ease-out forwards; }
        .features-grid .feature-card:nth-child(5) { animation: fadeInUp 0.8s 0.5s ease-out forwards; }
        .features-grid .feature-card:nth-child(6) { animation: fadeInUp 0.8s 0.6s ease-out forwards; }
        .features-grid .feature-card:nth-child(7) { animation: fadeInUp 0.8s 0.7s ease-out forwards; }
        .features-grid .feature-card:nth-child(8) { animation: fadeInUp 0.8s 0.8s ease-out forwards; }
        .workshop-grid .workshop-card:nth-child(1) { animation: fadeInUp 0.8s 0.1s ease-out forwards; }
        .workshop-grid .workshop-card:nth-child(2) { animation: fadeInUp 0.8s 0.2s ease-out forwards; }
        .workshop-grid .workshop-card:nth-child(3) { animation: fadeInUp 0.8s 0.3s ease-out forwards; }
        .footer-links .footer-link:nth-child(1) { animation-delay: 0.1s; }
        .footer-links .footer-link:nth-child(2) { animation-delay: 0.2s; }
        .footer-links .footer-link:nth-child(3) { animation-delay: 0.3s; }
        .footer-links .footer-link:nth-child(4) { animation-delay: 0.4s; }
        .footer-links .footer-link:nth-child(5) { animation-delay: 0.5s; }
        .footer-social .social-icon:nth-child(1) { animation-delay: 0.6s; }
        .footer-social .social-icon:nth-child(2) { animation-delay: 0.7s; }
        .footer-social .social-icon:nth-child(3) { animation-delay: 0.8s; }
        .footer-social .social-icon:nth-child(4) { animation-delay: 0.9s; }
        .footer-copyright { animation-delay: 1s; }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .swiper { height: 400px; }
            .slide-title { font-size: 1.8rem; }
            .slide-description { font-size: 1rem; }
            .section-title { font-size: 1.8rem; }
            .cta-title { font-size: 1.8rem; }
            .cta-description { font-size: 1rem; }
            .footer-links { flex-direction: column; gap: 0.8rem; }
            .theme-toggle { top: 10px; right: 10px; }
        }

        @media (max-width: 480px) {
            .swiper { height: 350px; }
            .slide-title { font-size: 1.5rem; }
            .slide-description { font-size: 0.9rem; }
            .section-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <!-- Theme toggle -->
    <div class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
        <span>Dark Mode</span>
    </div>

    <div class="page-container">
        <!-- Hero Section with Slider -->
        <div class="hero-section">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide" style="background-image: url('{{ asset('artworks/urban-montage.jpg') }}');">
                        <div class="slide-content">
                            <h2 class="slide-title">Start Your First Creative Job</h2>
                            <p class="slide-description">Explore opportunities in with top certified creative community.</p>
                            <a href="{{ url('/freelancer/login') }}" class="slide-button">Find Job Now</a>
                        </div>
                    </div>
                    <div class="swiper-slide" style="background-image: url('{{ asset('artworks/storyboard.jpg') }}');">
                        <div class="slide-content">
                            <h2 class="slide-title">Find Your Freelancer</h2>
                            <p class="slide-description">Find worker that suit with your need.</p>
                            <a href="{{ url('/freelancer/login') }}" class="slide-button">Find Freelancer Now</a>
                        </div>
                    </div>
                    <div class="swiper-slide" style="background-image: url('{{ asset('artworks/film-poster.jpg') }}');">
                        <div class="slide-content">
                            <h2 class="slide-title">Content Creation Gigs</h2>
                            <p class="slide-description">Discover roles and jobs in video production, writing, and social media content creation.</p>
                            <a href="{{ url('/freelancer/login') }}" class="slide-button">Start Creative Now</a>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section">
            <h2 class="section-title">Why Choose Visionist for Your Career</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-search"></i></div>
                    <h3 class="feature-title">Advanced Job Search</h3>
                    <p class="feature-description">Filter jobs by category, location, and type to find the perfect creative role.</p>
                    <div class="feature-meta">
                        <span>1000+ Listings</span>
                        <span>Daily Updates</span>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-user"></i></div>
                    <h3 class="feature-title">Portfolio Showcase</h3>
                    <p class="feature-description">Build and share your portfolio to attract top clients and employers.</p>
                    <div class="feature-meta">
                        <span>Custom Profiles</span>
                        <span>Easy Upload</span>
                    </div>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-briefcase"></i></div>
                    <h3 class="feature-title">Freelancer</h3>
                    <p class="feature-description">Choose from freelance gig roles that match your skills.</p>
                    <div class="feature-meta">
                        <span>Flexible Options</span>
                        <span>Malaysian Reach</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workshop Section -->
        <div class="workshop-section">
            <h2 class="section-title">Upcoming Workshops</h2>
            <div class="workshop-grid">
                @foreach(App\Models\Workshop::where('publish', true)->orderBy('created_at', 'desc')->take(3)->get() as $workshop)
                <a href="{{ route('workshop.detail', $workshop->id) }}" class="workshop-card hover:shadow-2xl transition-shadow duration-200 block no-underline" style="text-decoration: none;">
                    <div class="workshop-image">
                        <img src="{{ asset('storage/' . (is_array($workshop->image) && !empty($workshop->image) ? $workshop->image[0] : 'WorkshopImage/default-workshop.jpg')) }}" alt="{{ $workshop->name }}" class="workshop-img">
                    </div>
                    <h3 class="workshop-title">{{ $workshop->name }}</h3>
                    <p class="workshop-description">{{ Str::limit($workshop->description, 100) }}</p>
                    <div class="workshop-meta">
                        <span>RM {{ number_format($workshop->price, 2) }}</span>
                        <span>{{ $workshop->start_date ? $workshop->start_date->format('F d, Y') : 'TBA' }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Top Artworks Section -->
        <div class="features-section" style="margin-top: 3rem;">
            <h2 class="section-title">Top Artworks</h2>
            <div class="features-grid">
                @foreach(App\Models\Artwork::with('user')->withCount('likes')->where('publish', true)->orderBy('likes_count', 'desc')->orderBy('created_at', 'desc')->take(8)->get() as $artwork)
                <a href="{{ route('artwork.detail', $artwork->id) }}" class="feature-card hover:shadow-2xl transition-shadow duration-200 block no-underline" style="text-decoration: none;">
                    <div class="feature-icon" style="background: none; box-shadow: none;">
                        <img src="{{ asset('storage/' . ($artwork->image_url ?? 'artworks/default-artwork.jpg')) }}" alt="{{ $artwork->title }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <h3 class="feature-title">{{ $artwork->title }}</h3>
                    <p class="feature-description">by {{ $artwork->user->name ?? 'Unknown' }}</p>
                    <div class="feature-meta">
                        <span><i class="fas fa-heart" style="color: #f87171;"></i> {{ $artwork->likes_count }}</span>
                        <span>{{ $artwork->created_at->format('M d, Y') }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="cta-section">
            <h2 class="cta-title">Start Your Creative Career</h2>
            <p class="cta-description">Join Visionist to access thousands of job opportunities and workshops tailored for creative professionals.</p>
            <a href="{{ url('/freelancer/login') }}" class="cta-button">Explore Jobs Now</a>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-links">
                <a href="#" class="footer-link">About Us</a>
                <a href="#" class="footer-link">How It Works</a>
                <a href="#" class="footer-link">Terms of Service</a>
                <a href="#" class="footer-link">Privacy Policy</a>
                <a href="#" class="footer-link">Contact Us</a>
            </div>
            <div class="footer-social">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p class="footer-copyright">© 2025 Visionist. All rights reserved.</p>
        </footer>
    </div>

    <!-- Swiper Slider JS -->
    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <!-- Theme Toggle JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme Toggle
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            const themeIcon = themeToggle.querySelector('i');
            const themeText = themeToggle.querySelector('span');

            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                setDarkMode();
            } else {
                setLightMode();
            }

            themeToggle.addEventListener('click', function() {
                if (body.classList.contains('dark-mode')) {
                    setLightMode();
                    localStorage.setItem('theme', 'light');
                } else {
                    setDarkMode();
                    localStorage.setItem('theme', 'dark');
                }
            });

            function setDarkMode() {
                body.classList.add('dark-mode');
                themeIcon.className = 'fas fa-sun';
                themeText.textContent = 'Light Mode';
            }

            function setLightMode() {
                body.classList.remove('dark-mode');
                themeIcon.className = 'fas fa-moon';
                themeText.textContent = 'Dark Mode';
            }

            // Swiper Initialization
            const swiper = new Swiper('.swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
</body>
</html>