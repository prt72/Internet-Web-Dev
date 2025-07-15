@extends('layouts.app')

@section('title','Catalogue')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/catalogue.css') }}">
@endsection

@section('content')
<div class="catalogue-page">
  <div class="container mt-5">
    <h1 class="text-center mb-4">Catalogue</h1>

    {{-- Indicator Box --}}
    <div class="indicator-box mx-auto">
      <span class="indicator-item">✅ x1 In Collection</span>
      <span class="indicator-item">⭐ Special</span>
      <span class="indicator-item">🎀 Secret</span>
      <span class="indicator-item">👑 Super Secret</span>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('catalogue') }}" class="filters-row">
      <select name="category" class="form-select">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ $cat }}</option>
        @endforeach
      </select>
      <select name="series" class="form-select">
        <option value="">All Series</option>
        @foreach($seriesOptions as $ser)
          <option value="{{ $ser }}" {{ request('series')==$ser?'selected':'' }}>{{ $ser }}</option>
        @endforeach
      </select>
      <select name="edition" class="form-select">
        <option value="">All Editions</option>
        @foreach(\App\Models\Figurine::distinct()->pluck('edition') as $e)
          <option value="{{ $e }}" {{ request('edition')==$e?'selected':'' }}>{{ $e }}</option>
        @endforeach
      </select>
      <select name="rarity" class="form-select">
        <option value="">All Rarities</option>
        @foreach(\App\Models\Figurine::distinct()->pluck('rarity') as $r)
          <option value="{{ $r }}" {{ request('rarity')==$r?'selected':'' }}>{{ $r }}</option>
        @endforeach
      </select>
      <select name="sort" class="form-select">
        <option value="name_asc" {{ request('sort')=='name_asc'?'selected':'' }}>Name A–Z</option>
        <option value="name_desc" {{ request('sort')=='name_desc'?'selected':'' }}>Name Z–A</option>
        <option value="date_new" {{ request('sort')=='date_new'?'selected':'' }}>Newest First</option>
        <option value="date_old" {{ request('sort')=='date_old'?'selected':'' }}>Oldest First</option>
      </select>
      <div class="search-input">
        <input type="search" name="search" class="form-control"
               placeholder="Search by name…" value="{{ request('search') }}">
      </div>
      <button class="btn btn-primary">Filter</button>
    </form>

    <!-- Note outside dropdown -->
    <p class="text-muted text-center mt-2 small">Rarity Levels: Common (Standard Edition), Rare (Special Edition), Very Rare (Secret Edition), Rarest (Super Secret Edition)</p>
    {{-- Cards --}}
    <div class="row">
      @if($collectibles->isEmpty())
      <div class="col-12 text-center">
        <p class="text-muted">No results found for your filtering criteria.</p>
      </div>
      @else
      @foreach($collectibles as $item)
        @php
          $emojiMap = ['Special'=>'⭐','Secret'=>'🎀','Super Secret'=>'👑'];
          $emoji    = $emojiMap[$item->edition] ?? '';
          $qty      = $userCollectionData[$item->id] ?? 0;
        @endphp
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm position-relative">
            {{-- Duplicate‑count or checkmark --}}
            @if($qty > 0)
              <span class="emoji-badge qty-badge position-absolute top-0 start-0 m-2"
                    style="color:red;font-weight:bold;">
                {{ $qty>1 ? "×{$qty}" : '✅' }}
              </span>
            @endif
            {{-- Edition ribbon --}}
            @if($emoji)
              <span class="emoji-badge position-absolute top-0 end-0 m-2">
                {{ $emoji }}
              </span>
            @endif

            <img src="{{ asset($item->image_url) }}"
                 class="card-img-top" alt="{{ $item->name }}">

            <div class="card-body card-details">
              <p class="figurine-name">{{ $item->name }}</p>
              <p><strong>Category:</strong> {{ $item->category }}</p>
              <p><strong>Series:</strong> {{ $item->series }}</p>
              <p><strong>Edition:</strong> {{ $item->edition }}</p>
              <p><strong>Rarity:</strong> {{ $item->rarity }}</p>
              <p><strong>Release Date:</strong> {{ $item->release_date }}</p>
            </div>
            
            <div class="card-footer d-flex justify-content-between align-items-center px-3">
              {{-- Add to collection button --}}
              <button class="btn btn-outline-success btn-sm add-to-collection"
                      data-id="{{ $item->id }}"
                      data-name="{{ $item->name }}"
                      data-series="{{ $item->series }}"
                      data-edition="{{ $item->edition }}">
                + Add to Collection</button>
                
              {{-- Wishlist heart --}}
              <div class="heart-container" title="Like">
                  <input type="checkbox" class="checkbox wishlist-toggle" id="heart-{{ $item->id }}" data-id="{{ $item->id }}" 
                  {{ in_array($item->id, $userWishlistIds) ? 'checked' : '' }}>
                  <div class="svg-container">
                    <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
                      <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"/>
                    </svg>
                    <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
                      <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"/>
                    </svg>
                    <svg class="svg-celebrate" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                    </svg>
                  </div>
                </div>
            </div>
          </div>
        </div>
      @endforeach
      @endif
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
      {{ $collectibles->links('pagination::bootstrap-4') }}
    </div>
  </div>
