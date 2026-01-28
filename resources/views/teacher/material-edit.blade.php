<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Material — {{ $material->title }}</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>

<body>

{{-- ================= NAVBAR ================= --}}
<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('teacher.dashboard') }}" class="logo">
            <img src="{{ asset('images/logo_eduwee.png') }}" class="logo-img">
            <span>Teacher Dashboard</span>
        </a>

            <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>

        <div class="nav-links" id="navLinks">
            <a href="{{ route('teacher.dashboard') }}">Dashboard</a>
            <a href="{{ route('teacher.classes.index') }}">Classes</a>
            <div class="user-info">
                <a class="user-name profile-link" href="{{ route('teacher.profile.show') }}">
                    @if(Auth::guard('teacher')->user()->hasProfilePhoto())
                        <img class="nav-avatar" src="{{ Auth::guard('teacher')->user()->profile_photo_data_uri }}" alt="Profile photo">
                    @else
                        <span class="nav-avatar nav-avatar--fallback">{{ strtoupper(substr(Auth::guard('teacher')->user()->name, 0, 1)) }}</span>
                    @endif
                    <span class="profile-link__text">
                        <span class="profile-link__name">{{ Auth::guard('teacher')->user()->name }}</span>
                        <span class="profile-link__meta">Teacher</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<main class="container">
<div class="form-container">

<h1>Edit Material</h1>
<p><strong>{{ $class->name }}</strong> ({{ $class->class_code }})</p>

