<!-- resources/views/student/class-detail.blade.php -->
@extends('layouts.app')

@section('title', $class->name . ' - E-Learning Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/student-classes.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="breadcrumb-nav">
        <a href="{{ route('student.home') }}">Home</a> / 
        <a href="{{ route('student.classes') }}">My Classes</a> / 
        {{ $class->name }}
    </div>

    <div class="class-detail-banner">
        <div class="banner-content">
            <h1>{{ $class->name }}</h1>
            @if($class->description)
            <p class="class-desc">{{ $class->description }}</p>
            @endif
            <div class="class-meta">
                <span class="meta-item">👨‍🏫 {{ $class->teacher->name }}</span>
                <span class="meta-item">📋 {{ $class->class_code }}</span>
                <span class="meta-item">📖 {{ $scratchMaterials->count() + $pictoMaterials->count() }} Materials</span>
            </div>
        </div>
    </div>

    <!-- Scratch Materials Section -->
    <div class="section">
        <div class="section-header">
            <h2>🎮 Scratch Materials</h2>
        </div>

        @if($scratchMaterials->count() > 0)
        <div class="materials-grid">
            @foreach($scratchMaterials as $material)
            <a href="{{ route('student.materials.show', $material->id) }}" class="material-card">
                <div class="material-thumbnail">
                    @if($material->thumbnail)
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
                        @if($material->duration)
                        <span class="duration">⏱️ {{ $material->duration }} min</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state-small">
            <p>No Scratch materials in this class yet.</p>
        </div>
        @endif
    </div>

    <!-- Picto AI Materials Section -->
    <div class="section">
        <div class="section-header">
            <h2>🤖 Picto AI Materials</h2>
        </div>

        @if($pictoMaterials->count() > 0)
        <div class="materials-grid">
            @foreach($pictoMaterials as $material)
            <a href="{{ route('materials.show', $material->id) }}" class="material-card">
                <div class="material-thumbnail">
                    @if($material->thumbnail)
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
                        @if($material->duration)
                        <span class="duration">⏱️ {{ $material->duration }} min</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state-small">
            <p>No Picto AI materials in this class yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection