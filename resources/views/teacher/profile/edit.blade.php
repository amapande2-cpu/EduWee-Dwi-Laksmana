<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Teacher</title>
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
                <h2>Edit Profile</h2>
            </div>

            <div class="create-form profile-form">
                <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $teacher->name) }}" required>
                            @error('name') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $teacher->email) }}" required>
                            @error('email') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone">Phone Number (Optional)</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone', $teacher->phone) }}" placeholder="e.g., +62 812 3456 7890">
                            @error('phone') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="subject">Subject/Specialization (Optional)</label>
                            <input id="subject" name="subject" type="text" value="{{ old('subject', $teacher->subject) }}" placeholder="e.g., Mathematics, Computer Science">
                            @error('subject') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="profile_photo">Profile Photo (optional)</label>
                            <input id="profile_photo" name="profile_photo" type="file" accept="image/png,image/jpeg,image/webp">
                            @error('profile_photo') <span class="error">{{ $message }}</span> @enderror

                            <div class="profile-photo-row">
                                @if($teacher->hasProfilePhoto())
                                    <img class="avatar-img" src="{{ $teacher->profile_photo_data_uri }}" alt="Profile photo" style="width:56px;height:56px;">
                                    <label class="profile-remove-photo">
                                        <input type="checkbox" name="remove_photo" value="1"> Remove current photo
                                    </label>
                                @else
                                    <div class="avatar-fallback" style="width:56px;height:56px;">{{ strtoupper(substr($teacher->name, 0, 1)) }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr style="border:0;border-top:1px solid rgba(0,0,0,0.08); margin: 1.25rem 0;" />

                    <div class="form-row">
                        <div class="form-group">
                            <label for="current_password">Confirm with Password</label>
                            <input id="current_password" name="current_password" type="password" required placeholder="Enter your current password">
                            @error('current_password') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('teacher.profile.show') }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/footer-reveal.js') }}"></script>
</body>
</html>

