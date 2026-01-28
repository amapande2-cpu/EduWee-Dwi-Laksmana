<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduWee E-Learning Platform')</title>
    <link rel="icon" href="{{ asset('images/logo_eduwee.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ Auth::check() ? route('student.home') : route('welcome') }}" class="logo">
               <img src="{{ asset('images/logo_eduwee.png') }}" alt="its should be logo" class="logo-img">
                <span>EduWee</span>
            </a>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>

            @auth
            <div class="nav-links" id="navLinks">
                <a href="{{ route('student.home') }}">Home</a>
                <a href="{{ route('student.classes.index') }}">My Classes</a>
                <a href="{{ route('materials.public') }}"> Public Materials</a>
                <div class="user-info">
                    <a class="user-name profile-link" href="{{ route('student.profile.show') }}">
                        @if(Auth::user()->hasProfilePhoto())
                            <img class="nav-avatar" src="{{ Auth::user()->profile_photo_data_uri }}" alt="Profile photo">
                        @else
                            <span class="nav-avatar nav-avatar--fallback">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        @endif
                        <span class="profile-link__text">
                            <span class="profile-link__name">{{ Auth::user()->name }}</span>
                            <span class="profile-link__meta">Student</span>
                        </span>
                    </a>
                </div>
            </div>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/footer-reveal.js') }}"></script>

    <script>
        function toggleMobileMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.querySelector('.nav-container');
            const navLinks = document.getElementById('navLinks');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (!nav.contains(event.target) && !menuBtn.contains(event.target)) {
                navLinks.classList.remove('active');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
