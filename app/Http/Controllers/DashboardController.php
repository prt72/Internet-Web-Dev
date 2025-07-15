<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collectible;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Collection Stats
        $collectionStats = [
            'total_owned' => Collectible::where('user_id', $userId)
                                       ->where('status', 'owned')
                                       ->sum('quantity'),
            'duplicates' => Collectible::where('user_id', $userId)
                                       ->where('status', 'owned')
                                       ->where('quantity', '>', 1)
                                       ->count(),
        ];

        // Wishlist Stats
        $wishlistStats = [
            'total_wishlist' => Collectible::where('user_id', $userId)
                                           ->where('status', 'wishlist')
                                           ->count(),
        ];

        // Chart Data
        $chartData = [
            'bar_labels' => ['Total Owned', 'Duplicates'],
            'bar_values' => [$collectionStats['total_owned'], $collectionStats['duplicates']],
            'pie_labels' => ['Duplicates', 'Unique'],
            'pie_values' => [
                $collectionStats['duplicates'],
                max($collectionStats['total_owned'] - $collectionStats['duplicates'], 0)
            ],
            'pie_colors' => ['#ff6b6b', '#4ecdc4'],
        ];

        // Collection Over Time
        $collectionOverTime = DB::table('user_collections')
            ->where('user_id', $userId)
            ->where('status', 'owned')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(quantity) as count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Edition Breakdown
        $editionBreakdown = DB::table('user_collections')
            ->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
            ->where('user_collections.user_id', $userId)
            ->where('user_collections.status', 'owned')
            ->select('figurines.edition', DB::raw('COUNT(*) as count'))
            ->groupBy('figurines.edition')
            ->get();

        // Recent Items
        $owned = Collectible::with('figurine')
                            ->where('user_id', $userId)
                            ->where('status', 'owned')
                            ->latest()
                            ->take(3)
                            ->get();

        $wishlist = Collectible::with('figurine')
                               ->where('user_id', $userId)
                               ->where('status', 'wishlist')
                               ->latest()
                               ->take(3)
                               ->get();

        // Filter Options
        $availableSeries = Collectible::join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                                      ->where('user_collections.user_id', $userId)
                                      ->where('user_collections.status', 'owned')
                                      ->distinct()
                                      ->pluck('figurines.series')
                                      ->filter()
                                      ->values();

        $availableCategories = Collectible::join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                                          ->where('user_collections.user_id', $userId)
                                          ->where('user_collections.status', 'owned')
                                          ->distinct()
                                          ->pluck('figurines.category')
                                          ->filter()
                                          ->values();

        $availableMonths = DB::table('user_collections')
            ->where('user_id', $userId)
            ->where('status', 'owned')
            ->select(DB::raw('DISTINCT DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->filter()
            ->values();

        return view('dashboard', compact(
            'collectionStats', 'wishlistStats', 'chartData', 'owned', 'wishlist',
            'availableSeries', 'availableCategories', 'availableMonths', 
            'collectionOverTime', 'editionBreakdown'
        ));
    }

    public function getSeriesCharts(Request $request)
    {
        try {
            $userId = auth()->id();
            $filters = $request->only(['series', 'rarity', 'category', 'date']);

            $collectionQuery = DB::table('user_collections')
                ->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                ->where('user_collections.user_id', $userId)
                ->where('user_collections.status', 'owned');

            foreach (['series', 'rarity', 'category'] as $field) {
                if (!empty($filters[$field])) {
                    $collectionQuery->where("figurines.$field", $filters[$field]);
                }
            }

            if (!empty($filters['date'])) {
                $collectionQuery->whereRaw('DATE_FORMAT(user_collections.created_at, "%Y-%m") = ?', [$filters['date']]);
            }

            $collection = $collectionQuery->get();

            // Rarity distribution
            $rarityCount = collect($collection)->groupBy('rarity')->map->count()->toArray();

            // Wishlist data
            $wishlistQuery = Collectible::where('user_id', $userId)
                ->where('status', 'wishlist')
                ->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id');

            foreach (['series', 'rarity', 'category'] as $field) {
                if (!empty($filters[$field])) {
                    $wishlistQuery->where("figurines.$field", $filters[$field]);
                }
            }

            if (!empty($filters['date'])) {
                $wishlistQuery->whereRaw('DATE_FORMAT(user_collections.created_at, "%Y-%m") = ?', [$filters['date']]);
            }

            $wishlistCount = $wishlistQuery->count();

            // Top series
            $topSeries = DB::table('user_collections')
                ->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                ->where('user_collections.user_id', $userId)
                ->where('user_collections.status', 'owned');

            foreach (['series', 'rarity', 'category'] as $field) {
                if (!empty($filters[$field])) {
                    $topSeries->where("figurines.$field", $filters[$field]);
                }
            }

            if (!empty($filters['date'])) {
                $topSeries->whereRaw('DATE_FORMAT(user_collections.created_at, "%Y-%m") = ?', [$filters['date']]);
            }

            $topSeries = $topSeries->select('figurines.series', DB::raw('SUM(user_collections.quantity) as total'))
                ->groupBy('figurines.series')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();

            // Category distribution
            $categories = DB::table('user_collections')
                ->join('figurines', 'user_collections.figurine_id', '=', 'figurines.id')
                ->where('user_collections.user_id', $userId)
                ->where('user_collections.status', 'owned');

            foreach (['series', 'rarity', 'category'] as $field) {
                if (!empty($filters[$field])) {
                    $categories->where("figurines.$field", $filters[$field]);
                }
            }

            if (!empty($filters['date'])) {
                $categories->whereRaw('DATE_FORMAT(user_collections.created_at, "%Y-%m") = ?', [$filters['date']]);
            }

            $categories = $categories->select('figurines.category', DB::raw('COUNT(*) as count'))
                ->groupBy('figurines.category')
                ->get();

            return response()->json([
                'rarity' => [
                    'labels' => array_keys($rarityCount),
                    'values' => array_values($rarityCount),
                ],
                'breakdown' => [
                    'owned' => $collection->sum('quantity'),
                    'total_figurines' => $collection->count(),
                    'unique_figurines' => $collection->where('quantity', 1)->count(),
                    'figurines_with_duplicates' => $collection->where('quantity', '>', 1)->count(),
                    'total_duplicate_items' => $collection->sum(function($item) {
                        return max($item->quantity - 1, 0);
                    }),
                    'wishlist' => $wishlistCount,
                ],
                'topSeries' => [
                    'labels' => $topSeries->pluck('series')->toArray(),
                    'values' => $topSeries->pluck('total')->toArray(),
                ],
                'categories' => [
                    'labels' => $categories->pluck('category')->toArray(),
                    'values' => $categories->pluck('count')->toArray(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}