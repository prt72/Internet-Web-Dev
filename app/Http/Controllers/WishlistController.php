<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Figurine;
use App\Models\Collectible;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1) Base wishlist query
        $query = Collectible::with('figurine')
                ->where('user_id', $user->id)
                ->where('status', 'wishlist');

        // 2) Filters
        foreach (['series', 'edition', 'rarity'] as $f) {
            if ($request->filled($f)) {
                $query->whereHas('figurine', fn($q) => $q->where($f, $request->$f));
            }
        }

        if ($request->filled('search')) {
            $query->whereHas('figurine', fn($q) =>
                $q->where('name', 'like', '%'.$request->search.'%')
            );
        }

        // 3) Sort
        if ($request->sort === 'name_asc') {
            $query->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                  ->orderBy('figurines.name', 'asc')
                  ->select('user_collections.*');
        } elseif ($request->sort === 'name_desc') {
            $query->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                  ->orderBy('figurines.name', 'desc')
                  ->select('user_collections.*');
        } elseif ($request->sort === 'date_new') {
            $query->orderBy('created_at', 'desc');
        } elseif ($request->sort === 'date_old') {
            $query->orderBy('created_at', 'asc');
        }

        // 4) Paginate
        $wishlist = $query->paginate(12)->withQueryString();

        // 5) Other data for view
        $userWishlistIds = Collectible::where('user_id', $user->id)
                                      ->where('status', 'wishlist')
                                      ->pluck('figurine_id')
                                      ->toArray();
        $userSeries = Figurine::distinct()->pluck('series');
        $userEditions = Figurine::distinct()->pluck('edition');
        $userRarities = collect(['Common', 'Rare', 'Very Rare', 'Rarest']);
        $userCollectionData = Collectible::where('user_id', $user->id)
                                         ->where('status', 'owned')
                                         ->get()
                                         ->groupBy('figurine_id')
                                         ->map(fn($g) => $g->sum('quantity'))
                                         ->toArray();

        // 6) Suggestions
        $excluded = $wishlist->pluck('figurine_id')
                             ->merge(Collectible::where('user_id', $user->id)->pluck('figurine_id'))
                             ->unique();
        $suggested = Figurine::whereNotIn('id', $excluded)
                             ->inRandomOrder()
                             ->paginate(6, ['*'], 'suggested_page');

        // 7) Categories and series options
        $categories = Figurine::distinct()->pluck('category');
        $seriesOptions = $request->filled('category')
                           ? Figurine::where('category', $request->category)->distinct()->pluck('series')
                           : Figurine::distinct()->pluck('series');

        return view('wishlist', compact(
            'wishlist',
            'suggested',
            'userSeries',
            'userEditions',
            'userRarities',
            'userWishlistIds',
            'userCollectionData',
            'categories',
            'seriesOptions'
        ));
    }

    public function toggleWishlist($figurineId)
    {
        $userId = Auth::id();

        $existing = Collectible::where('user_id', $userId)
                               ->where('figurine_id', $figurineId)
                               ->where('status', 'wishlist')
                               ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        $figurine = Figurine::findOrFail($figurineId);
        Collectible::create([
            'user_id' => $userId,
            'figurine_id' => $figurineId,
            'name' => $figurine->name,
            'status' => 'wishlist',
        ]);

        return response()->json(['status' => 'added']);
    }
}