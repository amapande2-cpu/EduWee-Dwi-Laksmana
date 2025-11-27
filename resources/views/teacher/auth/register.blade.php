<!-- resources/views/teacher/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration - E-Learning Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left" style="background: linear-gradient(135deg, #764ba2, #667eea);">
            <div class="auth-icon">🎓</div>
            <h1>Join as a Teacher!</h1>
            <p>Create your account and start teaching. Build classes, share materials, and inspire students.</p>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h2>Teacher Registration</h2>
                <p>Fill in your details to get started</p>
            </div>

            <form action="{{ route('teacher.register.post') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        placeholder="John Doe"
                    >
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

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
                    <label for="phone">Phone Number (Optional)</label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}" 
                        placeholder="+1234567890"
                    >
                    @error('phone')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subject">Subject/Specialization (Optional)</label>
                    <input 
                        type="text" 
                        id="subject" 
                        name="subject" 
                        value="{{ old('subject') }}" 
                        placeholder="e.g., Computer Science, STEM"
                    >
                    @error('subject')
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
                        placeholder="Minimum 8 characters"
                    >
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        placeholder="Re-enter your password"
                    >
                </div>

                <button type="submit" class="btn btn-primary">Create Teacher Account</button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('teacher.login') }}">Sign in here</a></p>
                <p><a href="{{ route('welcome') }}">← Back to home</a></p>
            </div>
        </div>
    </div>
</body>
</html>