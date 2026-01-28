<!-- resources/views/teacher/material-student-detail-progress.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->name }} - Progress Details - {{ $material->title }} - Teacher Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="icon" href="{{ asset('images/logo_eduwee.png') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('teacher.dashboard') }}" class="logo">
               <img src="{{ asset('images/logo_eduwee.png') }}" alt="logo" class="logo-img">
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

            <!-- Student Header -->
            <div class="class-detail-header">
                <div>
                    <h1>{{ $student->name }}</h1>
                    <p class="class-description" style="color: white;">{{ $student->email }}</p>
                    <div class="material-badges">
                        <span class="badge badge-primary">
                            Progress: {{ $progress->progress_percent ?? 0 }}%
                        </span>
                        @if($progress->is_completed ?? false)
                        <span class="badge badge-success">✅ Completed</span>
                        @else
                        <span class="badge badge-warning">⏳ In Progress</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Material Info -->
            <div class="section-header">
                <h2>{{ $material->title }}</h2>
                <p>{{ Str::limit($material->description, 200) }}</p>
            </div>

            <!-- Overall Progress -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-content">
                        <h3>{{ $progress->progress_percent ?? 0 }}%</h3>
                        <p>Overall Progress</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <h3>{{ $progress->completed_steps ?? 0 }}</h3>
                        <p>Steps Completed</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📝</div>
                    <div class="stat-content">
                        <h3>{{ $material->steps->count() }}</h3>
                        <p>Total Steps</p>
                    </div>
                </div>

                @if($progress->is_completed ?? false)
                <div class="stat-card">
                    <div class="stat-icon">🎉</div>
                    <div class="stat-content">
                        <h3>Completed</h3>
                        <p>Material Finished</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Step-by-Step Progress -->
            <div class="section-header">
                <h2>Step-by-Step Progress</h2>
            </div>

            @if($stepProgress)
            <div class="steps-progress-container">
                @foreach($stepProgress as $index => $stepData)
                <div class="step-progress-card {{ $stepData['is_completed'] ? 'completed' : 'pending' }}">
                    <div class="step-progress-header">
                        <div class="step-number">
                            <span class="step-number-badge">{{ $stepData['step']->step_order }}</span>
                        </div>
                        <div class="step-info">
                            <h3>{{ $stepData['step']->title }}</h3>
                            @if($stepData['is_completed'])
                            <div class="completion-info">
                                <span class="completion-icon">✅</span>
                                <span class="completion-text">Completed {{ $stepData['completed_at']->format('M j, Y \a\t g:i A') }}</span>
                            </div>
                            @else
                            <div class="pending-info">
                                <span class="pending-icon">⏳</span>
                                <span class="pending-text">Not started yet</span>
                            </div>
                            @endif
                        </div>
                        <div class="step-status">
                            @if($stepData['is_completed'])
                            <span class="status-badge completed">Done</span>
                            @else
                            <span class="status-badge pending">Pending</span>
                            @endif
                        </div>
                    </div>

                    @if($stepData['step']->description)
                    <div class="step-description">
                        <p>{{ Str::limit($stepData['step']->description, 150) }}</p>
                    </div>
                    @endif

                    @if($stepData['step']->image || $stepData['step']->video_url)
                    <div class="step-media-preview">
                        @if($stepData['step']->image)
                        <div class="media-item">
                            <span class="media-icon">🖼️</span>
                            <span>Image attached</span>
                        </div>
                        @endif
                        @if($stepData['step']->video_url)
                        <div class="media-item">
                            <span class="media-icon">🎥</span>
                            <span>Video attached</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>No Progress Yet</h3>
                <p>This student hasn't started working on this material yet.</p>
            </div>
            @endif
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/footer-reveal.js') }}"></script>

    <script>
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
