<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collectible;
use App\Models\Figurine;
use App\Models\WishlistItem;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $tab = $request->input('tab', 'owned'); // Default to 'owned'

        $query = Collectible::with('figurine')->where('user_id', $userId)
                             ->where('status', 'owned');

        if ($tab === 'duplicates') {
            $query->where('quantity', '>', 1);
        } else {
            $query->where('quantity', '>=', 1);
        }

        // Apply filters
        if ($request->filled('series')) {
            $query->whereHas('figurine', fn($q) => $q->where('series', $request->series));
        }
        if ($request->filled('edition')) {
            $query->whereHas('figurine', fn($q) => $q->where('edition', $request->edition));
        }
        if ($request->filled('rarity')) {
            $query->whereHas('figurine', fn($q) => $q->where('rarity', $request->rarity));
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('purchase_source')) {
            $query->where('purchase_source', $request->purchase_source);
        }
        if ($request->filled('search')) {
            $query->whereHas('figurine', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%'));
        }

        // Sorting
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'release_date_new':
                $query->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                      ->orderBy('figurines.release_date', 'desc');
                break;
            case 'release_date_old':
                $query->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                      ->orderBy('figurines.release_date', 'asc');
                break;
            case 'purchase_date_new':
                $query->orderBy('purchase_date', 'desc');
                break;
            case 'purchase_date_old':
                $query->orderBy('purchase_date', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc'); // default sorting
        }

        // Paginate results - Changed to 12 per page
        $items = $query->paginate(12);

        // Attach duplicates_info if tab is 'duplicates'
        if ($tab === 'duplicates') {
            foreach ($items as $item) {
                $item->duplicates_info = $item->duplicates_info ?? [];
            }
        }

        // Supporting data
        $userWishlistIds = WishlistItem::where('user_id', $userId)
                                        ->pluck('figurine_id')
                                        ->toArray();

        $userCollectionData = Collectible::where('user_id', $userId)
                                         ->pluck('quantity', 'figurine_id')
                                         ->toArray();

        $seriesOptions = Figurine::distinct()->pluck('series')->filter();
        $categories = Figurine::distinct()->pluck('category')->filter();

        // Generate the collection URL
        $collectionUrl = route('collection', ['username' => Auth::user()->username]);

        // Generate QR code
        $qrCode = QrCode::size(200)->generate($collectionUrl);

        return view('collection', compact(
            'items',
            'tab',
            'userWishlistIds',
            'userCollectionData',
            'seriesOptions',
            'categories',
            'qrCode'
        ));
    }

    public function addFull(Request $req)
    {
        $data = $req->validate([
            'figurine_id'     => 'required|exists:figurines,id',
            'purchase_date'   => 'required|date',
            'condition'       => 'required|string',
            'purchase_source' => 'required|string',
            'comment'         => 'nullable|string',
        ]);

        $fig = Figurine::findOrFail($data['figurine_id']);
        $userId = Auth::id();

        $owned = Collectible::where('user_id', $userId)
                            ->where('figurine_id', $fig->id)
                            ->where('status', 'owned')
                            ->first();

        if ($owned) {
            $owned->quantity++;
            $info = $owned->duplicates_info;
            $info[] = [
                'purchase_date'   => $req->purchase_date,
                'condition'       => $req->condition,
                'comment'         => $req->comment,
                'purchase_source' => $req->purchase_source,
            ];
            $owned->update(['quantity' => $owned->quantity, 'duplicates_info' => $info]);
        } else {
            Collectible::create([
                'user_id'         => $userId,
                'figurine_id'     => $fig->id,
                'name'            => $fig->name,
                'image'           => $fig->image_url,
                'series'          => $fig->series,
                'edition'         => $fig->edition,
                'rarity'          => $fig->rarity,
                'purchase_date'   => $data['purchase_date'],
                'condition'       => $data['condition'],
                'comment'         => $data['comment'] ?? null,
                'purchase_source' => $data['purchase_source'],
                'quantity'        => 1,
                'duplicates_info' => [],
                'status'          => 'owned',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $req, $id)
    {
        $data = $req->validate([
            'purchase_date'   => 'required|date',
            'condition'       => 'required|string',
            'comment'         => 'nullable|string',
            'purchase_source' => 'required|string',
        ]);

        $item = Collectible::where('user_id', Auth::id())->findOrFail($id);
        $item->update($data);

        return response()->json(['success' => true]);
    }

    public function deleteOne($id)
    {
        $item = Collectible::where('user_id', Auth::id())->findOrFail($id);
        if ($item->quantity > 1) {
            $item->quantity -= 1;
            $item->save();
        } else {
            $item->delete();
        }

        return response()->json(['success' => true]);
    }

    // Note: toggleWishlist is now handled in WishlistController per web.php
}