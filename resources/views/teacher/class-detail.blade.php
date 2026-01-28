<!-- resources/views/teacher/class-detail.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $class->name }} - Teacher Dashboard</title>
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

            <!-- Class Header -->
            <div class="class-detail-header">
                <div>
                    <h1>{{ $class->name }}</h1>
                    @if($class->description)
                    <p class="class-description">{{ Str::limit($class->description, 400) }}</p>
                    @endif
                </div>
                <div class="header-actions">
                    <div class="class-code-large">
                        <span class="code-label">Class Code:</span>
                        <span class="code-value">{{ $class->class_code }}</span>
                    </div>
                    <a href="{{ route('teacher.classes.edit', $class->id) }}" class="btn btn-outline">✏️ Edit Class</a>
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
            </div>

            <!-- Materials Section -->
            <div class="section-header">
                <h2>Learning Materials</h2>
                <a href="{{ route('teacher.materials.create', $class->id) }}" class="btn btn-primary">+ Add Material</a>
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
                            <a href="{{ route('teacher.materials.progress', [$class->id, $material->id]) }}" class="badge badge-completed">
                                ✅ {{ $material->completed_count ?? 0 }} completed
                            </a>
                        </div>
                    </div>
                    <div class="material-actions">
                        <a href="{{ route('teacher.materials.edit', [$class->id, $material->id]) }}" class="btn btn-outline">Edit</a>
                        <form action="{{ route('teacher.materials.destroy', [$class->id, $material->id]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this material?')">
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
                <a href="{{ route('teacher.materials.create', $class->id) }}" class="btn btn-primary">Add First Material</a>
            </div>
            @endif

            <!-- Students Section -->
            <div class="section-header">
                <h2>Enrolled Students</h2>
            </div>

            @if($class->students->count() > 0)
            <div class="students-grid">
                @foreach($class->students as $student)
                <div class="student-card" onclick="openStudentModal('{{ $student->name }}', '{{ $student->email }}', '{{ $student->hasProfilePhoto() ? $student->profile_photo_data_uri : '' }}', '{{ $student->pivot && $student->pivot->joined_at ? date('M d, Y', strtotime($student->pivot->joined_at)) : 'Recently joined' }}', '{{ strtoupper(substr($student->name, 0, 1)) }}')">
                    @if($student->hasProfilePhoto())
                        <img class="student-avatar" src="{{ $student->profile_photo_data_uri }}" alt="Profile photo">
                    @else
                        <div class="student-avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                    @endif
                    <div class="student-info">
                        <h4>{{ $student->name }}</h4>
                        <p class="student-email">{{ $student->email }}</p>
                        <div class="student-details">
                            @if($student->pivot && $student->pivot->joined_at)
                            <small>
                                <span class="join-label">joined:</span><br>
                                {{ date('M d, Y', strtotime($student->pivot->joined_at)) }}
                            </small>
                            @else
                            <small>Recently joined</small>
                            @endif
                        </div>
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
        <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
    </footer>

    <!-- Student Detail Modal -->
    <div id="studentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeStudentModal()">&times;</span>
            <div class="student-detail-card">
                <div id="modalAvatar" class="student-detail-avatar"></div>
                <div class="student-detail-info">
                    <h2 id="modalName"></h2>
                    <p id="modalEmail" class="student-detail-email"></p>
                    <div class="student-detail-joined">
                        <span class="join-label">joined:</span><br>
                        <span id="modalJoinedDate"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        // Modal functions
        function openStudentModal(name, email, photoUri, joinedDate, initial) {
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalJoinedDate').textContent = joinedDate;

            const avatarDiv = document.getElementById('modalAvatar');
            if (photoUri) {
                avatarDiv.innerHTML = `<img src="${photoUri}" alt="Profile photo" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
            } else {
                avatarDiv.innerHTML = initial;
            }

            document.getElementById('studentModal').style.display = 'block';
        }

        function closeStudentModal() {
            document.getElementById('studentModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('studentModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Auto-hide alerts
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
