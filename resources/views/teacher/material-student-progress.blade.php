<!-- resources/views/teacher/material-student-progress.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress - {{ $material->title }} - Teacher Dashboard</title>
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

            <!-- Material Header -->
            <div class="class-detail-header">
                <div>
                    <h1>{{ $material->title }}</h1>
                    <p class="class-description">{{ Str::limit($material->description, 400) }}</p>
                    <div class="material-badges">
                        <span class="badge badge-{{ $material->category }}">
                            {{ ucfirst(str_replace('-', ' ', $material->category)) }}
                        </span>
                        <span class="badge badge-difficulty badge-{{ $material->difficulty }}">
                            {{ ucfirst($material->difficulty) }}
                        </span>
                        @if($material->duration)
                        <span class="badge badge-duration">⏱️ {{ $material->duration }} min</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <h3>{{ $totalStudents }}</h3>
                        <p>Total Students</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <a href="{{ route('teacher.materials.progress', [$class->id, $material->id]) }}?filter=completed" class="stat-link">
                            <h3>{{ $completedStudents }}</h3>
                            <p>Completed</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Students Progress Section -->
            <div class="section-header">
                <h2>Student Progress</h2>
            </div>

            @if($students->count() > 0)
            <div class="students-progress-container">
                <div class="students-progress-list">
                    @foreach($students as $student)
                    <div class="student-progress-card">
                        <div class="student-info">
                            @if($student['student']->hasProfilePhoto())
                                <img class="student-avatar" src="{{ $student['student']->profile_photo_data_uri }}" alt="Profile photo">
                            @else
                                <div class="student-avatar">{{ substr($student['student']->name, 0, 1) }}</div>
                            @endif
                            <div class="student-details">
                                <h4>{{ $student['student']->name }}</h4>
                                <p>{{ $student['student']->email }}</p>
                            </div>
                            <div class="progress-status {{ $student['is_completed'] ? 'completed' : 'in-progress' }}">
                                @if($student['is_completed'])
                                    <span class="status-icon">✅</span> <span class="status-text">Completed</span>
                                @else
                                    <span class="status-icon">⏳</span> <span class="status-text">In Progress</span>
                                @endif
                            </div>
                        </div>

                        <div class="progress-info">
                            <div class="progress-bar-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $student['progress_percent'] ?? 0 }}%"></div>
                                </div>
                                <span class="progress-percentage">{{ $student['progress_percent'] ?? 0 }}%</span>
                            </div>
                        </div>

                        <div class="progress-actions">
                            <a href="{{ route('teacher.materials.students.progress', [$class->id, $material->id, $student['student']->id]) }}" class="btn btn-outline">View Details</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">📊</div>
                @if(isset($filter) && $filter === 'completed')
                <h3>No Completed Students</h3>
                <p>No students have completed this material yet.</p>
                @else
                <h3>No Student Progress Yet</h3>
                <p>No students have started this material yet.</p>
                @endif
            </div>
            @endif
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/footer-reveal.js') }}"></script>

    <script>
        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Animate progress bars on page load
        document.addEventListener('DOMContentLoaded', function() {
            const progressFills = document.querySelectorAll('.progress-fill');
            progressFills.forEach(fill => {
                const targetWidth = fill.style.width;
                fill.style.width = '0%';
                // Use a small delay to ensure the animation is visible
                setTimeout(() => {
                    fill.style.transition = 'width 1s ease-out';
                    fill.style.width = targetWidth;
                }, 100);
            });
        });
    </script>
</body>
</html>
