<!-- resources/views/teacher/class-edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class - {{ $class->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="icon" href="{{ asset('images/logo_eduwee.png') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('teacher.dashboard') }}" class="logo">
               <img src="{{ asset('images/logo_eduwee.png') }}" alt="its should be logo" class="logo-img">
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
        <div class="container">
            <div class="form-container">
                <div class="form-header">
                    <h1>Edit Class</h1>
                    <p>Update class information</p>
                </div>

                <form action="{{ route('teacher.classes.update', $class->id) }}" method="POST" class="material-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Class Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $class->name) }}"
                            required 
                            placeholder="e.g., Scratch Programming 2024"
                        >
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="5" 
                            placeholder="Brief description of the class..."
                        >{{ old('description', $class->description) }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-info">
                        <p><strong>Class Code:</strong> {{ $class->class_code }}</p>
                        <p class="help-text">Class code cannot be changed</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Class</button>
                        <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-outline">Cancel</a>
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
