<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Material - {{ $class->name }}</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="icon" href="{{ asset('images/logo_eduwee.png') }}">
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

{{-- ================= CONTENT ================= --}}
<main>
<div class="container">
<div class="form-container">

    <div class="form-header">
        <h1>Add New Material</h1>
        <p>
            Class:
            <strong>{{ $class->name }}</strong>
            ({{ $class->class_code }})
        </p>
    </div>

    {{-- DEBUG: show validation errors --}}
    @if ($errors->any())
        <div style="background:#fee; padding:10px; margin-bottom:15px;">
            <strong>Validation Errors:</strong>
            <pre>{{ $errors }}</pre>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('teacher.materials.store', $class->id) }}"
        enctype="multipart/form-data"
        class="material-form"
    >
        @csrf

        {{-- ================= BASIC INFO ================= --}}
        <h2 class="section-title">Basic Information</h2>

        <div class="form-group">
            <label>Material Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required>
            @error('title') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Category *</label>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="coding" {{ old('category')==='coding' ? 'selected' : '' }}>💻 Coding</option>
                <option value="ai" {{ old('category')==='ai' ? 'selected' : '' }}>🤖 AI</option>
                <option value="robotics" {{ old('category')==='robotics' ? 'selected' : '' }}>🦾 Robotics</option>
            </select>
            @error('category') <div class="error">{{ $message }}</div> @enderror
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
                            {{ old('difficulty')===$level ? 'checked' : '' }}
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
            <input type="number" name="duration" min="1" value="{{ old('duration') }}">
            @error('duration') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Description *</label>
            <textarea name="description" rows="5" required>{{ old('description') }}</textarea>
            @error('description') <div class="error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Cover Image (optional)</label>
            <input type="file" name="cover_image" accept="image/*">
        </div>

        {{-- ================= VISIBILITY ================= --}}
        <hr>
        <h2 class="section-title">Visibility</h2>

        <div class="material-visibility">
            <span class="visibility-label">Publish Material</span>

            {{-- hidden input ensures false is sent --}}
            <input type="hidden" name="is_published" value="0">

            <label class="switch">
                <input
                    type="checkbox"
                    name="is_published"
                    value="1"
                    {{ old('is_published') ? 'checked' : '' }}
                >
                <span class="slider"></span>
            </label>
        </div>

        <div class="material-visibility">
            <div class="visibility-text-group">
                <span class="visibility-label">Make Public</span>
                <p class="visibility-description" style="font-size: 0.9rem; color: #666; margin: 0.25rem 0 0 0;">Allow anyone to view this material without joining a class</p>
            </div>

            {{-- hidden input ensures false is sent --}}
            <input type="hidden" name="is_public" value="0">

            <label class="switch">
                <input
                    type="checkbox"
                    name="is_public"
                    value="1"
                    {{ old('is_public') ? 'checked' : '' }}
                >
                <span class="slider"></span>
            </label>
        </div>

        {{-- ================= STEPS ================= --}}
        <hr>
        <h2 class="section-title">Material Steps</h2>

        <div id="steps-container">
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
        </div>

        <button type="button" class="btn btn-outline" onclick="addStep()" style="margin-bottom: 2rem;">➕ Add Step</button>

        {{-- ================= QUIZ ================= --}}
        <hr>
        <h2 class="section-title">Quiz (Optional)</h2>

        <div class="form-group">
            <label>Quiz Link</label>
            <input type="url" name="quiz_url" value="{{ old('quiz_url') }}" placeholder="https://...">
            <p class="help-text">Optional link to an external quiz or assessment</p>
            @error('quiz_url') <div class="error">{{ $message }}</div> @enderror
        </div>

        {{-- ================= ACTIONS ================= --}}
        <div class="form-actions">
            <button class="btn btn-primary">Add Material</button>
            <a href="{{ route('teacher.classes.show', $class->id) }}" class="btn btn-outline">Cancel</a>
        </div>

    </form>

</div>
</div>
</main>

<footer class="footer">
    <p>&copy; 2026 EduWee E-Learning Platform. All rights reserved.</p>
</footer>

<script src="{{ asset('js/footer-reveal.js') }}"></script>

{{-- ================= SCRIPT ================= --}}
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

function removeStep(button) {
    button.closest('.step-card').remove();

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
