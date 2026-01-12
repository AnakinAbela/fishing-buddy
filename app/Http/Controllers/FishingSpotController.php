<?php

namespace App\Http\Controllers;

use App\Models\FishingSpot;
use App\Rules\ValidMapLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FishingSpotController extends Controller
{
    public function index()
    {
        $spots = FishingSpot::with('user')->paginate(10);

        return view('fishing_spots.index', compact('spots'));
    }

    public function create()
    {
        return view('fishing_spots.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'latitude' => ['required','numeric','between:-90,90', new ValidMapLocation],
            'longitude' => ['required','numeric','between:-180,180', new ValidMapLocation],
        ]);

        $data['user_id'] = Auth::id();

        FishingSpot::create($data);

        return redirect()->route('spots.index')->with('success', 'Fishing spot created successfully!');
    }

    public function show(FishingSpot $spot)
    {
        return view('fishing_spots.show', compact('spot'));
    }

    public function edit(FishingSpot $spot)
    {
        return view('fishing_spots.edit', compact('spot'));
    }

    public function update(Request $request, FishingSpot $spot)
    {
        $this->authorize('update', $spot);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'latitude' => ['required','numeric','between:-90,90', new ValidMapLocation],
            'longitude' => ['required','numeric','between:-180,180', new ValidMapLocation],
        ]);

        $spot->update($data);

        return redirect()->route('spots.index')->with('success', 'Fishing spot updated successfully!');
    }

    public function destroy(FishingSpot $spot)
    {
        $this->authorize('delete', $spot);

        $spot->delete();

        return redirect()->route('spots.index')->with('success', 'Fishing spot deleted successfully!');
    }
}
