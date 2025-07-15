@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/register.css') }}"> <!-- Use register-specific CSS -->
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">  <!-- Include header styles -->
@endsection

@section('content')
<div class="register-page">
  <main class="auth-container">
    <div class="auth-card">
      @if (session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif

      <h2>Register to PopMart Tracker</h2>
      <form method="POST" action="{{ route('register.post') }}">
        @csrf

        {{-- Username Field --}}
        <div class="form-group @error('username') has-error @enderror">
          <label>Username</label>
          <input type="text" name="username" value="{{ old('username') }}" required>
          @error('username')
            <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        {{-- First Name Field --}}
        <div class="form-group @error('first_name') has-error @enderror">
          <label>First Name</label>
          <input type="text" name="first_name" value="{{ old('first_name') }}" required>
          @error('first_name')
            <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        {{-- Last Name Field --}}
        <div class="form-group @error('last_name') has-error @enderror">
          <label>Last Name</label>
          <input type="text" name="last_name" value="{{ old('last_name') }}" required>
          @error('last_name')
            <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        {{-- Email Field --}}
        <div class="form-group @error('email') has-error @enderror">
          <label>Email Address</label>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus>
          @error('email')
            <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        {{-- Password Field --}}
        <div class="form-group @error('password') has-error @enderror">
          <label>Password</label>
          <input type="password" name="password" required>
          @error('password')
            <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        {{-- Confirm Password Field --}}
        <div class="form-group @error('password_confirmation') has-error @enderror">
          <label>Confirm Password</label>
          <input type="password" name="password_confirmation" required>
          @error('password_confirmation')
            <span class="text-danger">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="auth-btn">Register</button>
      </form>

      <p class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in here</a>
      </p>
    </div>
  </main>
</div>
@endsection
