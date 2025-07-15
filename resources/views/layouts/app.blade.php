<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PopMart Collectible Tracker')</title>

    {{-- Load correct header styles based on auth --}}
    @auth
        <link rel="stylesheet" href="{{ asset('css/header_l.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @else
        <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    @endauth

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page-specific styles --}}
    @yield('styles')
</head>
<body id="app">

    {{-- Include the appropriate header --}}
    @auth
        @include('partials.header_l')
    @else
        @include('partials.header')
    @endauth

    {{-- Page content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
<footer style="text-align: center; padding: 20px; background-color: rgb(117, 156, 174); color: white; margin-top: auto;">
    <p>&copy; {{ date('Y') }} PopMart Collectible Tracker</p>
</footer>


    <!-- {{-- JS --}}
    <script src="{{ asset('js/app.js') }}"></script> -->
    @yield('scripts')
</body>
</html>
