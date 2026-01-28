@extends('layouts.app')

@section('title', 'Edit Profile - EduWee')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container profile-page">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-header profile-header">
        <h2>Edit Profile</h2>
    </div>

    <div class="create-form profile-form">
        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
                    @error('name') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="profile_photo">Profile Photo (optional)</label>
                    <input id="profile_photo" name="profile_photo" type="file" accept="image/png,image/jpeg,image/webp">
                    @error('profile_photo') <span class="error">{{ $message }}</span> @enderror

                    <div class="profile-photo-row">
                        @if($user->hasProfilePhoto())
                            <img class="avatar-img" src="{{ $user->profile_photo_data_uri }}" alt="Profile photo" style="width:56px;height:56px;">
                            <label class="profile-remove-photo">
                                <input type="checkbox" name="remove_photo" value="1"> Remove current photo
                            </label>
                        @else
                            <div class="avatar-fallback" style="width:56px;height:56px;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <hr style="border:0;border-top:1px solid rgba(0,0,0,0.08); margin: 1.25rem 0;" />

            <div class="form-row">
                <div class="form-group">
                    <label for="current_password">Confirm with Password</label>
                    <input id="current_password" name="current_password" type="password" required placeholder="Enter your current password">
                    @error('current_password') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('student.profile.show') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

