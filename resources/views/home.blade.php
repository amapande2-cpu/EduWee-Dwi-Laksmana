<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home - E-Learning Platform')

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
        <h1>🎓 Welcome to E-Learning Platform</h1>
        <p>Master Scratch programming and Picto AI with our comprehensive learning materials</p>
    </div>

    <!-- Join Class Section -->
    <div class="join-class-section">
        <div class="join-class-card">
            <h2>📚 Join a Class</h2>
            <p>Enter the class code provided by your teacher to access learning materials</p>
            <form action="{{ route('student.joinClass') }}" method="POST" class="join-class-form">
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
        @if(isset($classes) && $classes->count() > 0)
        <div class="my-classes">
            <h3>My Enrolled Classes</h3>
            <div class="classes-list">
                @foreach($classes as $class)
                <a href="{{ route('student.class.show', $class->id) }}" class="enrolled-class-card">
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

    <!-- Scratch Materials Section -->
    <div class="section">
        <div class="section-header">
            <h2>🎮 Scratch Materials</h2>
            <a href="{{ route('student.materials.category', 'scratch') }}" class="view-all">View All →</a>
        </div>

        @if(isset($scratchMaterials) && $scratchMaterials->count() > 0)
        <div class="materials-grid">
            @foreach($scratchMaterials as $material)
            <a href="{{ route('student.materials.show', $material->id) }}" class="material-card">
                <div class="material-thumbnail">
                    @if(isset($material->thumbnail) && $material->thumbnail)
                        <img src="{{ asset('storage/' . $material->thumbnail) }}" alt="{{ $material->title }}">
                    @else
                        🎮
                    @endif
                    <span class="material-category">Scratch</span>
                </div>
                <div class="material-content">
                    <h3 class="material-title">{{ $material->title }}</h3>
                    <p class="material-description">{{ $material->description }}</p>
                    <div class="material-meta">
                        <span class="difficulty difficulty-{{ $material->difficulty }}">
                            {{ ucfirst($material->difficulty) }}
                        </span>
                        @if(isset($material->duration) && $material->duration)
                        <span class="duration">⏱️ {{ $material->duration }} min</span>
                        @endif
                    </div>
                    @if(isset($material->class) && $material->class)
                    <div class="material-class">
                        <small>📚 {{ $material->class->name }}</small>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <p>No Scratch materials available yet. Join a class to see materials!</p>
        </div>
        @endif
    </div>

    <!-- Picto AI Materials Section -->
    <div class="section">
        <div class="section-header">
            <h2>🤖 Picto AI Materials</h2>
            <a href="{{ route('student.materials.category', 'picto-ai') }}" class="view-all">View All →</a>
        </div>

        @if(isset($pictoMaterials) && $pictoMaterials->count() > 0)
        <div class="materials-grid">
            @foreach($pictoMaterials as $material)
            <a href="{{ route('student.materials.show', $material->id) }}" class="material-card">
                <div class="material-thumbnail">
                    @if(isset($material->thumbnail) && $material->thumbnail)
                        <img src="{{ asset('storage/' . $material->thumbnail) }}" alt="{{ $material->title }}">
                    @else
                        🤖
                    @endif
                    <span class="material-category">Picto AI</span>
                </div>
                <div class="material-content">
                    <h3 class="material-title">{{ $material->title }}</h3>
                    <p class="material-description">{{ $material->description }}</p>
                    <div class="material-meta">
                        <span class="difficulty difficulty-{{ $material->difficulty }}">
                            {{ ucfirst($material->difficulty) }}
                        </span>
                        @if(isset($material->duration) && $material->duration)
                        <span class="duration">⏱️ {{ $material->duration }} min</span>
                        @endif
                    </div>
                    @if(isset($material->class) && $material->class)
                    <div class="material-class">
                        <small>📚 {{ $material->class->name }}</small>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <p>No Picto AI materials available yet. Join a class to see materials!</p>
        </div>
        @endif
    </div>
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