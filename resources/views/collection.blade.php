@extends('layouts.app')
@section('title', 'Collection')
@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/collection.css') }}">
@endsection
@section('content')
<div class="catalogue-page">
  <div class="container mt-5">
    <h1 class="text-center mb-4">Collection</h1>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs justify-content-center mb-4">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'owned' ? 'active' : '' }}" href="{{ route('collection', array_merge(request()->except('page'), ['tab' => 'owned'])) }}">Owned</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'duplicates' ? 'active' : '' }}" href="{{ route('collection', array_merge(request()->except('page'), ['tab' => 'duplicates'])) }}">Duplicates</a>
        </li>
    </ul>

    {{-- Indicator Box --}}
    <div class="indicator-box mx-auto">
      <span class="indicator-item">✅ x1 In Collection</span>
      <span class="indicator-item">⭐ Special</span>
      <span class="indicator-item">🎀 Secret</span>
      <span class="indicator-item">👑 Super Secret</span>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('collection') }}" class="filters-row">
      <input type="hidden" name="tab" value="{{ $tab }}">
      <select name="category" class="form-select">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
      </select>
      <select name="series" class="form-select">
        <option value="">All Series</option>
        @foreach($seriesOptions as $ser)
          <option value="{{ $ser }}" {{ request('series') == $ser ? 'selected' : '' }}>{{ $ser }}</option>
        @endforeach
      </select>
      <select name="edition" class="form-select">
        <option value="">All Editions</option>
        @foreach(['Standard', 'Special', 'Secret', 'Super Secret'] as $e)
          <option value="{{ $e }}" {{ request('edition') == $e ? 'selected' : '' }}>{{ $e }}</option>
        @endforeach
      </select>
      <select name="rarity" class="form-select">
        <option value="">All Rarities</option>
        @foreach(['Common', 'Rare', 'Very Rare', 'Rarest'] as $r)
          <option value="{{ $r }}" {{ request('rarity') == $r ? 'selected' : '' }}>{{ $r }}</option>
        @endforeach
      </select>
      <select name="condition" class="form-select">
        <option value="">All Conditions</option>
        @foreach(['New', 'Used', 'Damaged'] as $condition)
        <option value="{{ $condition }}" {{ request('condition') == $condition ? 'selected' : '' }}>{{ $condition }}</option>
        @endforeach
      </select>
      <select name="purchase_source" class="form-select">
        <option value="">Purchase Source</option>
        @foreach(['Local Store', 'Online Store', 'Other'] as $source)
        <option value="{{ $source }}" {{ request('purchase_source') == $source ? 'selected' : '' }}>{{ $source }}</option>
        @endforeach
      </select>
      <div class="search-input">
        <input type="search" name="search" class="form-control" placeholder="Search by name…" value="{{ request('search') }}">
      </div>
      <!-- Sorting Dropdown -->
      <select name="sort" class="form-select">
        <option value="name_asc" {{ request('sort')=='name_asc' ? 'selected' : '' }}>Name A–Z</option>
        <option value="name_desc" {{ request('sort')=='name_desc' ? 'selected' : '' }}>Name Z–A</option>
        <option value="release_date_new" {{ request('sort')=='release_date_new' ? 'selected' : '' }}>Release Date Newest First</option>
        <option value="release_date_old" {{ request('sort')=='release_date_old' ? 'selected' : '' }}>Release Date Oldest First</option>
        <option value="purchase_date_new" {{ request('sort')=='purchase_date_new' ? 'selected' : '' }}>Purchase Date Newest First</option>
        <option value="purchase_date_old" {{ request('sort')=='purchase_date_old' ? 'selected' : '' }}>Purchase Date Oldest First</option>
      </select>
      <button class="btn btn-primary">Filter</button>
    </form>

    <!-- Note outside dropdown -->
    <p class="text-muted text-center mt-2 small">Rarity Levels: Common (Standard Edition), Rare (Special Edition), Very Rare (Secret Edition), Rarest (Super Secret Edition)</p>

    <!-- QR Code Display -->
    <div class="text-center mb-4">
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#qrModal">
        Show QR Code
      </button>
    </div>


    {{-- Cards --}}
    <div class="row">
      @foreach($items as $item)
        @php
          $qty = $item->quantity ?? 1;
          $emojiMap = ['Special' => '⭐', 'Secret' => '🎀', 'Super Secret' => '👑'];
          $emoji = $emojiMap[$item->figurine->edition] ?? '';
        @endphp

        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm position-relative">
            {{-- Duplicate-count or checkmark --}}
            @if($qty > 0)
              <span class="emoji-badge qty-badge position-absolute top-0 start-0 m-2"
                    style="color:red;font-weight:bold;">
                {{ $qty > 1 ? "×{$qty}" : '✅' }}
              </span>
            @endif
            {{-- Edition ribbon --}}
            @if($emoji)
              <span class="emoji-badge position-absolute top-0 end-0 m-2">
                {{ $emoji }}
              </span>
            @endif
            <img src="{{ asset($item->figurine->image_url) }}" class="card-img-top" alt="{{ $item->figurine->name }}">
            <div class="card-body card-details">
              <p class="figurine-name">{{ $item->figurine->name }}</p>
              <p><strong>Category:</strong> {{ $item->figurine->category }}</p>
              <p><strong>Series:</strong> {{ $item->figurine->series }}</p>
              <p><strong>Edition:</strong> {{ $item->figurine->edition }}</p>
              <p><strong>Rarity:</strong> {{ $item->figurine->rarity }}</p>
              <p><strong>Release Date:</strong> {{ $item->figurine->release_date }}</p>
              <p><strong>Purchase Date:</strong> {{ $item->purchase_date }}</p>
              <p><strong>Condition:</strong> {{ $item->condition }}</p>
              <p><strong>Comment:</strong> {{ $item->comment }}</p>
              <p><strong>Purchase Source:</strong> {{ $item->purchase_source }}</p>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center px-3">
              @if($tab === 'duplicates' && $item->quantity > 1)
                <button type="button" class="btn btn-outline-info btn-sm view-info-btn"
                        data-info='@json($item->duplicates_info)'  
                        data-name="{{ $item->figurine->name }}"    
                        data-id="{{ $item->id }}">
                    View Info
                </button>
              @endif
              <button class="btn btn-outline-secondary btn-sm edit-btn"
                      data-id="{{ $item->id }}"
                      data-date="{{ $item->purchase_date }}"
                      data-condition="{{ $item->condition }}"
                      data-comment="{{ $item->comment }}"
                      data-source="{{ $item->purchase_source }}">
                Edit
              </button>
              <button class="btn btn-outline-danger btn-sm remove-btn" data-id="{{ $item->id }}">
                Remove
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Duplicate Details Modal -->
    <div class="modal fade" id="duplicateModal" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="duplicateModalLabel">Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <button type="button" class="btn btn-sm btn-secondary" id="dupPrev">&laquo;</button>
              <span id="dupCounter"></span>
              <button type="button" class="btn btn-sm btn-secondary" id="dupNext">&raquo;</button>
            </div>
            <div id="dupContent"></div> <!-- This is where we'll load duplicate info -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Figurine</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">
            <!-- Purchase Date Field -->
            <div class="mb-3">
              <label>Purchase Date</label>
              <input type="date" name="purchase_date" id="edit-date" class="form-control">
            </div>
            <!-- Condition Dropdown -->
            <div class="mb-3">
              <label>Condition</label>
              <select name="condition" id="edit-condition" class="form-select" required>
                <option value="New">New</option>
                <option value="Used">Used</option>
                <option value="Damaged">Damaged</option>
                <option value="Miscellaneous">Miscellaneous</option>
              </select>
            </div>
            <!-- Purchase Source Dropdown -->
            <div class="mb-3">
              <label>Purchase Source</label>
              <select name="purchase_source" id="edit-source" class="form-select" required>
                <option value="Local Store">Local Store</option>
                <option value="Online Store">Online Store</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <!-- Comment Textarea -->
            <div class="mb-3">
              <label>Comment</label>
              <textarea name="comment" id="edit-comment" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content qr-container text-center">
      <div class="modal-header border-0">
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <h5 class="mb-3">📱 Scan to View Your Collection</h5>
        <div class="qr-code mx-auto">{!! $qrCode !!}</div>
      </div>
    </div>
  </div>
