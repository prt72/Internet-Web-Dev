@extends('layouts.app')

@section('title', 'Store Locator')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/location.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
<div class="location-page">
    <div class="container mt-5">
        <div class="alert alert-info d-flex align-items-center justify-content-between">
            <div>
                <i class="fas fa-map-marker-alt me-3"></i>
                Search to find PopMart stores near you!
            </div>
        </div>

        <!-- Single Search Bar -->
        <div class="search-container mb-4">
            <input id="searchBox" class="form-control" type="text" placeholder="Search for stores or locations...">
            <button class="btn btn-primary" onclick="performSearch()">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Map Container -->
        <div id="map" class="leaflet-map"></div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // 🌍 Set default location to Kuala Lumpur (KL)
    var defaultLat = 3.1390;
    var defaultLon = 101.6869;

    // Initialize the map
    const map = L.map('map', {
        zoomControl: false // Disable default zoom controls
    }).setView([defaultLat, defaultLon], 12);

    // Add custom zoom controls to bottom-right
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add "You Are Here" marker at default location
    L.marker([defaultLat, defaultLon], {
        icon: L.divIcon({
            className: 'you-are-here-marker',
            html: '<i class="fa-solid fa-location-dot" style="color:rgb(4, 82, 34);"></i>',
            iconSize: [16, 16],           // Slightly larger icon container
            iconAnchor: [11, 42],         // Center-bottom of the icon
            popupAnchor: [0, -38]         // Move popup up just a bit more
        })
    }).addTo(map)
    .bindPopup("You are here (Kuala Lumpur)")
    .openPopup();

    // Predefined store locations
    const storeLocations = [
        { name: "POP MART Pavilion KL", lat: 3.1459, lon: 101.7117 },
        { name: "POP MART Suria KLCC", lat: 3.1587, lon: 101.7146 },
        { name: "POP MART Mid Valley", lat: 3.1148, lon: 101.6797 },
        { name: "POP MART Sunway Pyramid", lat: 3.0739, lon: 101.6077 },
        { name: "POP MART IOI City Mall", lat: 2.9249, lon: 101.6555 },
        { name: "POP MART 1 Utama", lat: 3.1477, lon: 101.6156 },
        { name: "POP MART Paradigm Mall PJ", lat: 3.1178, lon: 101.6113 },
        { name: "POP MART Central i-City", lat: 3.0637, lon: 101.4927 },
        { name: "POP MART Pavilion Bukit Jalil", lat: 3.0630, lon: 101.6535 },
        { name: "POP MART Sunway Velocity", lat: 3.1365, lon: 101.7243 },
        { name: "POP MART City Square JB Johor Bahru", lat: 1.4626, lon: 103.7631 },
        { name: "POP MART Penang", lat: 5.4148, lon: 100.3307 },
        { name: "POP MART ION Orchard", lat: 1.3048, lon: 103.8314 },
        { name: "POP MART Funan", lat: 1.2847, lon: 103.8461 },
        { name: "POP MART Jewel Changi Airport", lat: 1.3577, lon: 103.9863 },
        { name: "POP MART Plaza Singapura", lat: 1.3027, lon: 103.8461 },
        { name: "POP MART Westgate", lat: 1.3340, lon: 103.7429 },
        { name: "POP MART Lot One", lat: 1.3853, lon: 103.7459 },
        { name: "POP MART Century Square", lat: 1.3533, lon: 103.9463 },
        { name: "POP MART Suntec City", lat: 1.2947, lon: 103.8572 },
        { name: "POP MART Bugis Junction", lat: 1.3004, lon: 103.8559 },
        { name: "POP MART CentralWorld Bangkok", lat: 13.7465, lon: 100.5398 },
        { name: "POP MART Terminal 21 Asoke Bangkok", lat: 13.7357, lon: 100.5663 },
        { name: "POP MART Siam Center Bangkok", lat: 13.7460, lon: 100.5323 },
        { name: "POP MART Central Ladprao Bangkok", lat: 13.8170, lon: 100.5626 },
        { name: "POP MART Central Westgate Nonthaburi", lat: 13.8635, lon: 100.4344 },
        { name: "POP MART Fashion Island Bangkok", lat: 13.8245, lon: 100.6685 },
        { name: "POP MART Mega Bangna Samut Prakan", lat: 13.6449, lon: 100.6799 },
        { name: "POP MART Central Pattaya Pattaya", lat: 12.9355, lon: 100.8888 },
        { name: "POP MART Gandaria City Jakarta Selatan", lat: -6.2439, lon: 106.7854 },
        { name: "POP MART Kota Kasablanka Jakarta Selatan", lat: -6.2287, lon: 106.8345 },
        { name: "POP MART Senayan City Jakarta Pusat", lat: -6.2253, lon: 106.8004 }
    ];

    // Add store markers as red dots
    storeLocations.forEach(store => {
        L.marker([store.lat, store.lon], {
            icon: L.divIcon({
                className: 'store-marker',
                html: '<div class="marker-dot"></div>',
                iconSize: [20, 20]
            })
        }).addTo(map)
        .bindPopup(`
            <div class="store-popup">
                <h6>${store.name}</h6>
                <div class="text-muted small">Open until 10PM</div>
            </div>
        `);
    });

    // Search functionality
    window.performSearch = async () => {
        const query = document.getElementById('searchBox').value.trim();

        if (!query) {
            alert('Please enter a search term');
            return;
        }

        try {
            // Check store names first
            const matchedStore = storeLocations.find(store =>
                store.name.toLowerCase().includes(query.toLowerCase())
            );

            if (matchedStore) {
                map.setView([matchedStore.lat, matchedStore.lon], 15);  // Zoom to store location
                return;
            }

            // Fallback to Nominatim
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`);
            const data = await response.json();

            if (data.length > 0) {
                const { lat, lon, display_name } = data[0];
                map.setView([lat, lon], 15);

                L.popup()
                    .setLatLng([lat, lon])
                    .setContent(`<div class="search-result">Search result: ${display_name}</div>`)
                    .openOn(map);
            } else {
                alert('Location not found');
            }
        } catch (error) {
            console.error(error);
            alert('Search failed. Please try again.');
        }
    };
});
</script>
@endsection
