@extends('layouts.app')

@section('title', 'PopMart Collectible Tracker')

@section('content')
<div class="guest-homepage">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h2>Welcome to PopMart Collectible Tracker</h2>
            <p>Discover, manage, and grow your PopMart figurine collection with ease</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Features:</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-cogs"></i>
                    <h3>Track Your Figurine Collection</h3>
                    <p>Easily add, track, and manage your PopMart figurines</p>
                </div>
                <div class="feature-card">
                    <i class="fa-solid fa-heart"></i>
                    <h3>Add to Wishlist</h3>
                    <p>Browse the catalogue, and add figurines to your wishlist</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-exchange-alt"></i>
                    <h3>Manage Duplicates</h3>
                    <p>Worry about duplicates? Track them!</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-handshake"></i>
                    <h3>Share your Collectibles</h3>
                    <p>Just by scanning the QR!</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-star"></i>
                    <h3>Get Personalized Wishlist Recommendations</h3>
                    <p>Receive new figurine suggestions based on your collection and interests</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-store"></i>
                    <h3>Find Nearest Stores</h3>
                    <p>Locate PopMart official stores near you and purchase your figurine</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
@endsection