</div>

  {{-- Pagination --}}
  <div class="mt-4 d-flex justify-content-center">
    {{ $items->links('pagination::bootstrap-4') }}
  </div>
</div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const token = document.querySelector('meta[name="csrf-token"]').content;

      // Edit Modal logic
      document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          document.getElementById('edit-id').value = btn.dataset.id;
          document.getElementById('edit-date').value = btn.dataset.date;
          document.getElementById('edit-condition').value = btn.dataset.condition;
          document.getElementById('edit-comment').value = btn.dataset.comment;
          document.getElementById('edit-source').value = btn.dataset.source;
          new bootstrap.Modal(document.getElementById('editModal')).show();
        });
      });

      // Form submission for edit
      document.getElementById('editForm').addEventListener('submit', async e => {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const form = new FormData(e.target);
        const res = await fetch(`/collection/${id}/update`, {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': token },
          body: form,
        });
        if (res.ok) return location.reload();
        alert('Failed to update item.');
      });

      // Remove button functionality
      document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
          const id = btn.dataset.id;
          const confirmDelete = confirm('Are you sure you want to remove one copy of this item?');
          if (!confirmDelete) return;

          const res = await fetch(`/collection/${id}/delete-one`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': token,
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({}),
          });

          if (res.ok) {
            location.reload();
          } else {
            alert('Failed to remove item.');
          }
        });
      });

      // Duplicate Modal logic
      const dupModalEl = document.getElementById('duplicateModal');
      const dupModal = new bootstrap.Modal(dupModalEl);
      let dupInfo = [];
      let dupIndex = 0;

      // Function to render duplicate info in the modal
      function renderDup() {
        if (!dupInfo || dupInfo.length === 0) {
          document.getElementById('dupContent').innerHTML = '<p>No duplicate information available.</p>';
          document.getElementById('dupCounter').textContent = '0 of 0';
          document.getElementById('duplicateModalLabel').textContent = dupModalEl.dataset.name || 'Details';
          return;
        }
        const dup = dupInfo[dupIndex];
        document.getElementById('duplicateModalLabel').textContent =
          `${dupModalEl.dataset.name} — Duplicate ${dupIndex + 1}`;
        document.getElementById('dupCounter').textContent =
          `${dupIndex + 1} of ${dupInfo.length}`;
        document.getElementById('dupContent').innerHTML = `
          <p><strong>Purchase Date:</strong> ${dup.purchase_date || 'N/A'}</p>
          <p><strong>Condition:</strong> ${dup.condition || 'N/A'}</p>
          <p><strong>Comment:</strong> ${dup.comment || '–'}</p>
          <p><strong>Source:</strong> ${dup.purchase_source || 'N/A'}</p>
        `;
      }

      // Bind buttons for viewing duplicate info
      document.querySelectorAll('.view-info-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          try {
            dupInfo = JSON.parse(btn.getAttribute('data-info'));
            console.log('Duplicate Info:', dupInfo); // Debug: Check data in console
          } catch (e) {
            console.error('Error parsing duplicates_info:', e);
            dupInfo = [];
          }
          dupIndex = 0; // Start at the first duplicate
          dupModalEl.dataset.name = btn.getAttribute('data-name');
          renderDup();
          dupModal.show();
        });
      });

      // Previous button for duplicate info
      document.getElementById('dupPrev').addEventListener('click', () => {
        if (dupIndex > 0) { dupIndex--; renderDup(); }
      });

      // Next button for duplicate info
      document.getElementById('dupNext').addEventListener('click', () => {
        if (dupIndex < dupInfo.length - 1) { dupIndex++; renderDup(); }
      });
    });
  </script>
@endsection