<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cast;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class CastController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->get('search');
        $paginate = $request->get('perPage') ?? 5;

        return Inertia::render('Casts/Index', [
            'casts' => Cast::query()
                ->when($searchTerm, function ($query, $search) {
                    $query->where('name', 'like', "%$search%");
                })
                ->paginate($paginate)
                ->withQueryString(),
            'filters' => $request->only(['search', 'perPage']),
        ]);
    }

    public function store(Request $request)
    {
        $newTMDBId = $request->get('castTMDBId');
        $cast = Cast::where('tmdb_id', $newTMDBId)->first();
        $endPoint = config('services.tmdb.endpoint');
        $apiKey = config('services.tmdb.secret');

        if ($cast)
            return to_route('admin.casts.index')
                ->with('flash.banner', 'Cast already exists')
                ->with('flash.bannerStyle', 'danger');

        $tmdbCast = Http::get(
            $endPoint . "person/$newTMDBId?api_key=$apiKey&language=en-US"
        );

        if ($tmdbCast->successful()) {
            Cast::create([
                'tmdb_id' => $tmdbCast['id'],
                'name' => $tmdbCast['name'],
                'slug' => Str::slug($tmdbCast['name']),
                'poster_path' => $tmdbCast['profile_path'],
            ]);

            return back()
                ->with('flash.banner', 'Cast added successfully');
        } else
            return back()
                ->with('flash.banner', 'Some error with endpoint')
                ->with('flash.bannerStyle', 'danger');
    }

    public function edit(Cast $cast)
    {
        return Inertia::render('Casts/Edit', [
            'cast' => $cast
        ]);
    }

    public function update(Cast $cast, Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'poster_path' => 'required'
        ]);

        $cast->update($validated);

        return back()
            ->with('flash.banner', 'Cast updated successfully');
    }

    public function destroy(Cast $cast)
    {
        $cast->delete();

        return back()
            ->with('flash.banner', 'Cast deleted successfully')
            ->with('flash.bannerStyle', 'danger');
    }
}
