<!-- resources/views/teacher/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login - E-Learning Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left" style="background: linear-gradient(135deg, #667eea, #764ba2);">
            <img src="{{ asset('images/logo_eduwee.png') }}" alt="its should be logo" class="logo-img">
            <h1>Welcome Back, Teacher!</h1>
            <p>Login to manage your classes and create amazing learning materials for your students.</p>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h2>Teacher Sign In</h2>
                <p>Enter your credentials to access your dashboard</p>
            </div>

            <form action="{{ route('teacher.login.post') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        placeholder="teacher@example.com"
                    >
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="Enter your password"
                    >
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="{{ route('teacher.register') }}">Sign up here</a></p>
                <p><a href="{{ route('welcome') }}">← Back to home</a></p>
            </div>
        </div>
    </div>
</body>
</html>