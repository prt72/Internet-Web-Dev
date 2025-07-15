<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        // Dummy data for nearby stores
        $stores = [
            [
                'name' => 'PopMart Store #001',
                'address' => '123 Collectible Lane, Tokyo',
                'distance' => '1.2km',
                'hours' => '10:00 AM - 9:00 PM',
                'phone' => '+81 3-1234-5678',
                'website' => 'https://popmart.jp'
            ],
            [
                'name' => 'Collectibles Hub',
                'address' => '456 Figurine Avenue, Seoul',
                'distance' => '2.8km',
                'hours' => '10:30 AM - 10:00 PM',
                'phone' => '+82 2-9876-5432',
                'website' => 'https://collectibles.kr'
            ],
            // Add more dummy stores as needed
        ];

        return view('location', [
            'stores' => $stores
        ]);
    }
}