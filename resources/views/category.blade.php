<!-- resources/views/category.blade.php -->
@extends('layouts.app')

@section('title', ucfirst($category) . ' Materials - E-Learning Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="category-header">
        <div class="category-icon">
            @if($category === 'coding')
                🎮
            @elseif($category === 'ai')
                🤖
            @elseif($category === 'robotics')
                🤖
            @else
                🤖
            @endif
        </div>
        <h1>
            @if($category === 'coding')
                🎮 Coding
            @elseif($category === 'ai')
                🤖 AI & Robotics
            @elseif($category === 'robotics')
                🤖 Robotics
            @else
                {{ ucfirst(str_replace('-', ' ', $category)) }}
            @endif
            Materials
        </h1>
        <p>Explore all available learning materials</p>
    </div>

    @if($materials->count() > 0)
    <div class="materials-grid">
        @foreach($materials as $material)
        <a href="{{ route('student.materials.show', $material->id) }}" class="material-card">
            <div class="material-thumbnail">
                @if($material->cover_image)
                    <img src="{{ asset('storage/' . $material->cover_image) }}" alt="{{ $material->title }}">
                @else
                    @if($category === 'coding')
                        🎮
                    @elseif($category === 'ai')
                        🤖
                    @elseif($category === 'robotics')
                        🤖
                    @else
                        🤖
                    @endif
                @endif
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
    <div class="empty-state">
        <div class="empty-state-icon">📚</div>
        <h2>No Materials Yet</h2>
        <p>
            There are no
            @if($category === 'coding')
                Coding
            @elseif($category === 'ai')
                AI & Robotics
            @elseif($category === 'robotics')
                Robotics
            @else
                {{ ucfirst(str_replace('-', ' ', $category)) }}
            @endif
            materials available at the moment.
        </p>
        <p>Please check back later for new content!</p>
    </div>
    @endif
</div>
@endsection