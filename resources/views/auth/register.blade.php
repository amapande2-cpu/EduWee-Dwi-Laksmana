<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Learning Platform</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <img src="{{ asset('images/logo_eduwee.png') }}" alt="its should be logo" class="logo-img">
            <h1>Start Learning!</h1>
            <p>Create an account and unlock access to our comprehensive Scratch and Picto AI learning materials.</p>
        </div>

        <div class="auth-right">
            <div class="auth-header">
                <h2>Create Account</h2>
                <p>Fill in your details to get started</p>
            </div>

            <form action="{{ route('student.register.post') }}" method="POST" enctype="multipart/form-data">
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
                        placeholder="your.email@example.com"
                    >
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="profile_photo">Profile Photo (Optional)</label>
                    <input
                        type="file"
                        id="profile_photo"
                        name="profile_photo"
                        accept="image/png,image/jpeg,image/webp"
                    >
                    @error('profile_photo')
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

                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="{{ route('student.login') }}">Sign in here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
