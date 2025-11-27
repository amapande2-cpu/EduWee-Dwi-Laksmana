<!-- resources/views/teacher/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - E-Learning Platform</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('teacher.dashboard') }}" class="logo">
                <div class="logo-icon">EL</div>
                <span>Teacher Dashboard</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
                <div class="user-info">
                    <span class="user-name">👨‍🏫 {{ Auth::guard('teacher')->user()->name }}</span>
                    <form action="{{ route('teacher.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline">Logout</button>
                    </form>
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

            <!-- Stats Section -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-content">
                        <h3>{{ $classes->count() }}</h3>
                        <p>Total Classes</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <h3>{{ $totalStudents }}</h3>
                        <p>Total Students</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📖</div>
                    <div class="stat-content">
                        <h3>{{ $totalMaterials }}</h3>
                        <p>Total Materials</p>
                    </div>
                </div>
            </div>

            <!-- Create Class Section -->
            <div class="section-header">
                <h2>My Classes</h2>
                <button class="btn btn-primary" onclick="toggleCreateForm()">+ Create New Class</button>
            </div>

            <!-- Create Class Form (Hidden by default) -->
            <div id="createClassForm" class="create-form" style="display: none;">
                <form action="{{ route('teacher.class.create') }}" method="POST">
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
            @if($classes->count() > 0)
            <div class="classes-grid">
                @foreach($classes as $class)
                <div class="class-card">
                    <div class="class-header">
                        <h3>{{ $class->name }}</h3>
                        <span class="class-code">📋 {{ $class->class_code }}</span>
                    </div>
                    
                    @if($class->description)
                    <p class="class-description">{{ $class->description }}</p>
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
                        <a href="{{ route('teacher.class.show', $class->id) }}" class="btn btn-primary">View Class</a>
                        <form action="{{ route('teacher.class.delete', $class->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This will delete all materials in this class!')">
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
        <p>&copy; 2024 E-Learning Platform - Teacher Portal</p>
    </footer>

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