<form method="POST"
      action="{{ route('teacher.materials.update', [$class->id, $material->id]) }}"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- ================= BASIC INFO ================= --}}
    <div class="form-group">
        <label>Title *</label>
        <input type="text" name="title"
               value="{{ old('title', $material->title) }}" required>
    </div>

    <div class="form-group">
        <label>Category *</label>
        <select name="category" required>
            @foreach(['coding','ai','robotics'] as $cat)
                <option value="{{ $cat }}"
                    {{ old('category', $material->category) === $cat ? 'selected' : '' }}>
                    {{ ucfirst($cat) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Difficulty *</label>
        <div class="radio-group vertical">
            @foreach(['beginner','intermediate','advanced'] as $level)
                <label class="radio-card">
                    <input
                        type="radio"
                        name="difficulty"
                        value="{{ $level }}"
                        {{ old('difficulty', $material->difficulty) === $level ? 'checked' : '' }}
                        required
                    >
                    <span>{{ ucfirst($level) }}</span>
                </label>
            @endforeach
        </div>
        @error('difficulty') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label>Duration (minutes)</label>
        <input type="number" name="duration" min="1"
               value="{{ old('duration', $material->duration) }}">
    </div>

    <div class="form-group">
        <label>Description *</label>
        <textarea name="description" rows="4" required>{{ old('description', $material->description) }}</textarea>
    </div>

    <div class="form-group">
        <label>Cover Image (optional)</label>
        <input type="file" name="cover_image" accept="image/*">
        @if($material->cover_image)
            <small>Current: <a href="{{ asset('storage/' . $material->cover_image) }}" target="_blank">View Cover Image</a></small>
        @endif
    </div>

    <hr>

    {{-- ================= VISIBILITY ================= --}}
    <h2>Visibility</h2>

    <div class="material-visibility">
        <span class="visibility-label">Publish Material</span>

        <input type="hidden" name="is_published" value="0">

        <label class="switch">
            <input
                type="checkbox"
                name="is_published"
                value="1"
                {{ old('is_published', $material->is_published) ? 'checked' : '' }}
            >
            <span class="slider"></span>
        </label>
    </div>

    <div class="material-visibility">
        <span class="visibility-label">Make Public</span>
        <p style="font-size: 0.9rem; color: #666; margin: 0.5rem 0;">Allow anyone to view this material without joining a class</p>

        <input type="hidden" name="is_public" value="0">

        <label class="switch">
            <input
                type="checkbox"
                name="is_public"
                value="1"
                {{ old('is_public', $material->is_public) ? 'checked' : '' }}
            >
            <span class="slider"></span>
        </label>
    </div>

    <hr>

    {{-- ================= STEPS ================= --}}
    <h2>Material Steps</h2>

    <div id="steps-container">
        @forelse($material->steps as $i => $step)
            <div class="step-card">
                <div class="step-header">
                    <strong class="step-title">Step {{ $i + 1 }}</strong>
                    <button type="button" class="btn-icon" onclick="removeStep(this)">🗑</button>
                </div>

                <div class="form-group">
                    <label>Step Title *</label>
                    <input type="text"
                           name="steps[{{ $i }}][title]"
                           value="{{ $step->title }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Step Content *</label>
                    <textarea name="steps[{{ $i }}][description]" rows="4" required>{{ $step->description }}</textarea>
                </div>

                <div class="form-group">
                    <label>Step Image (optional)</label>
                    <input type="file" name="steps[{{ $i }}][image]" accept="image/*">
                    @if($step->image)
                        <small>Current: <a href="{{ asset('storage/' . $step->image) }}" target="_blank">View Image</a></small>
                    @endif
                </div>

                <div class="form-group">
                    <label>Step Video URL (optional)</label>
                    <input type="url"
                           name="steps[{{ $i }}][video_url]"
                           value="{{ $step->video_url }}"
                           placeholder="https://...">
                </div>
            </div>
        @empty
            <div class="step-card">
                <div class="step-header">
                    <strong class="step-title">Step 1</strong>
                    <button type="button" class="btn-icon" onclick="removeStep(this)">🗑</button>
                </div>

                <div class="form-group">
                    <label>Step Title *</label>
                    <input type="text" name="steps[0][title]" required>
                </div>

                <div class="form-group">
                    <label>Step Content *</label>
                    <textarea name="steps[0][description]" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label>Step Image (optional)</label>
                    <input type="file" name="steps[0][image]" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Step Video URL (optional)</label>
                    <input type="url" name="steps[0][video_url]" placeholder="https://...">
                </div>
            </div>
        @endforelse
    </div>

    <button type="button" class="btn btn-outline" onclick="addStep()">➕ Add Step</button>

    <hr>

    {{-- ================= QUIZ ================= --}}
    <h2>Quiz (Optional)</h2>

    <div class="form-group">
        <label>Quiz Link</label>
        <input type="url" name="quiz_url" value="{{ old('quiz_url', $material->quiz_url) }}" placeholder="https://...">
        <p class="help-text">Optional link to an external quiz or assessment</p>
        @error('quiz_url') <div class="error">{{ $message }}</div> @enderror
    </div>

    <hr>

    <div class="form-actions">
        <button class="btn btn-primary">Update Material</button>
        <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

</div>
</main>

{{-- ================= JS ================= --}}
<script>
function addStep() {
    const container = document.getElementById('steps-container');
    const index = container.children.length;

    container.insertAdjacentHTML('beforeend', `
        <div class="step-card">
            <div class="step-header">
                <strong class="step-title">Step ${index + 1}</strong>
                <button type="button" class="btn-icon" onclick="removeStep(this)">🗑</button>
            </div>

            <div class="form-group">
                <label>Step Title *</label>
                <input type="text" name="steps[${index}][title]" required>
            </div>

            <div class="form-group">
                <label>Step Content *</label>
                <textarea name="steps[${index}][description]" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>Step Image (optional)</label>
                <input type="file" name="steps[${index}][image]" accept="image/*">
            </div>

            <div class="form-group">
                <label>Step Video URL (optional)</label>
                <input type="url" name="steps[${index}][video_url]" placeholder="https://...">
            </div>
        </div>
    `);
}

function removeStep(btn) {
    btn.closest('.step-card').remove();

    document.querySelectorAll('.step-card').forEach((card, i) => {
        card.querySelector('.step-title').textContent = `Step ${i + 1}`;
        card.querySelectorAll('input, textarea').forEach(input => {
            input.name = input.name.replace(/steps\[\d+]/, `steps[${i}]`);
        });
    });
}
</script>

</body>
</html>
