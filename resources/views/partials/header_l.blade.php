<header class="site-header">
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <a href="{{ route('dashboard') }}">
                <h1>PopMart Collectible Tracker</h1>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="main-nav">
            <ul>
                <li><a href="{{ route('catalogue') }}">Catalogue</a></li>
                <li><a href="{{ route('collection') }}">Collection</a></li>
                <li><a href="{{ route('wishlist') }}">Wishlist</a></li>
            </ul>
        </nav>

        <!-- Right Section -->
        <div class="right-section">
            <!-- Location Icon -->
            <div class="location-icon">
                <a href="{{ route('location') }}">
                    <i class="fa-solid fa-location-dot" style="color: #ff0000;"></i>                </a>
            </div>

            <!-- Profile Dropdown -->
            <div class="profile-dropdown">
                <button class="profile-btn" id="profileToggle" title="Account">
                    <i class="fas fa-user header-icon"></i>
                </button>
                <div class="dropdown-content" id="profileDropdown">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Profile Dropdown Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggle = document.getElementById("profileToggle");
        const dropdown = document.getElementById("profileDropdown");

        document.addEventListener("click", function (e) {
            if (toggle.contains(e.target)) {
                dropdown.classList.toggle("show-dropdown");
            } else {
                dropdown.classList.remove("show-dropdown");
            }
        });
    });
</script>
