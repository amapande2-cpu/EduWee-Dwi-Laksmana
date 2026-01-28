<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home - EduWee E-Learning Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="hero fade-in">
        <h1>🎓 Welcome to EduWee E-Learning Platform</h1>
        <p>Embark on an interactive learning journey! Explore coding, AI, and robotics through hands-on projects and engaging challenges</p>
    </div>

    <!-- Join Class Section -->
    <div class="join-class-section">
        <div class="join-class-card">
            <h2>📚 Bergabung ke Kelas</h2>
            <p>Enter the class code provided by your teacher to access learning materials</p>
            <form action="{{ route('student.classes.join') }}" method="POST" class="join-class-form">
                @csrf
                <div class="join-form-group">
                    <input 
                        type="text" 
                        name="class_code" 
                        placeholder="Enter 6-character class code (e.g., ABC123)" 
                        maxlength="6"
                        required
                        style="text-transform: uppercase;"
                    >
                    <button type="submit" class="btn btn-primary">Join Class</button>
                </div>
                @error('class_code')
                    <div class="error">{{ $message }}</div>
                @enderror
            </form>
        </div>

        <!-- My Classes -->
        @if($classes instanceof \Illuminate\Support\Collection && $classes->isNotEmpty())
        <div class="my-classes">
            <h3>My Enrolled Classes</h3>
            <div class="classes-list">
                @foreach($classes as $class)
                <a href="{{ route('student.classes.show', $class->id) }}" class="enrolled-class-card">
                    <div class="class-info">
                        <h4>{{ $class->name }}</h4>
                        <p>Teacher: {{ $class->teacher->name ?? 'N/A' }}</p>
                        <span class="class-code-badge">{{ $class->class_code }}</span>
                    </div>
                    <div class="class-count">
                        <span>{{ $class->materials_count ?? 0 }} materials</span>
                        <span class="view-arrow">→</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Public Materials Section -->
    <div class="public-materials-section">
        <div class="section-header">
            <h2>Public Learning Materials</h2>
            <p>Explore free learning materials available to everyone</p>
        </div>

        @if(isset($publicMaterials) && $publicMaterials->count() > 0)
            <div class="materials-grid">
                @foreach($publicMaterials as $material)
                    <a href="{{ route('materials.public.show', $material->id) }}" class="material-card">
                        <!-- Thumbnail -->
                        <div class="material-thumbnail">
                            @if($material->cover_image)
                                <img src="{{ asset('storage/' . $material->cover_image) }}" alt="{{ $material->title }}">
                            @else
                                <span class="material-icon">
                                    @if($material->category === 'coding')
                                        🎮
                                    @elseif($material->category === 'ai')
                                        🤖
                                    @elseif($material->category === 'robotics')
                                        🤖
                                    @else
                                        📚
                                    @endif
                                </span>
                            @endif

                            <span class="material-category">
                                @if($material->category === 'coding')
                                    💻 Coding
                                @elseif($material->category === 'ai')
                                    🤖 AI
                                @elseif($material->category === 'robotics')
                                    🦾 Robotics
                                @else
                                    {{ ucfirst($material->category) }}
                                @endif
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="material-content">
                            <h3 class="material-title">{{ $material->title }}</h3>

                            <p class="material-description">
                                {{ Str::limit($material->description, 90) }}
                            </p>

                            <div class="material-meta">
                                <span class="difficulty difficulty-{{ $material->difficulty }}">
                                    {{ ucfirst($material->difficulty) }}
                                </span>

                                @if($material->duration)
                                    <span class="duration">
                                        ⏱ {{ $material->duration }} min
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="public-materials-link" style="margin-top: 2rem;">
                <a href="{{ route('materials.public') }}" class="btn btn-primary">
                    View All Public Materials →
                </a>
            </div>
        @else
            <div class="public-materials-link">
                <a href="{{ route('materials.public') }}" class="btn btn-primary">
                    Browse Public Materials →
                </a>
            </div>
        @endif
    </div>

<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
</script>
@endsection