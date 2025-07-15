<?php

namespace App\Http\Controllers;

use App\Models\Figurine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FigurineController extends Controller
{
    // Public Catalogue
    public function index()
    {
        $figurines = Figurine::latest()->paginate(10);
        return view('figurines.catalogue', compact('figurines'));
        $figurines = auth()->user()->figurines()->latest()->paginate(10);
        return view('figurines.index', compact('figurines'));
    }

    // User Dashboard (Private)
    public function dashboard()
    {
        $figurines = auth()->user()->figurines()->latest()->paginate(10);
        return view('dashboard', compact('figurines'));
    }

    public function create()
    {
        return view('figurines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'series' => 'required|string',
            'edition' => 'required|string',
            'comment' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $figurine = new Figurine();
        $figurine->fill($validated);
        $figurine->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('figurines', 'public');
            $figurine->image = $path;
        }

        $figurine->save();

        return redirect()->route('figurines.index')->with('success', 'Figurine added!');
    }

    public function edit(Figurine $figurine)
    {
        $this->authorize('update', $figurine);
        return view('figurines.edit', compact('figurine'));
    }

    public function update(Request $request, Figurine $figurine)
    {
        $this->authorize('update', $figurine);

        $validated = $request->validate([
            'name' => 'required|string',
            'series' => 'required|string',
            'edition' => 'required|string',
            'comment' => 'nullable|string',
            'purchase_date' => 'nullable|date',
            'condition' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($figurine->image) {
                Storage::delete($figurine->image);
            }
            $path = $request->file('image')->store('figurines', 'public');
            $validated['image'] = $path;
        }

        $figurine->update($validated);

        return redirect()->route('figurines.index')->with('success', 'Figurine updated!');
    }

    public function destroy(Figurine $figurine)
    {
        $this->authorize('delete', $figurine);
        $figurine->delete();
        return redirect()->back()->with('success', 'Figurine deleted!');
    }
}