<?php

namespace App\Http\Controllers;

use App\Models\CatchLog;
use App\Models\FishingSpot;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatchLogController extends Controller
{
    private WeatherService $weather;

    public function __construct(WeatherService $weather)
    {
        $this->weather = $weather;
    }

    public function index()
    {
        $catches = CatchLog::with('user', 'fishingSpot')->paginate(10);
        return view('catches.index', compact('catches'));
    }

    public function create()
    {
        $spots = FishingSpot::all();
        return view('catches.create', compact('spots'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'species' => 'required|string|max:255',
            'weight_kg' => 'nullable|numeric|min:0',
            'length_cm' => 'nullable|numeric|min:0',
            'depth_m' => 'nullable|numeric|min:0',
            'fishing_spot_id' => 'nullable|exists:fishing_spots,id',
            'photo_path' => 'nullable|image|max:2048', // optional upload
            'visibility' => 'required|in:public,friends,private',
            'notes' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('catch_photos', 'public');
        }

        CatchLog::create($data);

        return redirect()->route('catches.index')->with('success', 'Catch logged successfully!');
    }

    public function show(CatchLog $catch)
    {
        $catch->load(['user.followers', 'fishingSpot', 'comments.user', 'likes']);

        $weather = null;
        if ($catch->fishingSpot && $catch->fishingSpot->latitude && $catch->fishingSpot->longitude) {
            $weather = $this->weather->getCurrent($catch->fishingSpot->latitude, $catch->fishingSpot->longitude);
        }

        return view('catches.show', compact('catch', 'weather'));
    }

    public function edit(CatchLog $catch)
    {
        $this->authorize('update', $catch);
        $spots = FishingSpot::all();
        return view('catches.edit', compact('catch', 'spots'));
    }

    public function update(Request $request, CatchLog $catch)
    {
        $this->authorize('update', $catch);

        $data = $request->validate([
            'species' => 'required|string|max:255',
            'weight_kg' => 'nullable|numeric|min:0',
            'length_cm' => 'nullable|numeric|min:0',
            'depth_m' => 'nullable|numeric|min:0',
            'fishing_spot_id' => 'nullable|exists:fishing_spots,id',
            'photo_path' => 'nullable|image|max:2048',
            'visibility' => 'required|in:public,friends,private',
            'notes' => 'nullable|string',
        ]);

        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('catch_photos', 'public');
        }

        $catch->update($data);

        return redirect()->route('catches.index')->with('success', 'Catch updated successfully!');
    }

    public function destroy(CatchLog $catch)
    {
        $this->authorize('delete', $catch);

        $catch->delete();

        return redirect()->route('catches.index')->with('success', 'Catch deleted successfully!');
    }
}