</div>

{{-- Collection Modal --}}
<div class="modal fade" id="collectionModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form id="collectionForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add to My Collection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="figurine_id" id="figId">
        <div class="mb-3"><label>Name</label><input id="figName" class="form-control" readonly></div>
        <div class="mb-3"><label>Series</label><input id="figSeries" class="form-control" readonly></div>
        <div class="mb-3"><label>Edition</label><input id="figEdition" class="form-control" readonly></div>
        <div class="mb-3"><label>Purchase Date</label>
          <input type="date" name="purchase_date" class="form-control" required max="{{ date('Y-m-d') }}">
        </div>
        <div class="mb-3"><label>Condition</label>
        <select name="condition" class="form-select" required>
          <option value="" disabled selected>Select condition</option>
          <option>New</option>
          <option>Used</option>
          <option>Damaged</option>
          <option>Miscellaneous</option>
        </select>
        </div>
        <div class="mb-3"><label>Purchase Source</label>
        <select name="purchase_source" class="form-select" required>
          <option value="" disabled selected>Select source</option>
          <option>Local Store</option>
          <option>Online Store</option>
          <option>Other</option>
        </select>
        </div>
        <div class="mb-3"><label>Comment</label><textarea name="comment" class="form-control"></textarea></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const seriesByCategory = @json($seriesOptionsGroupedByCategory);

  document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    // Dynamic Series Dropdown
    const categorySelect = document.querySelector('select[name="category"]');
    const seriesSelect = document.querySelector('select[name="series"]');

    categorySelect.addEventListener('change', () => {
      const selectedCategory = categorySelect.value;
      const seriesList = seriesByCategory[selectedCategory] || [];

      // Reset series options
      seriesSelect.innerHTML = '<option value="">All Series</option>';
      seriesList.forEach(ser => {
        const opt = document.createElement('option');
        opt.value = ser;
        opt.textContent = ser;
        seriesSelect.appendChild(opt);
      });
    });

    // Collection Modal
    const modal = new bootstrap.Modal(document.getElementById('collectionModal'));
    document.querySelectorAll('.add-to-collection').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('figId').value = btn.dataset.id;
        document.getElementById('figName').value = btn.dataset.name;
        document.getElementById('figSeries').value = btn.dataset.series;
        document.getElementById('figEdition').value = btn.dataset.edition;
        modal.show();
      });
    });

    document.getElementById('collectionForm').addEventListener('submit', async e => {
      e.preventDefault();
      const res = await fetch('/collection/add-full', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        body: new FormData(e.target)
      });
      if (res.ok) return location.reload();
      alert('Failed to save to collection.');
    });

    // Wishlist Toggle
    document.querySelectorAll('.wishlist-toggle').forEach(cb => {
      cb.addEventListener('change', async function () {
        const id = this.dataset.id;
        const wasChecked = this.checked;
        try {
          const res = await fetch(`/wishlist/toggle/${id}`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
          });
          if (!res.ok) throw new Error('Toggle failed');
          const { status } = await res.json();
          if (status === 'removed' && wasChecked) this.checked = false;
          if (status === 'added' && !wasChecked) this.checked = true;
        } catch (err) {
          console.error(err);
          alert('Could not update your wishlist. Please try again.');
          this.checked = !wasChecked;
        }
      });
    });
  });
</script>
@endsection
