<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Teacher</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="icon" href="{{ asset('images/logo_eduwee.png') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('teacher.dashboard') }}" class="logo">
                <img src="{{ asset('images/logo_eduwee.png') }}" alt="Logo" class="logo-img">
                <span>Teacher Dashboard</span>
            </a>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>

            <div class="nav-links" id="navLinks">
                <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
                <a href="{{ route('teacher.classes.index') }}">Classes</a>
                <div class="user-info">
                    <a class="user-name profile-link" href="{{ route('teacher.profile.show') }}">
                        @if(Auth::guard('teacher')->user()->hasProfilePhoto())
                            <img class="nav-avatar" src="{{ Auth::guard('teacher')->user()->profile_photo_data_uri }}" alt="Profile photo">
                        @else
                            <span class="nav-avatar nav-avatar--fallback">{{ strtoupper(substr(Auth::guard('teacher')->user()->name, 0, 1)) }}</span>
                        @endif
                        <span class="profile-link__text">
                            <span class="profile-link__name">{{ Auth::guard('teacher')->user()->name }}</span>
                            <span class="profile-link__meta">Teacher</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div class="container profile-page">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="section-header profile-header">
                <h2>My Profile</h2>
            </div>

            <div class="create-form profile-form profile-card">
                <div class="profile-card__top">
                    @if($teacher->hasProfilePhoto())
                        <img class="profile-card__avatar avatar-img" src="{{ $teacher->profile_photo_data_uri }}" alt="Profile photo">
                    @else
                        <div class="profile-card__avatar avatar-fallback">{{ strtoupper(substr($teacher->name, 0, 1)) }}</div>
                    @endif

                    <div class="profile-card__info">
                        <div class="profile-card__name">{{ $teacher->name }}</div>
                        <div class="profile-card__meta">Teacher</div>
                        <div class="profile-card__email">{{ $teacher->email }}</div>
                        @if($teacher->phone)
                            <div class="profile-card__field"><strong>Phone:</strong> {{ $teacher->phone }}</div>
                        @endif
                        @if($teacher->subject)
                            <div class="profile-card__field"><strong>Subject:</strong> {{ $teacher->subject }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-actions" style="justify-content:center;">
                    <a href="{{ route('teacher.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    <form action="{{ route('teacher.logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/footer-reveal.js') }}"></script>
</body>
</html>

