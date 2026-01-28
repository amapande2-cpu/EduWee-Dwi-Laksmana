@extends('layouts.app')

@section('title', $class->name . ' - Class')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/student-classes.css') }}">
@endsection

@section('content')
<div class="container">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="breadcrumb-nav">
        <a href="{{ route('student.home') }}">Home</a> /
        <a href="{{ route('student.classes.index') }}">My Classes</a> /
        <span>{{ $class->name }}</span>
    </div>

    {{-- ================= CLASS HEADER ================= --}}
    <div class="class-detail-banner">
        <div class="banner-content">
            <h1>{{ $class->name }}</h1>

            @if(!empty($class->description))
                <p class="class-desc">{{ $class->description }}</p>
            @endif

            <div class="class-meta">
                <span class="meta-item">
                    👨‍🏫 {{ optional($class->teacher)->name ?? 'Unknown Teacher' }}
                </span>
                <span class="meta-item">📋 {{ $class->class_code }}</span>
                <span class="meta-item">
                    📖 {{ $class->materials->count() }} Materials
                </span>
            </div>
        </div>
    </div>

    {{-- ================= MATERIALS ================= --}}
    @if($class->materials->isEmpty())
        <div class="empty-state">
            <p>No materials have been published yet.</p>
        </div>
    @else
        @php
            $materialsByCategory = $class->materials->groupBy('category');
        @endphp

        @foreach($materialsByCategory as $category => $materials)
            <div class="section">
                <div class="section-header">
                    <h2>
                        @switch($category)
                            @case('coding') 💻 Coding Materials @break
                            @case('ai') 🤖 AI Materials @break
                            @case('robotics') 🦾 Robotics Materials @break
                            @default 📚 {{ ucfirst($category) }} Materials
                        @endswitch
                    </h2>
                </div>

                <div class="materials-grid">
                    @foreach($materials as $material)
                        <a
                            href="{{ route('student.materials.show', $material->id) }}"
                            class="material-card"
                        >
                            <div class="material-thumbnail">
                                @if(!empty($material->cover_image))
                                    <img
                                        src="{{ asset('storage/' . $material->cover_image) }}"
                                        alt="{{ $material->title }}"
                                    >
                                @else
                                    <span class="material-icon">📘</span>
                                @endif
                            </div>

                            <div class="material-content">
                                <h3 class="material-title">
                                    {{ $material->title }}
                                </h3>

                                <p class="material-description">
                                    {{ Str::limit($material->description, 90) }}
                                </p>

                                <div class="material-meta">
                                    <span class="badge badge-difficulty badge-{{ $material->difficulty }}">
                                        {{ ucfirst($material->difficulty) }}
                                    </span>

                                    @if($material->duration)
                                        <span class="badge badge-duration">
                                            ⏱ {{ $material->duration }} min
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif

</div>
@endsection
