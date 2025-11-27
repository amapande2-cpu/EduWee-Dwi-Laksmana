<!-- resources/views/student/classes.blade.php -->
@extends('layouts.app')

@section('title', 'My Classes - E-Learning Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/student-classes.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <h1>📚 My Classes</h1>
        <p>Browse your enrolled classes and access learning materials</p>
    </div>

    @if($classes->count() > 0)
    <div class="classes-grid">
        @foreach($classes as $class)
        <a href="{{ route('student.class.show', $class->id) }}" class="class-card">
            <div class="class-card-header">
                <h3>{{ $class->name }}</h3>
                <span class="class-code">{{ $class->class_code }}</span>
            </div>
            
            @if($class->description)
            <p class="class-description">{{ $class->description }}</p>
            @endif

            <div class="class-teacher">
                <span class="teacher-icon">👨‍🏫</span>
                <span>{{ $class->teacher->name }}</span>
            </div>

            <div class="class-stats">
                <div class="stat-item">
                    <span class="stat-icon">📖</span>
                    <span class="stat-text">{{ $class->materials_count }} Materials</span>
                </div>
                <div class="stat-item">
                    <span class="stat-icon">📅</span>
                    <span class="stat-text">Joined {{ date('M d, Y', strtotime($class->pivot->joined_at)) }}</span>
                </div>
            </div>

            <div class="view-class-btn">
                View Class Materials →
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">🎓</div>
        <h3>No Classes Yet</h3>
        <p>You haven't joined any classes. Enter a class code from your teacher to get started!</p>
        <a href="{{ route('student.home') }}" class="btn btn-primary">Go to Home & Join Class</a>
    </div>
    @endif
</div>
@endsection