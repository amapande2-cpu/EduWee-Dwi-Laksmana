@extends('layouts.app')

@section('title', 'Public Materials - EduWee')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/materials.css') }}">
@endsection

@section('content')
<div class="container">

    <!-- Page Header -->
    <div class="page-header">
        <h1>📚 Public Learning Materials</h1>
        <p>Explore free learning materials you can access without joining a class.</p>
    </div>

    @if($materials->count() > 0)
        <div class="materials-grid">
            @foreach($materials as $material)
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
    @else
        <div class="empty-state">
            <p>No public materials available yet.</p>
        </div>
    @endif

</div>
@endsection
