<!-- resources/views/teacher/material-create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Material - {{ $class->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('teacher.dashboard') }}" class="logo">
                <div class="logo-icon">EL</div>
                <span>Teacher Dashboard</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('teacher.class.show', $class->id) }}">← Back to Class</a>
                <div class="user-info">
                    <span class="user-name">👨‍🏫 {{ Auth::guard('teacher')->user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <div class="container">
            <div class="form-container">
                <div class="form-header">
                    <h1>Add New Material</h1>
                    <p>Class: <strong>{{ $class->name }}</strong> ({{ $class->class_code }})</p>
                </div>

                <form action="{{ route('teacher.material.store', $class->id) }}" method="POST" class="material-form">
                    @csrf

                    <div class="form-group">
                        <label for="title">Material Title *</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            value="{{ old('title') }}"
                            required 
                            placeholder="e.g., Introduction to Scratch Programming"
                        >
                        @error('title')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="scratch" {{ old('category') == 'scratch' ? 'selected' : '' }}>🎮 Scratch</option>
                                <option value="picto-ai" {{ old('category') == 'picto-ai' ? 'selected' : '' }}>🤖 Picto AI</option>
                            </select>
                            @error('category')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="difficulty">Difficulty Level *</label>
                            <select id="difficulty" name="difficulty" required>
                                <option value="">Select Difficulty</option>
                                <option value="beginner" {{ old('difficulty') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                            @error('difficulty')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="duration">Duration (minutes)</label>
                            <input 
                                type="number" 
                                id="duration" 
                                name="duration" 
                                value="{{ old('duration') }}"
                                min="1" 
                                placeholder="e.g., 30"
                            >
                            @error('duration')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="5" 
                            required 
                            placeholder="Describe what students will learn from this material..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="video_url">Video URL (YouTube) *</label>
                        <input 
                            type="url" 
                            id="video_url" 
                            name="video_url" 
                            value="{{ old('video_url') }}"
                            required 
                            placeholder="https://www.youtube.com/watch?v=..."
                        >
                        <small class="help-text">Paste the full YouTube video URL. Supported formats: youtube.com/watch?v=, youtu.be/</small>
                        @error('video_url')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Add Material</button>
                        <a href="{{ route('teacher.class.show', $class->id) }}" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2024 E-Learning Platform - Teacher Portal</p>
    </footer>
</body>
</html>