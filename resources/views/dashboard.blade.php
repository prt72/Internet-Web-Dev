@extends('layouts.app')
@section('title', 'Dashboard')
@section('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
  body {
    background: linear-gradient(to bottom right, #fdfbff, #e8f0ff);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .dashboard-page {
    margin-top: 70px;
    padding: 2rem;
    min-height: 100vh;
    background: transparent;
  }

  .dashboard-page h1 {
    font-size: 2.8rem;
    font-weight: bold;
    color: #4c3c94;
    margin-bottom: 10px;
  }

  .text-muted.lead {
    color: #7a7a7a;
  }

  .filter-group {
    display: flex;
    gap: 1.2rem;
    justify-content: center;
    align-items: flex-end;
    flex-wrap: wrap;
    background: #ffffff;
    padding: 1.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    margin-bottom: 3rem;
  }

  .filter-group label {
    font-weight: 600;
    color: #444;
    font-size: 0.95rem;
  }

  .filter-group select {
    border-radius: 6px;
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    background-color: #fdfdfd;
    color: #333;
    width: 200px;
  }

  .btn-filter {
    background-color: #b784f0;
    color: #fff;
    font-weight: 600;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
  }

  .btn-filter:hover {
    background-color: #9e66dc;
  }

  .card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
  }

  .card-body {
    padding: 1.8rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .card-title {
    text-align: center;
    font-weight: 700;
    font-size: 1.4rem;
    margin-bottom: 1rem;
    color: #574b90;
  }

  .chart-wrapper {
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .chart-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
  }
 
    /* Collection & Wishlist Items */
    .collection-item {
    width: 130px;
    border-radius: 10px;
    padding: 10px;
    background: #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease;
    text-align: center;
  }

  .collection-item:hover {
    transform: scale(1.05);
  }

  .collection-item img {
    width: 75px;
    height: 75px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 0.5rem;
  }

  .collection-item strong {
    display: block;
    font-size: 0.95rem;
    color: #333;
    margin-bottom: 0.2rem;
  }

  .collection-item .badge {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    padding: 0.35em 0.6em;
    border-radius: 10px;
  }

  .collection-item .text-muted {
    font-size: 0.75rem;
  }

  .btn-outline-primary,
  .btn-outline-success {
    font-weight: 600;
    border-radius: 8px;
    padding: 0.5rem 1rem;
  }

  /* Chart Tooltip Styling */
  .chartjs-tooltip {
    font-size: 0.9rem;
    font-weight: 500;
    color: #444;
  }

  /* Responsive Love */
  @media (max-width: 768px) {
    .dashboard-page {
      padding: 1rem;
    }

    .chart-wrapper {
      height: 240px;
    }

    h1 {
      font-size: 2.2rem;
    }

    .display-6 {
      font-size: 1.6rem;
    }

    .collection-item {
      width: 100px;
    }

    .collection-item img {
      width: 60px;
      height: 60px;
    }
  }

  @media (min-width: 1200px) {
    .chart-wrapper {
      height: 380px;
    }
  }
</style>

@endsection
@section('content')
<div class="dashboard-page">
  <div class="container">
    <div class="text-center mb-5 pt-4">
      <h1 class="fw-bold text-dark"><i class="fa-solid fa-chart-line" style="color: #4390cb;"></i> Your Dashboard</h1>
      <p class="text-muted lead">Explore your collection with detailed insights.</p>
    </div>
    
    <!-- STATISTICS -->
    <div class="row g-4 mb-5">
      <div class="col-md-4 col-sm-6">
        <div class="card p-4 text-center shadow-sm h-100">
          <h5><i class="fa-solid fa-boxes-stacked" style="color: #6ec02a;"></i> Total Owned Items</h5>
          <div class="display-6 text-primary">{{ $collectionStats['total_owned'] }}</div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card p-4 text-center shadow-sm h-100">
          <h5><i class="fa-solid fa-clone" style="color: #563da4;"></i> Duplicates</h5>
          <div class="display-6 text-warning">{{ $collectionStats['duplicates'] }}</div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card p-4 text-center shadow-sm h-100">
          <h5><i class="fa-solid fa-heart" style="color: #ff0000;"></i> Wishlist Items</h5>
          <div class="display-6 text-info">{{ $wishlistStats['total_wishlist'] }}</div>
        </div>
      </div>
    </div>

    <!-- FILTERS -->
    <div class="mb-5 filter-group">
    <div>
        <label for="categorySelect" class="form-label fw-semibold">Category</label>
        <select id="categorySelect" class="form-select">
          <option value="">All Categories</option>
          @foreach($availableCategories as $category)
            <option value="{{ $category }}">{{ $category }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label for="seriesSelect" class="form-label fw-semibold">Series</label>
        <select id="seriesSelect" class="form-select">
        <option value="">All Series</option>
          @foreach($availableSeries as $series)
            <option value="{{ $series }}">{{ $series }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label for="raritySelect" class="form-label fw-semibold">Rarity</label>
        <select id="raritySelect" class="form-select" title="Rarity Levels: Common (Standard Edition), Rare (Special Edition), Very Rare (Secret Edition), Rarest (Super Secret Edition)">
          <option value="">All Rarities</option>
          <option value="Common">Common</option>
          <option value="Rare">Rare</option>
          <option value="Very Rare">Very Rare</option>
          <option value="Rarest">Rarest</option>
        </select>
      </div>
      <div>
        <label for="dateRange" class="form-label fw-semibold">Date Range</label>
        <select id="dateRange" class="form-select">
          <option value="">All Time</option>
          @foreach($availableMonths as $month)
            <option value="{{ $month }}">{{ $month }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <button id="applyFilters" class="btn btn-filter mt-4">Filter</button>
      </div>
      <!-- Note outside dropdown -->
      <p class="text-muted text-center mt-2 small">Rarity Levels: Common (Standard Edition), Rare (Special Edition), Very Rare (Secret Edition), Rarest (Super Secret Edition)</p>
    </div>
    
    <!-- CHARTS -->
    <div class="row g-4 mb-5">
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-chart-bar me-2"></i> Collection Stats</h5>
            <div class="chart-wrapper"><canvas id="collectionChart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-chart-pie me-2"></i> Duplicates Distribution</h5>
            <div class="chart-wrapper"><canvas id="duplicatesChart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-gem me-2"></i> Rarity Distribution</h5>
            <div class="chart-wrapper"><canvas id="rarityChart"></canvas></div>
            <p class="text-muted text-center mt-2 small">Rarity Levels: Common (Standard Edition), Rare (Special Edition), Very Rare (Secret Edition), Rarest (Super Secret Edition)</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fa-solid fa-circle-notch me-2"></i> Collection Breakdown</h5>
            <div class="chart-wrapper"><canvas id="seriesPieChart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-calendar-alt me-2"></i> Collection Over Time</h5>
            <div class="chart-wrapper"><canvas id="heatmapChart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-layer-group me-2"></i> Edition Breakdown</h5>
            <div class="chart-wrapper"><canvas id="editionStackedChart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-star me-2"></i> Top Series</h5>
            <div class="chart-wrapper"><canvas id="topSeriesChart"></canvas></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card chart-card h-100">
          <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-pie-chart me-2"></i> Category Distribution</h5>
            <div class="chart-wrapper"><canvas id="categoryChart"></canvas></div>
          </div>
        </div>
      </div>
    </div>

    <!-- COLLECTION & WISHLIST -->
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm h-100 p-4">
          <h5 class="text-primary mb-3"><i class="fas fa-box-open me-2"></i> Your Collection</h5>
          @if($owned->isEmpty())
            <div class="text-muted">Start tracking your figurines now!</div>
          @else
            <div class="d-flex flex-wrap gap-3 justify-content-center">
              @foreach($owned as $item)
                <div class="collection-item">
                  <img src="{{ asset($item->figurine->image_url) }}" alt="{{ $item->figurine->name }}">
                  <strong>{{ $item->figurine->name }}</strong>
                  @if($item->figurine->rarity === 'Rare') <span class="badge -secondary">🔒</span> @endif
                  @if($item->figurine->rarity === 'Very Rare') <span class="badge warning">⭐</span> @endif
                  @if($item->figurine->rarity === 'Rarest') <span class="badge danger">👑</span> @endif
                  <br>
                  <span class="text-muted small">{{ $item->figurine->series }}</span>
                </div>
              @endforeach
            </div>
            <a href="{{ route('collection') }}" class="btn btn-outline-primary mt-3 d-block mx-auto" style="width: 120px;">See More</a>
          @endif
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm h-100 p-4">
          <h5 class="text-success mb-3"><i class="fas fa-heart me-2"></i> Your Wishlist</h5>
          @if($wishlist->isEmpty())
            <div class="text-muted">Add your dream figurines!</div>
          @else
            <div class="d-flex flex-wrap gap-3 justify-content-center">
              @foreach($wishlist as $item)
                <div class="collection-item">
                  <img src="{{ asset($item->figurine->image_url) }}" alt="{{ $item->figurine->name }}">
                  <strong>{{ $item->figurine->name }}</strong>
                  @if($item->figurine->rarity === 'Rare') <span class="badge bg-secondary">🔒</span> @endif
                  @if($item->figurine->rarity === 'Very Rare') <span class="badge bg-warning">⭐</span> @endif
                  @if($item->figurine->rarity === 'Rarest') <span class="badge bg-danger">👑</span> @endif
                  <br>
                  <span class="text-muted small">{{ $item->figurine->series }}</span>
                </div>
              @endforeach
            </div>
            <a href="{{ route('wishlist') }}" class="btn btn-outline-success mt-3 d-block mx-auto" style="width: 120px;">See More</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  Chart.defaults.plugins.legend.position = 'top';
  Chart.defaults.plugins.tooltip.enabled = true;

  const charts = {
    collectionChart: null,
    duplicatesChart: null,
    rarityChart: null,
    seriesPieChart: null,
    heatmapChart: null,
    editionStackedChart: null,
    topSeriesChart: null,
    categoryChart: null
  };

  function createEmptyChart(ctx, type, options = {}) {
    return new Chart(ctx, {
      type: type,
      data: {
        labels: [],
        datasets: [{
          label: 'No data',
          data: [],
          backgroundColor: '#eee'
        }]
      },
      options: Object.assign({
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        }
      }, options)
    });
  }

  function updateCharts(data) {
    // Collection Stats
    if (charts.collectionChart) charts.collectionChart.destroy();
    const collectionCtx = document.getElementById("collectionChart").getContext("2d");
    if (data.breakdown.total_figurines === 0) {
      charts.collectionChart = createEmptyChart(collectionCtx, 'bar');
    } else {
      charts.collectionChart = new Chart(collectionCtx, {
        type: 'bar',
        data: {
          labels: ['Total Figurine Types', 'Total Items Owned'],
          datasets: [{
            label: 'Count',
            data: [data.breakdown.total_figurines, data.breakdown.owned],
            backgroundColor: ['#c851c0', '#8329ae'],
            borderRadius: 5
          }]
        },
        options: {
          scales: { y: { beginAtZero: true } }
        }
      });
    }

    // Duplicates Distribution
    if (charts.duplicatesChart) charts.duplicatesChart.destroy();
    const duplicatesCtx = document.getElementById("duplicatesChart").getContext("2d");
    if (data.breakdown.total_figurines === 0) {
      charts.duplicatesChart = createEmptyChart(duplicatesCtx, 'pie');
    } else {
      charts.duplicatesChart = new Chart(duplicatesCtx, {
        type: 'pie',
        data: {
          labels: ['Types with One Copy', 'Types with Duplicates'],
          datasets: [{
            data: [data.breakdown.unique_figurines, data.breakdown.figurines_with_duplicates],
            backgroundColor: ['#2ab2a9', '#2a7eb2'],
            hoverOffset: 10
          }]
        }
      });
    }

    // Rarity Distribution
    if (charts.rarityChart) charts.rarityChart.destroy();
    const rarityCtx = document.getElementById("rarityChart").getContext("2d");
    if (data.rarity.labels.length === 0) {
      charts.rarityChart = createEmptyChart(rarityCtx, 'bar');
    } else {
      charts.rarityChart = new Chart(rarityCtx, {
        type: 'bar',
        data: {
          labels: data.rarity.labels,
          datasets: [{
            label: 'Count',
            data: data.rarity.values,
            backgroundColor: ['#fff000', '#ffc100', '#ff9300', '#ff6800'],
            borderRadius: 5
          }]
        },
        options: {
          scales: { y: { beginAtZero: true } },
          onClick: (e) => handleChartClick(e, 'rarity')
        }
      });
    }

    // Collection Breakdown
    if (charts.seriesPieChart) charts.seriesPieChart.destroy();
    const seriesPieCtx = document.getElementById("seriesPieChart").getContext("2d");
    const total = data.breakdown.owned + data.breakdown.total_duplicate_items + data.breakdown.wishlist;
    if (total === 0) {
      charts.seriesPieChart = createEmptyChart(seriesPieCtx, 'doughnut');
    } else {
      charts.seriesPieChart = new Chart(seriesPieCtx, {
        type: 'doughnut',
        data: {
          labels: ['Owned', 'Duplicates', 'Wishlist'],
          datasets: [{
            data: [data.breakdown.owned, data.breakdown.total_duplicate_items, data.breakdown.wishlist],
            backgroundColor: ['#d50404', '#871111', '#5e0d0d'],
            hoverOffset: 10
          }]
        }
      });
    }

    // Top Series
    if (charts.topSeriesChart) charts.topSeriesChart.destroy();
    const topSeriesCtx = document.getElementById("topSeriesChart").getContext("2d");
    if (data.topSeries.labels.length === 0) {
      charts.topSeriesChart = createEmptyChart(topSeriesCtx, 'bar', { indexAxis: 'y' });
    } else {
      charts.topSeriesChart = new Chart(topSeriesCtx, {
        type: 'bar',
        data: {
          labels: data.topSeries.labels,
          datasets: [{
            label: 'Items',
            data: data.topSeries.values,
            backgroundColor: '#0d5e4d',
            borderRadius: 5
          }]
        },
        options: {
          indexAxis: 'y',
          scales: { x: { beginAtZero: true } },
          onClick: (e) => handleChartClick(e, 'series')
        }
      });
    }

    // Category Distribution
    if (charts.categoryChart) charts.categoryChart.destroy();
    const categoryCtx = document.getElementById("categoryChart").getContext("2d");
    if (data.categories.labels.length === 0) {
      charts.categoryChart = createEmptyChart(categoryCtx, 'pie');
    } else {
      charts.categoryChart = new Chart(categoryCtx, {
        type: 'pie',
        data: {
          labels: data.categories.labels,
          datasets: [{
            data: data.categories.values,
            backgroundColor: ['#ff99e6', '#f86fd6', '#e14dbc', '#c5199a', '#cd00b4'],
            hoverOffset: 10
          }]
        }
      });
    }
  }

  // Initialize static charts
  const collectionOverTime = @json($collectionOverTime);
  const editionBreakdown = @json($editionBreakdown);

  const heatmapCtx = document.getElementById("heatmapChart").getContext("2d");
  charts.heatmapChart = new Chart(heatmapCtx, {
    type: 'line',
    data: {
      labels: collectionOverTime.map(item => item.month),
      datasets: [{
        label: 'Items Added',
        data: collectionOverTime.map(item => item.count),
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1,
        fill: true
      }]
    },
    options: {
      scales: { y: { beginAtZero: true } }
    }
  });

  const editionCtx = document.getElementById("editionStackedChart").getContext("2d");
  charts.editionStackedChart = new Chart(editionCtx, {
    type: 'bar',
    data: {
      labels: editionBreakdown.map(item => item.edition),
      datasets: [{
        label: 'Count',
        data: editionBreakdown.map(item => item.count),
        backgroundColor: '#9b59b6',
        borderRadius: 5
      }]
    },
    options: {
      scales: { y: { beginAtZero: true } }
    }
  });

  document.getElementById("applyFilters").addEventListener("click", function () {
    const filters = {
      series: document.getElementById("seriesSelect").value,
      rarity: document.getElementById("raritySelect").value,
      category: document.getElementById("categorySelect").value,
      date: document.getElementById("dateRange").value
    };

    $.ajax({
      url: '/dashboard/series-charts',
      method: 'GET',
      data: filters,
      success: updateCharts,
      error: function(xhr) {
        console.error('Error:', xhr.responseText);
        alert('Failed to load data. Check console for details.');
      }
    });
  });

  // Initial load
  $.ajax({
    url: '/dashboard/series-charts',
    method: 'GET',
    success: updateCharts,
    error: function(xhr) {
      console.error('Initial load error:', xhr.responseText);
      Object.values(charts).forEach(chart => chart && chart.destroy());
      Object.keys(charts).forEach(id => {
        const ctx = document.getElementById(id).getContext("2d");
        createEmptyChart(ctx, id.includes('pie') || id.includes('seriesPie') ? 'pie' : 'bar');
      });
    }
  });

  function handleChartClick(event, type) {
    const chart = charts[type + 'Chart'];
    const elements = chart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
    if (elements.length > 0) {
      const label = chart.data.labels[elements[0].index];
      let url = '';
      switch(type) {
        case 'rarity': url = `/collection?rarity=${encodeURIComponent(label)}`; break;
        case 'series': url = `/collection?series=${encodeURIComponent(label)}`; break;
      }
      if (url) window.location.href = url;
    }
  }
});
</script>
@endsection