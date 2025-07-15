<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Figurine;
use App\Models\Collectible;
use Illuminate\Support\Facades\Auth;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        // 1) Build base query & apply filters
        $query = Figurine::query();
        foreach (['category', 'series', 'edition', 'rarity'] as $f) {
            if ($val = $request->input($f)) {
                $query->where($f, $val);
            }
        }

        // 2) Search by name
        if ($q = $request->input('search')) {
            $query->where('name', 'like', "%{$q}%");
        }

        // 3) Sort
        switch ($request->input('sort')) {
            case 'name_desc':
                $query->orderBy('name', 'desc'); 
                break;
            case 'date_new':
                $query->orderBy('release_date', 'desc'); 
                break;
            case 'date_old':
                $query->orderBy('release_date', 'asc'); 
                break;
            default:
                $query->orderBy('name', 'asc'); 
                break;
        }

        // 4) Paginate results
        $collectibles = $query->paginate(12)->withQueryString();

        // 5) Wishlist IDs
        $userWishlistIds = Collectible::where('user_id', Auth::id())
            ->where('status', 'wishlist')
            ->pluck('figurine_id')
            ->toArray();

        // 6) Collection quantities grouped by figurine_id
        $userCollectionData = Collectible::where('user_id', Auth::id())
            ->where('status', 'owned')
            ->get()
            ->groupBy('figurine_id')
            ->map(fn($group) => $group->sum('quantity'))
            ->toArray();

        // 7) Dropdowns
        $categories = Figurine::distinct()->pluck('category');

        $seriesOptions = $request->filled('category')
            ? Figurine::where('category', $request->category)->distinct()->pluck('series')
            : Figurine::distinct()->pluck('series');

        // Group series by category for frontend filtering
        $seriesOptionsGroupedByCategory = Figurine::all()
            ->groupBy('category')
            ->mapWithKeys(fn($group, $category) => [$category => $group->pluck('series')->unique()->values()])
            ->toArray();

        return view('catalogue', compact(
            'collectibles',
            'userWishlistIds',
            'userCollectionData',
            'categories',
            'seriesOptions',
            'seriesOptionsGroupedByCategory'
        ));
    }

    // Mark figurine as owned
    public function markAsOwned(Request $request, $figurineId)
    {
        $userId = Auth::id();

        $userCollection = Collectible::where('user_id', $userId)
            ->where('figurine_id', $figurineId)
            ->first();

        if ($userCollection) {
            $userCollection->update([
                'status' => 'owned',
                'purchase_date' => $request->purchase_date,
                'condition' => $request->condition,
                'purchase_source' => $request->purchase_source,
            ]);
            return back()->with('message', 'Marked as owned!');
        }

        Collectible::create([
            'user_id' => $userId,
            'figurine_id' => $figurineId,
            'status' => 'owned',
            'purchase_date' => $request->purchase_date,
            'condition' => $request->condition,
            'purchase_source' => $request->purchase_source,
        ]);

        return back()->with('message', 'Added as owned!');
    }
}
