<!-- resources/views/student/material-detail.blade.php -->

@extends('layouts.app')

@section('title', $material->title . ' - Material')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/material-detail.css') }}">
@endsection

@section('content')
<div class="container material-detail">

    <!-- Back -->
    <a href="{{ url()->previous() }}" class="back-link">← Back</a>

    <!-- Header -->
    <div class="material-header">
        <h1>{{ $material->title }}</h1>

        <div class="material-meta">
            <span class="badge badge-category">
                {{ strtoupper($material->category) }}
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

    <!-- Description -->
    <div class="material-description">
        <h2>About this material</h2>
        <p>{{ $material->description }}</p>
    </div>

    <!-- ===== PROGRESS BAR (NEW) ===== -->
    @php
        $completedSteps = $material->steps->filter(fn($step) => $step->progress?->is_completed)->count();
        $totalSteps = $material->steps->count();
        $progressPercent = $material->progress->progress_percent ?? 0;
    @endphp
    <div class="material-progress {{ $progressPercent == 100 ? 'completed' : '' }}">
        <div class="progress-header">
            <div class="progress-icon">
                @if($progressPercent == 100)
                    <span class="completion-icon">🏆</span>
                @else
                    <span class="progress-icon">📚</span>
                @endif
            </div>
            <div class="progress-info">
                <div class="progress-label">
                    @if($progressPercent == 100)
                        <strong>Completed!</strong>
                    @else
                        Progress: <strong><span id="progress-percent">{{ $progressPercent }}</span>%</strong>
                    @endif
                </div>
                <div class="progress-steps">
                    {{ $completedSteps }} of {{ $totalSteps }} steps completed
                </div>
            </div>
        </div>

        <div class="progress-bar">
            <div
                id="progress-fill"
                class="progress-fill"
                style="width: {{ $progressPercent }}%;"
            ></div>
        </div>

        @if($progressPercent == 100)
            <div class="completion-message">
                🎉 Congratulations! You've completed this material.
            </div>
        @endif
    </div>

    <!-- ===== STEPS ===== -->
    <div class="material-steps">
        <h2>Learning Steps</h2>

        @forelse($material->steps as $index => $step)
            @php
                $completed = $step->progress?->is_completed ?? false;
            @endphp

            <div class="step-card {{ $completed ? 'completed' : '' }}">
                <div class="step-header">

                    <!-- ✅ CHECKBOX & STEP NUMBER GROUP -->
                    <div class="step-check-group">
                        <label class="step-check">
                            <input
                                type="checkbox"
                                data-step-id="{{ $step->id }}"
                                {{ $completed ? 'checked' : '' }}
                            >
                            <span class="checkmark"></span>
                        </label>
                        <span class="step-number">{{ $index + 1 }}</span>
                    </div>

                    <div class="step-title">
                        <h3>{{ $step->title }}</h3>
                    </div>
                </div>

                @if($step->description)
                    <p class="step-description">
                        {{ $step->description }}
                    </p>
                @endif

                @if($step->image)
                    <div class="step-media">
                        <div class="step-image">
                            <img
                                src="{{ asset('storage/' . $step->image) }}"
                                alt="Step image"
                                loading="lazy"
                            >
                        </div>
                    </div>
                @endif

                @if($step->video_url)
                    <div class="step-media">
                        @php
                            $videoId = null;
                            $embedUrl = $step->video_url;

                            // Extract YouTube video ID - improved regex for multiple formats
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $step->video_url, $matches)) {
                                $videoId = $matches[1] ?? $matches[2] ?? null;
                                if ($videoId) {
                                    $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
                                }
                            }
                        @endphp

                        @if($videoId)
                            <!-- YouTube Preview with Thumbnail -->
                            <div class="video-container step-video-container" data-video-id="{{ $videoId }}">
                                <div class="video-preview" onclick="playYouTubeVideo(this)">
                                    <img
                                        src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg"
                                        alt="Video thumbnail"
                                        loading="lazy"
                                        onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg'"
                                        onerror="this.onerror=null; this.src='https://img.youtube.com/vi/{{ $videoId }}/default.jpg'"
                                    >
                                    <div class="play-button">
                                        <div class="play-icon">▶</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Direct embed for non-YouTube videos -->
                            <iframe
                                src="{{ $embedUrl }}"
                                allowfullscreen
                                loading="lazy"
                                title="Step video"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <p class="empty-text">No steps added yet.</p>
        @endforelse
    </div>

    <!-- ===== QUIZ ===== -->
    @if($material->quiz_url)
    <div class="material-quiz">
        <h2>Quiz</h2>
        <p>Test your knowledge with this quiz!</p>
        <a href="{{ $material->quiz_url }}" target="_blank" class="btn btn-primary">
            Take Quiz 📝
        </a>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
function playYouTubeVideo(element) {
    const container = element.parentElement;
    const videoId = container.dataset.videoId;

    if (!videoId) {
        console.error('Video ID not found');
        return;
    }

    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;

    // Replace preview with iframe
    container.innerHTML = `
        <iframe
            src="${embedUrl}"
            allowfullscreen
            loading="lazy"
            title="Step video"
            class="youtube-iframe"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        ></iframe>
    `;
}

document.querySelectorAll('[data-step-id]').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const stepId = this.dataset.stepId;

        fetch(`/student/materials/steps/${stepId}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const progressPercent = data.progress;
                document.getElementById('progress-percent').innerText = progressPercent;
                document.getElementById('progress-fill').style.width = progressPercent + '%';

                // Update completion state
                const progressContainer = document.querySelector('.material-progress');
                if (progressPercent == 100) {
                    progressContainer.classList.add('completed');
                } else {
                    progressContainer.classList.remove('completed');
                }

                // Update step counts if available
                if (data.completed_steps !== undefined && data.total_steps !== undefined) {
                    const stepsText = `${data.completed_steps} of ${data.total_steps} steps completed`;
                    document.querySelector('.progress-steps').textContent = stepsText;
                }
            }
        });
    });
});
</script>
@endsection
