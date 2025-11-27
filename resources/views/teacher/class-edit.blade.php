<!-- resources/views/teacher/class-edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class - {{ $class->name }}</title>
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
                    <h1>Edit Class</h1>
                    <p>Update class information</p>
                </div>

                <form action="{{ route('teacher.class.update', $class->id) }}" method="POST" class="material-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Class Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $class->name) }}"
                            required 
                            placeholder="e.g., Scratch Programming 2024"
                        >
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="5" 
                            placeholder="Brief description of the class..."
                        >{{ old('description', $class->description) }}</textarea>
                        @error('description')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-info">
                        <p><strong>Class Code:</strong> {{ $class->class_code }}</p>
                        <p class="help-text">Class code cannot be changed</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Class</button>
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