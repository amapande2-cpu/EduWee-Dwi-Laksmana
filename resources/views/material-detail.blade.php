{{-- resources/views/student/material-show.blade.php --}}
@extends('layouts.app')

@section('title', $material->title)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/material-detail.css') }}">
@endsection

@section('scripts')
<script>
function playYouTubeVideo(element) {
    const videoId = element.parentElement.dataset.videoId;
    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1`;

    // Replace preview with iframe
    element.outerHTML = `
        <iframe
            src="${embedUrl}"
            allowfullscreen
            loading="lazy"
            title="Step video"
            class="youtube-iframe"
        ></iframe>
    `;
}
</script>
@endsection

@section('content')
<div class="container">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="breadcrumb-nav">
        @if(isset($is_public) && $is_public)
            <a href="{{ route('materials.public') }}">Public Materials</a> /
        @else
            <a href="{{ route('student.home') }}">Home</a> /
            <a href="{{ route('student.classes.show', $material->class_id) }}">
                {{ $material->class->name }}
            </a> /
        @endif
        <span>{{ $material->title }}</span>
    </div>

    {{-- ================= MATERIAL HEADER ================= --}}
    <div class="material-header">
        @if($material->cover_image)
            <div class="material-cover">
                <img src="{{ asset('storage/' . $material->cover_image) }}" alt="{{ $material->title }} cover" class="cover-image">
            </div>
        @endif

        <h1>{{ $material->title }}</h1>

        <p class="material-description">
            {{ $material->description }}
        </p>

        <div class="material-meta">
            <span class="badge badge-category">
                📚 {{ ucfirst($material->category) }}
            </span>

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

    {{-- ================= MATERIAL STEPS ================= --}}
    <div class="material-steps">

        <h2>📘 Learning Steps</h2>

        @forelse($material->steps as $index => $step)
            <div class="step-card">

                <div class="step-header">
                    <span class="step-number">
                        {{ $index + 1 }}
                    </span>
                    <h3 class="step-title">
                        📚 {{ $step->title }}
                    </h3>
                </div>

                <div class="step-content">
                    <p class="step-description">
                        {{ $step->description }}
                    </p>

                    {{-- Step Media --}}
                    @if($step->image || $step->video_url)
                        <div class="step-media">
                            {{-- Step Image --}}
                            @if($step->image)
                                <div class="step-image">
                                    <img
                                        src="{{ asset('storage/' . $step->image) }}"
                                        alt="Step image"
                                        loading="lazy"
                                    >
                                </div>
                            @endif

                            {{-- Step Video --}}
                            @if($step->video_url)
                                <div class="step-video">
                                    @php
                                        $videoId = null;
                                        $embedUrl = $step->video_url;
                                        // Extract YouTube video ID
                                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $step->video_url, $matches)) {
                                            $videoId = $matches[1];
                                            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                        }
                                    @endphp

                                    @if($videoId)
                                        {{-- YouTube Preview with Thumbnail --}}
                                        <div class="video-container" data-video-id="{{ $videoId }}">
                                            <div class="video-preview" onclick="playYouTubeVideo(this)">
                                                <img
                                                    src="https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg"
                                                    alt="Video thumbnail"
                                                    loading="lazy"
                                                    onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/default.jpg'"
                                                >
                                                <div class="play-button">
                                                    <div class="play-icon">▶</div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Direct embed for non-YouTube videos --}}
                                        <iframe
                                            src="{{ $embedUrl }}"
                                            allowfullscreen
                                            loading="lazy"
                                            title="Step video"
                                        ></iframe>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        @empty
            <div class="empty-state">
                <p>No steps available for this material yet.</p>
            </div>
        @endforelse

    </div>

    {{-- ================= QUIZ ================= --}}
    @if($material->quiz_url)
        <div class="material-quiz">
            <h2>📝 Quiz</h2>

            <p>
                Complete the quiz after finishing all steps.
            </p>

            <a
                href="{{ $material->quiz_url }}"
                target="_blank"
                class="btn btn-primary"
            >
                Open Quiz
            </a>
        </div>
    @endif

    {{-- ================= RELATED MATERIALS ================= --}}
    @if(isset($relatedMaterials) && $relatedMaterials->count() > 0)
        <div class="related-materials">
            <h2>📚 Related Materials</h2>

            <div class="related-grid">
                @foreach($relatedMaterials as $related)
                    <a href="{{ route('student.materials.show', $related->id) }}" class="related-card">
                        <h3 class="related-card-title">{{ $related->title }}</h3>
                        <p class="related-card-desc">{{ Str::limit($related->description, 100) }}</p>
                        <div class="related-meta">
                            <span class="badge badge-category">{{ ucfirst($related->category) }}</span>
                            <span class="badge badge-difficulty badge-{{ $related->difficulty }}">
                                {{ ucfirst($related->difficulty) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
