<!-- resources/views/teacher/classes.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Classes - Teacher Dashboard</title>
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
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <!-- Create Class Section -->
            <div class="section-header">
                <h2>My Classes</h2>
                <button class="btn btn-primary" onclick="toggleCreateForm()">+ Create New Class</button>
            </div>

            <!-- Create Class Form (Hidden by default) -->
            <div id="createClassForm" class="create-form" style="display: none;">
                <form action="{{ route('teacher.classes.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Class Name</label>
                            <input type="text" id="name" name="name" required placeholder="e.g., Scratch Basics 2024">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3" placeholder="Brief description of the class..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Create Class</button>
                        <button type="button" class="btn btn-outline" onclick="toggleCreateForm()">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Classes Grid -->
            @if($classes instanceof \Illuminate\Support\Collection && $classes->isNotEmpty())
            <div class="classes-grid">
                @foreach($classes as $class)
                <div class="class-card">
                    <div class="class-header">
                        <h3>{{ $class->name }}</h3>
                        <span class="class-code">📋 {{ $class->class_code }}</span>
                    </div>

                    @if($class->description)
                    <p class="class-description">{{ Str::limit($class->description, 300) }}</p>
                    @endif

                    <div class="class-stats">
                        <div class="class-stat">
                            <span class="stat-number">{{ $class->students_count }}</span>
                            <span class="stat-label">Students</span>
                        </div>
                        <div class="class-stat">
                            <span class="stat-number">{{ $class->materials_count }}</span>
                            <span class="stat-label">Materials</span>
                        </div>
                    </div>

                    <div class="class-actions">
                        <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-primary">View Class</a>
                        <form action="{{ route('teacher.classes.destroy', $class->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will delete all materials in this class!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h3>No Classes Yet</h3>
                <p>Create your first class to start adding learning materials!</p>
                <button class="btn btn-primary" onclick="toggleCreateForm()">Create Your First Class</button>
            </div>
            @endif
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/footer-reveal.js') }}"></script>

    <script>
        function toggleCreateForm() {
            const form = document.getElementById('createClassForm');
            if (form.style.display === 'none') {
                form.style.display = 'block';
                form.scrollIntoView({ behavior: 'smooth' });
            } else {
                form.style.display = 'none';
            }
        }

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

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>