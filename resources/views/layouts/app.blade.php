<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Learning Platform')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ Auth::check() ? route('student.home') : route('welcome') }}" class="logo">
                <div class="logo-icon">EL</div>
                <span>E-Learning</span>
            </a>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>

            @auth
            <div class="nav-links" id="navLinks">
                <a href="{{ route('student.home') }}">Home</a>
                <a href="{{ route('student.classes') }}">My Classes</a>
                <a href="{{ route('student.materials.category', 'scratch') }}">Scratch</a>
                <a href="{{ route('student.materials.category', 'picto-ai') }}">Picto AI</a>
                <div class="user-info">
                    <span class="user-name">Hi, {{ Auth::user()->name }}</span>
                    <form action="{{ route('student.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline">Logout</button>
                    </form>
                </div>
            </div>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p>&copy; 2024 E-Learning Platform. All rights reserved.</p>
    </footer>

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