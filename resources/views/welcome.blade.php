<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - E-Learning Platform</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-header">
            <div class="logo-large">
                <div class="logo-icon-large">EL</div>
                <h1>E-Learning Platform</h1>
            </div>
            <p class="subtitle">Master Scratch & Picto AI with interactive learning</p>
        </div>

        <div class="login-options">
            <div class="option-card">
                <div class="option-icon">👨‍🎓</div>
                <h2>I'm a Student</h2>
                <p>Join classes and access learning materials</p>
                <div class="option-buttons">
                    <a href="{{ route('student.login') }}" class="btn btn-primary">Login</a>
                    <a href="{{ route('student.register') }}" class="btn btn-outline">Register</a>
                </div>
            </div>

            <div class="divider">
                <span>OR</span>
            </div>

            <div class="option-card">
                <div class="option-icon">👨‍🏫</div>
                <h2>I'm a Teacher</h2>
                <p>Create classes and manage learning materials</p>
                <div class="option-buttons">
                    <a href="{{ route('teacher.login') }}" class="btn btn-primary">Login</a>
                    <a href="{{ route('teacher.register') }}" class="btn btn-outline">Register</a>
                </div>
            </div>
        </div>

        <div class="features">
            <div class="feature">
                <div class="feature-icon">🎮</div>
                <h3>Scratch Programming</h3>
                <p>Learn block-based coding</p>
            </div>
            <div class="feature">
                <div class="feature-icon">🤖</div>
                <h3>Picto AI</h3>
                <p>Explore AI concepts</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📚</div>
                <h3>Interactive Lessons</h3>
                <p>Video tutorials & exercises</p>
            </div>
        </div>
    </div>
</body>
</html>