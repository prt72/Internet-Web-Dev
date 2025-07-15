@extends('layouts.app')

@section('title', 'Wishlist')

@section('styles')
    @parent
    <link rel="stylesheet" href="{{ asset('css/wishlist.css') }}">
@endsection

@section('content')
<div class="wishlist-page pt-5">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Wishlist</h1>
        <div class="indicator-box mx-auto">
            <span class="indicator-item">✅ x1 In Collection</span>
            <span class="indicator-item">⭐ Special</span>
            <span class="indicator-item">🎀 Secret</span>
            <span class="indicator-item">👑 Super Secret</span>
        </div>

        <!-- Filters & Sort -->
        <form method="GET" action="{{ route('wishlist') }}" class="filters-row mb-4">
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
            <select name="sort" class="form-select">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z–A</option>
                <option value="date_new" {{ request('sort') == 'date_new' ? 'selected' : '' }}>Newest First</option>
                <option value="date_old" {{ request('sort') == 'date_old' ? 'selected' : '' }}>Oldest First</option>
            </select>
            <div class="search-input">
                <input type="search" name="search" class="form-control" placeholder="Search by name…" value="{{ request('search') }}">
            </div>
            <button class="btn btn-primary">Filter</button>
        </form>
        <p class="text-muted text-center mt-2 small">Rarity Levels: Common (Standard Edition), Rare (Special Edition), Very Rare (Secret Edition), Rarest (Super Secret Edition)</p>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="wishlistTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist" type="button" role="tab" aria-controls="wishlist" aria-selected="true">My Wishlist</button>
            </li>
            @if($wishlist->isNotEmpty())
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="suggested-tab" data-bs-toggle="tab" data-bs-target="#suggested" type="button" role="tab" aria-controls="suggested" aria-selected="false">Suggested For You</button>
            </li>
            @endif
        </ul>

        <div class="tab-content" id="wishlistTabsContent">
            <!-- Wishlist Tab -->
            <div class="tab-pane fade show active" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                <div class="row g-4 mb-4 mt-4">
                    @forelse($wishlist as $item)
                        @php
                            $fig = $item->figurine;
                            $emojiMap = ['Special' => '⭐', 'Secret' => '🎀', 'Super Secret' => '👑'];
                            $emoji = $fig ? ($emojiMap[$fig->edition] ?? '') : '';
                            $qty = $fig ? ($userCollectionData[$fig->id] ?? 0) : 0;
                        @endphp
                        <div class="col-md-4">
                            <div class="card h-100 shadow-sm position-relative">
                                @if($qty > 0)
                                <span class="emoji-badge qty-badge position-absolute top-0 start-0 m-2" style="color:red;font-weight:bold;">
                                    {{ $qty > 1 ? "×{$qty}" : '✅' }}
                                </span>
                                @endif
                                @if($emoji)
                                <span class="emoji-badge position-absolute top-0 end-0 m-2">{{ $emoji }}</span>
                                @endif
                                <img src="{{ asset($fig->image_url) }}" alt="{{ $fig->name }}" class="card-img-top">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $fig->name }}</h5>
                                    <div class="card-details px-2">
                                        <div class="category"><strong>Category:</strong> {{ $fig->category }}</div>
                                        <div class="series"><strong>Series:</strong> {{ $fig->series }}</div>
                                        <div class="edition"><strong>Edition:</strong> {{ $fig->edition }}</div>
                                        <div class="rarity"><strong>Rarity:</strong> {{ $fig->rarity }}</div>
                                        <div class="release-date"><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($fig->release_date)->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center px-3">
                                    <button class="btn btn-outline-success btn-sm add-to-collection"
                                            data-id="{{ $fig->id }}"
                                            data-name="{{ $fig->name }}"
                                            data-series="{{ $fig->series }}"
                                            data-edition="{{ $fig->edition }}">
                                        + Add to Collection
                                    </button>
                                    <div class="heart-container" title="Remove from Wishlist">
                                        <input type="checkbox" class="checkbox wishlist-toggle" id="heart-{{ $fig->id }}" data-id="{{ $fig->id }}" checked>
                                        <div class="svg-container">
                                            <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"/>
                                            </svg>
                                            <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"/>
                                            </svg>
                                            <svg class="svg-celebrate" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                                                <polygon points="10,10 20,20"/>
                                                <polygon points="10,50 20,50"/>
                                                <polygon points="20,80 30,70"/>
                                                <polygon points="90,10 80,20"/>
                                                <polygon points="90,50 80,50"/>
                                                <polygon points="80,80 70,70"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center mt-4">Your wishlist is empty.</p>
                    @endforelse
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $wishlist->links() }}
                </div>
            </div>

            <!-- Suggested Tab -->
            @if($wishlist->isNotEmpty())
            <div class="tab-pane fade" id="suggested" role="tabpanel" aria-labelledby="suggested-tab">
                <div class="row g-4 mt-4">
                    @foreach($suggested as $fig)
                        @php
                            $emojiMap = ['Special' => '⭐', 'Secret' => '🎀', 'Super Secret' => '👑'];
                            $emoji = $emojiMap[$fig->edition] ?? '';
                            $qty = $userCollectionData[$fig->id] ?? 0;
                            $inWish = in_array($fig->id, $userWishlistIds);
                        @endphp
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm position-relative">
                                @if($qty > 0)
                                <span class="emoji-badge qty-badge position-absolute top-0 start-0 m-2" style="color:red;font-weight:bold;">
                                    {{ $qty > 1 ? "×{$qty}" : '✅' }}
                                </span>
                                @endif
                                @if($emoji)
                                <span class="emoji-badge position-absolute top-0 end-0 m-2">{{ $emoji }}</span>
                                @endif
                                <img src="{{ asset($fig->image_url) }}" alt="{{ $fig->name }}" class="card-img-top">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $fig->name }}</h5>
                                    <div class="card-details px-2">
                                        <div class="category"><strong>Category:</strong> {{ $fig->category }}</div>
                                        <div class="series"><strong>Series:</strong> {{ $fig->series }}</div>
                                        <div class="edition"><strong>Edition:</strong> {{ $fig->edition }}</div>
                                        <div class="rarity"><strong>Rarity:</strong> {{ $fig->rarity }}</div>
                                        <div class="release-date"><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($fig->release_date)->format('Y-m-d') }}</div>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="heart-container d-inline-block" title="Add to Wishlist">
                                        <input type="checkbox" class="checkbox wishlist-toggle" data-id="{{ $fig->id }}" {{ $inWish ? 'checked' : '' }}>
                                        <div class="svg-container">
                                            <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"/>
                                            </svg>
                                            <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"/>
                                            </svg>
                                            <svg class="svg-celebrate" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                                                <polygon points="10,10 20,20"/>
                                                <polygon points="10,50 20,50"/>
                                                <polygon points="20,80 30,70"/>
                                                <polygon points="90,10 80,20"/>
                                                <polygon points="90,50 80,50"/>
                                                <polygon points="80,80 70,70"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $suggested->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add to Collection Modal -->
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
                        <option>New</option><option>Used</option><option>Damaged</option><option>Miscellaneous</option>
                    </select>
                </div>
                <div class="mb-3"><label>Purchase Source</label>
                    <select name="purchase_source" class="form-select" required>
                        <option>Local Store</option><option>Online Store</option><option>Other</option>
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
<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    // Add to Collection Modal Logic
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

    // Handle Collection Form Submission
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

    // Wishlist Toggle Logic
    document.querySelectorAll('.wishlist-toggle').forEach(checkbox => {
        checkbox.addEventListener('change', async function() {
            const figurineId = this.dataset.id;
            const isChecked = this.checked;
            const isWishlistTab = this.closest('#wishlist') !== null;
            const card = this.closest('.card');

            try {
                const response = await fetch(`/wishlist/toggle/${figurineId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                });

                if (!response.ok) throw new Error('Failed to toggle wishlist');

                const data = await response.json();

                if (data.status === 'added') {
                    this.checked = true;
                    if (!isWishlistTab) {
                        // Dynamically add to Wishlist tab
                        const wishlistRow = document.querySelector('#wishlist .row');
                        const newCard = document.createElement('div');
                        newCard.className = 'col-md-4';
                        newCard.innerHTML = `
                            <div class="card h-100 shadow-sm position-relative">
                                ${card.querySelector('.qty-badge') ? card.querySelector('.qty-badge').outerHTML : ''}
                                ${card.querySelector('.emoji-badge:not(.qty-badge)') ? card.querySelector('.emoji-badge:not(.qty-badge)').outerHTML : ''}
                                <img src="${card.querySelector('img').src}" alt="${card.querySelector('.card-title').textContent}" class="card-img-top">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${card.querySelector('.card-title').textContent}</h5>
                                    <div class="card-details px-2">
                                        ${card.querySelector('.card-details').innerHTML}
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center px-3">
                                    <button class="btn btn-outline-success btn-sm add-to-collection"
                                            data-id="${figurineId}"
                                            data-name="${card.querySelector('.card-title').textContent}"
                                            data-series="${card.querySelector('.series').textContent.replace('Series: ', '')}"
                                            data-edition="${card.querySelector('.edition').textContent.replace('Edition: ', '')}">
                                        + Add to Collection
                                    </button>
                                    <div class="heart-container" title="Remove from Wishlist">
                                        <input type="checkbox" class="checkbox wishlist-toggle" id="heart-${figurineId}" data-id="${figurineId}" checked>
                                        <div class="svg-container">
                                            <svg viewBox="0 0 24 24" class="svg-outline" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Zm-3.585,18.4a2.973,2.973,0,0,1-3.83,0C4.947,16.006,2,11.87,2,8.967a4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,11,8.967a1,1,0,0,0,2,0,4.8,4.8,0,0,1,4.5-5.05A4.8,4.8,0,0,1,22,8.967C22,11.87,19.053,16.006,13.915,20.313Z"/>
                                            </svg>
                                            <svg viewBox="0 0 24 24" class="svg-filled" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17.5,1.917a6.4,6.4,0,0,0-5.5,3.3,6.4,6.4,0,0,0-5.5-3.3A6.8,6.8,0,0,0,0,8.967c0,4.547,4.786,9.513,8.8,12.88a4.974,4.974,0,0,0,6.4,0C19.214,18.48,24,13.514,24,8.967A6.8,6.8,0,0,0,17.5,1.917Z"/>
                                            </svg>
                                            <svg class="svg-celebrate" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                                                <polygon points="10,10 20,20"/>
                                                <polygon points="10,50 20,50"/>
                                                <polygon points="20,80 30,70"/>
                                                <polygon points="90,10 80,20"/>
                                                <polygon points="90,50 80,50"/>
                                                <polygon points="80,80 70,70"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        wishlistRow.insertBefore(newCard, wishlistRow.firstChild);
                        // Reattach event listeners for the new card
                        newCard.querySelector('.add-to-collection').addEventListener('click', () => {
                            document.getElementById('figId').value = figurineId;
                            document.getElementById('figName').value = card.querySelector('.card-title').textContent;
                            document.getElementById('figSeries').value = card.querySelector('.series').textContent.replace('Series: ', '');
                            document.getElementById('figEdition').value = card.querySelector('.edition').textContent.replace('Edition: ', '');
                            modal.show();
                        });
                        newCard.querySelector('.wishlist-toggle').addEventListener('change', arguments.callee);
                        alert('Figurine added to Wishlist!');
                    }
                } else if (data.status === 'removed') {
                    this.checked = false;
                    if (isWishlistTab) {
                        this.closest('.col-md-4').remove();
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                this.checked = !isChecked; // Revert state on error
            }
        });
    });
});
</script>
@endsection