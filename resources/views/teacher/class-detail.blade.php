<!-- resources/views/teacher/class-detail.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $class->name }} - Teacher Dashboard</title>
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
                <a href="{{ route('teacher.dashboard') }}">← Back to Dashboard</a>
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

            <!-- Class Header -->
            <div class="class-detail-header">
                <div>
                    <h1>{{ $class->name }}</h1>
                    @if($class->description)
                    <p class="class-description">{{ $class->description }}</p>
                    @endif
                </div>
                <div class="header-actions">
                    <div class="class-code-large">
                        <span class="code-label">Class Code:</span>
                        <span class="code-value">{{ $class->class_code }}</span>
                    </div>
                    <a href="{{ route('teacher.class.edit', $class->id) }}" class="btn btn-outline">✏️ Edit Class</a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-content">
                        <h3>{{ $class->students->count() }}</h3>
                        <p>Enrolled Students</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📖</div>
                    <div class="stat-content">
                        <h3>{{ $class->materials->count() }}</h3>
                        <p>Total Materials</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🎮</div>
                    <div class="stat-content">
                        <h3>{{ $class->materials->where('category', 'scratch')->count() }}</h3>
                        <p>Scratch Materials</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🤖</div>
                    <div class="stat-content">
                        <h3>{{ $class->materials->where('category', 'picto-ai')->count() }}</h3>
                        <p>Picto AI Materials</p>
                    </div>
                </div>
            </div>

            <!-- Materials Section -->
            <div class="section-header">
                <h2>Learning Materials</h2>
                <a href="{{ route('teacher.material.create', $class->id) }}" class="btn btn-primary">+ Add Material</a>
            </div>

            @if($class->materials->count() > 0)
            <div class="materials-list">
                @foreach($class->materials as $material)
                <div class="material-item">
                    <div class="material-icon">
                        @if($material->category === 'scratch')
                            🎮
                        @else
                            🤖
                        @endif
                    </div>
                    <div class="material-info">
                        <h3>{{ $material->title }}</h3>
                        <p>{{ Str::limit($material->description, 100) }}</p>
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
                    <div class="material-actions">
                        <a href="{{ route('teacher.material.edit', [$class->id, $material->id]) }}" class="btn btn-outline">Edit</a>
                        <form action="{{ route('teacher.material.destroy', [$class->id, $material->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this material?')">
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
                <h3>No Materials Yet</h3>
                <p>Add your first learning material to this class!</p>
                <a href="{{ route('teacher.material.create', $class->id) }}" class="btn btn-primary">Add First Material</a>
            </div>
            @endif

            <!-- Students Section -->
            <div class="section-header">
                <h2>Enrolled Students</h2>
            </div>

            @if($class->students->count() > 0)
            <div class="students-grid">
                @foreach($class->students as $student)
                <div class="student-card">
                    <div class="student-avatar">{{ substr($student->name, 0, 1) }}</div>
                    <div class="student-info">
                        <h4>{{ $student->name }}</h4>
                        <p>{{ $student->email }}</p>
                        @if($student->pivot && $student->pivot->joined_at)
                        <small>Joined: {{ date('M d, Y', strtotime($student->pivot->joined_at)) }}</small>
                        @else
                        <small>Recently joined</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">👥</div>
                <h3>No Students Yet</h3>
                <p>Share the class code <strong>{{ $class->class_code }}</strong> with your students so they can join!</p>
            </div>
            @endif
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2024 E-Learning Platform - Teacher Portal</p>
    </footer>

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