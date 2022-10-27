<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\TvShow;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class TvShowController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->get('search');
        $paginate = $request->get('perPage') ?? 5;

        return Inertia::render('TvShows/Index', [
            'tvShows' => TvShow::query()
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
        $newTMDBId = $request->get('tvShowTMDBId');
        $tvShow = TvShow::where('tmdb_id', $newTMDBId)->first();
        $endPoint = config('services.tmdb.endpoint');
        $apiKey = config('services.tmdb.secret');

        if ($tvShow)
            return to_route('admin.tv-shows.index')
                ->with('flash.banner', 'TV Show already exists')
                ->with('flash.bannerStyle', 'danger');

        $tmdbTvShow = Http::get(
            $endPoint . "tv/$newTMDBId?api_key=$apiKey&language=en-US"
        );

        if ($tmdbTvShow->successful()) {
            TvShow::create([
                'tmdb_id' => $tmdbTvShow['id'],
                'name' => $tmdbTvShow['name'],
                'poster_path' => $tmdbTvShow['poster_path'],
                'created_year' => $tmdbTvShow['first_air_date'],
            ]);

            return back()
                ->with('flash.banner', 'TV Show added successfully');
        } else
            return back()
                ->with('flash.banner', 'Some error with endpoint')
                ->with('flash.bannerStyle', 'danger');
    }

    public function edit(TvShow $tvShow)
    {
        return Inertia::render('TvShows/Edit', [
            'tvShow' => $tvShow,
        ]);
    }

    public function update(TvShow $tvShow, Request $request)
    {
        $tvShow->update(
            $request->validate(
                ['name' => 'required']
            )
        );

        return back()
            ->with('flash.banner', 'TV Show updated successfully');
    }

    public function destroy(TvShow $tvShow)
    {
        $tvShow->delete();
        return back()
            ->with('flash.banner', 'TV Show deleted successfully');
    }
}
