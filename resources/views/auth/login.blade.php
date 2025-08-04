<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="png" href="{{ asset('images/favgiatlogo.png') }}">
    <title>Login | GIAT CORE</title>
    <link href="{{ asset('css/loginstyle.css') }}" rel="stylesheet">
</head>

<body>
    <div class="main-container">
        <!-- Hero Card - LEFT SIDE -->
        <div class="hero-card">
            <div class="hero-content">
                <div class="slides-container">
                    <div class="slide">
                        <h1 class="hero-title">GROW</h1>
                        <p class="hero-subtitle">Continuously grow without stagnation — always striving for growth wherever you are.</p>
                    </div>
                    <div class="slide">
                        <h1 class="hero-title">Collaborate with<br>Your Team</h1>
                        <p class="hero-subtitle">Unite your team's creativity with advanced project management tools and real-time collaboration features.</p>
                    </div>
                    <div class="slide">
                        <h1 class="hero-title">Secure & Reliable<br>Platform</h1>
                        <p class="hero-subtitle">Your data is protected with enterprise-grade security while maintaining easy access to all your important files.</p>
                    </div>
                    <div class="slide">
                        <h1 class="hero-title">Analytics &<br>Insights</h1>
                        <p class="hero-subtitle">Track your project progress with detailed analytics and gain valuable insights to optimize your workflow.</p>
                    </div>
                </div>
                <div class="pagination-dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
        </div>

        <!-- Login Section - RIGHT SIDE -->
        <div class="login-section">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="logo-container">
                    <img src="{{ asset('images/giatlogo.png') }}" alt="GIAT" class="logo-img">
                    <div class="logo-text-container">
                        <span class="logo-text">CORE</span>
                        <div class="login-logo-subtitle">(Collaboration Needed Request)</div>
                    </div>
                </div>
                <a href="#" class="back-link">
                    Back to website →
                </a>
            </div>

            <!-- Login Content -->
            <div class="login-content">
                <div class="login-header">
                    <h2 class="login-title">Sign in to your account</h2>
                    <p class="login-subtitle">Don't have an account? <a href="{{ url('/register') }}">Create account</a></p>
                </div>

                @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="form-container">
                        <div class="form-group">
                            <input
                                type="text"
                                class="form-input"
                                placeholder="Username or Email"
                                name="username"
                                value="{{ old('username') }}"
                                required>
                        </div>

                        <div class="password-group">
                            <input
                                type="password"
                                class="password-input"
                                placeholder="Enter your password"
                                name="password"
                                required>
                            <button type="button" class="password-toggle">👁</button>
                        </div>

                        <button type="submit" class="login-button">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="additional-links">
                    <p>Forgot your password? <a href="{{ url('/forgetpass') }}">Reset Password</a></p>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                Powered by <strong>GIAT CORE</strong>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.querySelector('.password-toggle').addEventListener('click', function() {
            const input = document.querySelector('.password-input');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '🙈';
        });

        // Auto-sliding carousel animation
        let currentSlide = 0;
        const dots = document.querySelectorAll('.dot');
        const slidesContainer = document.querySelector('.slides-container');
        const totalSlides = dots.length;

        function slideToNext() {
            // Remove active class from all dots
            dots.forEach(dot => dot.classList.remove('active'));

            // Add active class to current dot
            dots[currentSlide].classList.add('active');

            // Slide to current position
            const translateX = -currentSlide * (100 / totalSlides);
            slidesContainer.style.transform = `translateX(${translateX}%)`;

            // Move to next slide
            currentSlide = (currentSlide + 1) % totalSlides;
        }

        // Start animation immediately and repeat every 3.5 seconds
        slideToNext();
        setInterval(slideToNext, 3500);

        // Add click functionality to dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                slideToNext();
            });
        });
    </script>
</body>

</html>