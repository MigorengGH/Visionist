<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Visionist</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <style>
        :root {
            --color-text: #1f2a44;
            --color-background: linear-gradient(135deg, #a6c4ff, #fa93a8);
            --color-primary: #7c3aed;
            --color-primary-hover: #5b21b6;
            --color-accent: #facc15;
            --color-card: rgba(255, 255, 255, 0.95);
            --color-section: rgba(255, 255, 255, 0.85);
            --color-secondary-text: #64748b;
            --color-icon-bg: #f1f5f9;
            --color-border: #ea8aff;
        }

        .dark-mode {
            --color-text: #e5e7eb;
            --color-background: linear-gradient(135deg, #a6c4ff, #fa93a8);
            --color-primary: #a78bfa;
            --color-primary-hover: #7c3aed;
            --color-accent: #facc15;
            --color-card: rgba(31, 41, 68, 0.95);
            --color-section: rgba(31, 41, 68, 0.85);
            --color-secondary-text: #94a3b8;
            --color-icon-bg: #334155;
            --color-border: #374151;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--color-text);
            background: var(--color-background);
            min-height: 100vh;
            text-align: center;
            overflow-x: hidden;
            position: relative;
            transition: all 0.4s ease;
        }

        /* Enhanced Background floating elements with abstract shapes */
        .bg-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
            opacity: 0.3;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            background: var(--color-primary);
            opacity: 0.2;
            filter: blur(10px);
            animation: floatAbstract 18s ease-in-out infinite alternate;
            transition: transform 0.5s ease;
        }

        .floating-element:nth-child(odd) {
            border-radius: 50%;
        }

        .floating-element:nth-child(even) {
            clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
        }

        .element-1 { top: 10%; left: 15%; width: 70px; height: 70px; }
        .element-2 { top: 65%; left: 20%; width: 90px; height: 90px; animation-delay: -5s; }
        .element-3 { top: 25%; right: 10%; width: 50px; height: 50px; animation-delay: -8s; }
        .element-4 { bottom: 15%; right: 25%; width: 80px; height: 80px; }
        .element-5 { bottom: 20%; left: 35%; width: 60px; height: 60px; animation-delay: -3s; }
        .element-6 { top: 45%; right: 15%; width: 75px; height: 75px; animation-delay: -10s; }

        .page-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 4rem;
            align-items: center;
        }

        /* Theme toggle with interactive animation */
        .theme-toggle {
            position: fixed;
            top: 25px;
            right: 25px;
            z-index: 100;
            background: var(--color-card);
            border-radius: 50px;
            padding: 10px 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.4s ease;
            border: 1px solid var(--color-border);
        }

        .theme-toggle:hover {
            transform: translateY(-3px) rotate(5deg);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .theme-toggle i { font-size: 18px; color: var(--color-text); transition: transform 0.4s ease; }
        .theme-toggle:hover i { transform: scale(1.2); }
        .theme-toggle span { font-size: 15px; font-weight: 600; color: var(--color-text); }

        /* Hero section with abstract overlay animation */
        .hero-container {
            max-width: 900px;
            padding: 4rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            background: var(--color-card);
            backdrop-filter: blur(12px);
            transform: translateY(30px);
            animation: fadeInUp 1.8s ease-out forwards;
            position: relative;
            width: 100%;
            overflow: hidden;
        }

        .hero-container::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            padding: 2px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.5;
            animation: borderPulse 4s infinite ease-in-out;
        }

        .hero-container::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%);
            animation: abstractOverlay 12s infinite ease-in-out;
            z-index: -1;
        }

        .logo-container { display: flex; justify-content: center; margin-bottom: 1.5rem; }
        .logo {
            width: 180px;
            transition: transform 0.4s ease;
            animation: pulseGlow 5s infinite ease-in-out;
        }
        .logo:hover { transform: scale(1.15) rotate(3deg); }

        .title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 4rem;
            font-weight: 400;
            margin-top: 0;
            background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 6s infinite alternate;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 1.4rem;
            color: var(--color-secondary-text);
            margin-top: 0.75rem;
            animation: fadeInText 2s ease-out forwards 0.6s;
        }

        .button-container {
            margin-top: 2.5rem;
            position: relative;
            display: inline-block;
            animation: fadeInUp 2s ease-out forwards 1.2s;
        }

        .button-glow {
            position: absolute;
            inset: 0;
            background: var(--color-primary);
            filter: blur(25px);
            opacity: 0.4;
            border-radius: 14px;
            z-index: -1;
            animation: glowPulse 2.5s infinite alternate;
        }

        .button {
            padding: 1.2rem 2.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--color-primary), var(--color-primary-hover));
            border-radius: 14px;
            text-decoration: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .button:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
            background: linear-gradient(135deg, var(--color-primary-hover), var(--color-accent));
        }

        .button::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s, height 0.5s;
        }

        .button:hover::after { width: 400px; height: 400px; }

        /* Features section with dynamic hover effects */
        .features-section {
            width: 100%;
            animation: fadeInUp 1.8s ease-out forwards 0.8s;
        }

        .features-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 6s infinite alternate;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--color-section);
            border-radius: 16px;
            padding: 2rem;
            text-align: left;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            border: 1px solid var(--color-border);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            animation: cardOverlay 10s infinite ease-in-out;
        }

        .feature-card:hover::before { opacity: 0.3; }

        .feature-card:hover {
            transform: translateY(-8px) rotate(1deg);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--color-icon-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            background: var(--color-primary);
            transform: scale(1.15) rotate(10deg);
        }

        .feature-icon i {
            font-size: 1.75rem;
            color: var(--color-primary);
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-icon i { color: white; transform: rotate(-10deg); }

        .feature-title {
            font-size: 1.35rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .feature-description {
            font-size: 1rem;
            color: var(--color-secondary-text);
            line-height: 1.6;
        }

        /* Testimonials section with subtle animation */
        .testimonials-section {
            width: 100%;
            animation: fadeInUp 1.8s ease-out forwards 1s;
        }

        .testimonials-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 2rem;
            background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 6s infinite alternate;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .testimonial-card {
            background: var(--color-section);
            border-radius: 16px;
            padding: 2rem;
            text-align: left;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid var(--color-border);
            overflow: hidden;
        }

        .testimonial-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .quote-icon {
            font-size: 2.5rem;
            color: var(--color-primary);
            opacity: 0.15;
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            transition: all 0.4s ease;
        }

        .testimonial-card:hover .quote-icon {
            transform: scale(1.2);
            opacity: 0.3;
        }

        .testimonial-text {
            font-style: italic;
            font-size: 1rem;
            color: var(--color-secondary-text);
            line-height: 1.7;
            margin-bottom: 1.75rem;
            transition: transform 0.4s ease;
        }

        .testimonial-card:hover .testimonial-text { transform: translateX(5px); }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .author-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 1rem;
            background: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.4s ease;
        }

        .testimonial-card:hover .author-avatar { transform: rotate(360deg); }

        .author-name { font-weight: 600; font-size: 1rem; }
        .author-position { font-size: 0.9rem; color: var(--color-secondary-text); }

        /* Call to action section with dynamic background */
        .cta-section {
            width: 100%;
            animation: fadeInUp 1.8s ease-out forwards 1.5s;
            background: var(--color-card);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            padding: 2px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.5;
            animation: borderPulse 4s infinite ease-in-out;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%);
            animation: abstractOverlay 15s infinite ease-in-out;
            z-index: -1;
        }

        .cta-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.25rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
        }

        .cta-description {
            font-size: 1.15rem;
            color: var(--color-secondary-text);
            max-width: 650px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
        }

        /* Footer with subtle hover animations */
        .footer {
            width: 100%;
            margin-top: 3rem;
            padding: 2rem;
            animation: fadeInUp 1.8s ease-out forwards 2s;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .footer-link {
            color: var(--color-secondary-text);
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.4s ease;
            position: relative;
        }

        .footer-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: var(--color-primary);
            transition: all 0.4s ease;
            transform: translateX(-50%);
        }

        .footer-link:hover::after { width: 100%; }
        .footer-link:hover {
            color: var(--color-primary);
            transform: translateY(-2px);
        }

        .footer-social {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--color-section);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
            color: var(--color-secondary-text);
        }

        .social-icon:hover {
            background: var(--color-primary);
            color: white;
            transform: scale(1.1) rotate(10deg);
        }

        .footer-copyright {
            font-size: 0.9rem;
            color: var(--color-secondary-text);
        }

        /* New and Enhanced Animations */
        @keyframes floatAbstract {
            0% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(20px, -20px) rotate(45deg); }
            50% { transform: translate(-25px, 25px) rotate(90deg); }
            75% { transform: translate(15px, 15px) rotate(135deg); }
            100% { transform: translate(-20px, -25px) rotate(180deg); }
        }

        @keyframes borderPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.02); }
        }

        @keyframes abstractOverlay {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-10%, 10%) rotate(180deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }

        @keyframes cardOverlay {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-10%, 10%) rotate(90deg); }
            100% { transform: translate(0, 0) rotate(180deg); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInText {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); filter: brightness(1); }
            50% { transform: scale(1.05); filter: brightness(1.1); }
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        @keyframes glowPulse {
            0% { opacity: 0.3; transform: scale(0.9); }
            100% { opacity: 0.5; transform: scale(1.2); }
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .hero-container { padding: 2.5rem 1.5rem; }
            .title { font-size: 2.5rem; }
            .subtitle { font-size: 1.2rem; }
            .features-grid, .testimonials-grid { grid-template-columns: 1fr; }
            .cta-section { padding: 2.5rem 1.5rem; }
            .cta-title { font-size: 1.75rem; }
            .cta-description { font-size: 1rem; }
            .footer-links { flex-direction: column; gap: 1rem; }
            .theme-toggle { top: 15px; right: 15px; }
        }

        .feature-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 1.25rem;
        }

        .workshop-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .feature-card:hover .workshop-img {
            transform: scale(1.1);
        }

        .feature-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: var(--color-primary);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Background floating elements -->
    <div class="bg-elements">
        <div class="floating-element element-1"></div>
        <div class="floating-element element-2"></div>
        <div class="floating-element element-3"></div>
        <div class="floating-element element-4"></div>
        <div class="floating-element element-5"></div>
        <div class="floating-element element-6"></div>
    </div>

    <!-- Theme toggle -->
    <div class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
        <span>Dark Mode</span>
    </div>

    <div class="page-container">
        <!-- Hero Section -->
        <div class="hero-container">
            <div class="logo-container">
                <img src="{{ asset('logoV/logo-light.svg') }}" class="logo" alt="Visionist Logo" id="lightLogo">
                <img src="{{ asset('logoV/logo-dark.svg') }}" class="logo" alt="Visionist Logo" id="darkLogo" style="display: none;">
            </div>
            <h1 class="title">Welcome to Visionist</h1>
            <p class="subtitle">Empowering creative professionals with endless opportunities.</p>
            <div class="button-container">
                <div class="button-glow"></div>
                <a href="https://myvisionist.test/freelancer" class="button">Get Started</a>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section">
            <h2 class="features-title">Upcoming Workshops</h2>
            <div class="features-grid">
                @foreach(App\Models\Workshop::where('publish', true)->orderBy('created_at', 'desc')->take(3)->get() as $workshop)
                <div class="feature-card">
                    <div class="feature-image">
                        <img src="{{ asset('storage/' . (is_array($workshop->image) && !empty($workshop->image) ? $workshop->image[0] : 'WorkshopImage/default-workshop.jpg')) }}" alt="{{ $workshop->name }}" class="workshop-img">
                    </div>
                    <h3 class="feature-title">{{ $workshop->name }}</h3>
                    <p class="feature-description">{{ Str::limit($workshop->description, 100) }}</p>
                    <div class="feature-meta">
                        <span class="feature-price">RM {{ number_format($workshop->price, 2) }}</span>
                        <span class="feature-date">{{ $workshop->start_date ? $workshop->start_date->format('F d, Y') : 'TBA' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="testimonials-section">
            <h2 class="testimonials-title">What Our Users Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <i class="fas fa-quote-right quote-icon"></i>
                    <p class="testimonial-text">Visionist has transformed my freelance career. I've connected with amazing clients and doubled my income within just a few months!</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">SL</div>
                        <div class="author-info">
                            <span class="author-name">Sarah Lee</span>
                            <span class="author-position">Graphic Designer</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <i class="fas fa-quote-right quote-icon"></i>
                    <p class="testimonial-text">The platform is intuitive and the community is incredibly supportive. I've found consistent work that aligns perfectly with my creative vision.</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">MJ</div>
                        <div class="author-info">
                            <span class="author-name">Michael Johnson</span>
                            <span class="author-position">Web Developer</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <i class="fas fa-quote-right quote-icon"></i>
                    <p class="testimonial-text">As a content creator, finding the right platform was crucial. Visionist not only helped me showcase my portfolio effectively but also find clients who value my work.</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">RP</div>
                        <div class="author-info">
                            <span class="author-name">Rachel Patel</span>
                            <span class="author-position">Content Creator</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="cta-section">
            <h2 class="cta-title">Ready to Start Your Creative Journey?</h2>
            <p class="cta-description">Join thousands of creative professionals who have found success on Visionist. Our platform is designed to help you showcase your talent, connect with clients, and grow your business.</p>
            <div class="button-container">
                <div class="button-glow"></div>
                <a href="https://visionist.test/freelancer" class="button">Join Visionist Today</a>
            </div>
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

    <!-- JavaScript for theme toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            const themeIcon = themeToggle.querySelector('i');
            const themeText = themeToggle.querySelector('span');
            const lightLogo = document.getElementById('lightLogo');
            const darkLogo = document.getElementById('darkLogo');

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
                if (lightLogo && darkLogo) {
                    lightLogo.style.display = 'none';
                    darkLogo.style.display = 'block';
                }
            }

            function setLightMode() {
                body.classList.remove('dark-mode');
                themeIcon.className = 'fas fa-moon';
                themeText.textContent = 'Dark Mode';
                if (lightLogo && darkLogo) {
                    lightLogo.style.display = 'block';
                    darkLogo.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
