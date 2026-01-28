@extends('layouts.app')

@section('title', 'My Profile - EduWee')

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
        <h2>My Profile</h2>
    </div>

    <div class="create-form profile-form profile-card">
        <div class="profile-card__top">
            @if($user->hasProfilePhoto())
                <img class="profile-card__avatar avatar-img" src="{{ $user->profile_photo_data_uri }}" alt="Profile photo">
            @else
                <div class="profile-card__avatar avatar-fallback">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            @endif

            <div class="profile-card__info">
                <div class="profile-card__name">{{ $user->name }}</div>
                <div class="profile-card__meta">Student</div>
                <div class="profile-card__email">{{ $user->email }}</div>
            </div>
        </div>

        <div class="form-actions" style="justify-content:center;">
            <a href="{{ route('student.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            <form action="{{ route('student.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-outline">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection

