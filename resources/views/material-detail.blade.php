<!-- resources/views/material-detail.blade.php -->
@extends('layouts.app')

@section('title', $material->title . ' - E-Learning Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/material-detail.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="material-detail">
        <div class="breadcrumb">
            <a href="{{ route('student.home') }}">Home</a> / 
            <a href="{{ route('student.materials.category', $material->category) }}">{{ ucfirst(str_replace('-', ' ', $material->category)) }}</a> / 
            {{ $material->title }}
        </div>

        <div class="material-header">
            <div class="material-meta-top">
                <span class="category-badge">
                    @if($material->category === 'scratch')
                        🎮 Scratch
                    @else
                        🤖 Picto AI
                    @endif
                </span>
                <span class="difficulty difficulty-{{ $material->difficulty }}">
                    {{ ucfirst($material->difficulty) }}
                </span>
                @if($material->duration)
                <span class="duration-badge">⏱️ {{ $material->duration }} minutes</span>
                @endif
            </div>

            <h1 class="material-title">{{ $material->title }}</h1>
            <p class="material-description">{{ $material->description }}</p>
        </div>

        <div class="video-container">
            <h2>📺 Tutorial Video</h2>
            @if($material->video_url)
                <div class="video-wrapper">
                    @php
                        $videoUrl = $material->video_url;
                        // Convert YouTube watch URL to embed URL
                        if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                            $videoId = explode('v=', $videoUrl)[1];
                            $ampersandPosition = strpos($videoId, '&');
                            if ($ampersandPosition !== false) {
                                $videoId = substr($videoId, 0, $ampersandPosition);
                            }
                            $videoUrl = 'https://www.youtube.com/embed/' . $videoId;
                        } elseif (strpos($videoUrl, 'youtu.be/') !== false) {
                            $videoId = explode('youtu.be/', $videoUrl)[1];
                            $videoUrl = 'https://www.youtube.com/embed/' . $videoId;
                        }
                    @endphp
                    <iframe 
                        src="{{ $videoUrl }}" 
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    ></iframe>
                </div>
            @else
                <div class="no-video">
                    <div class="no-video-icon">🎬</div>
                    <p>Video will be available soon</p>
                </div>
            @endif
        </div>

        @if($relatedMaterials->count() > 0)
        <div class="related-materials">
            <h2>📚 Related Materials</h2>
            <div class="related-grid">
                @foreach($relatedMaterials as $related)
                <a href="{{ route('student.materials.show', $related->id) }}" class="related-card">
                    <h3 class="related-card-title">{{ $related->title }}</h3>
                    <p class="related-card-desc">{{ $related->description }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection