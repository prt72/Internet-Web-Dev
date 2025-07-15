@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link rel="stylesheet" href="{{ asset('css/header.css') }}">
@endsection

@section('content')
<div class="auth-container">
  <div class="auth-card">
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    <h2>Sign In to PopMart Tracker</h2>
    <form method="POST" action="{{ route('login.post') }}">
      {{-- Manually add CSRF token without autocomplete --}}
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

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

      <button type="submit" class="auth-btn">SIGN IN</button>
    </form>

    <p class="auth-footer">
      Don't have an account? <a href="{{ route('register') }}">Register here</a>
    </p>
  </div>
</div>
@endsection
