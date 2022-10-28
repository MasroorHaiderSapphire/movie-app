<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Season;
use App\Models\TvShow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SeasonController extends Controller
{
    public function index(TvShow $tvShow, Request $request)
    {
        $searchTerm = $request->get('search');
        $paginate = $request->get('perPage') ?? 5;

        return Inertia::render('TvShows/Seasons/Index', [
            'seasons' => Season::query()
                ->where('tv_show_id', $tvShow->id)
                ->when($searchTerm, function ($query, $search) {
                    $query->where('name', 'like', "%$search%");
                })
                ->paginate($paginate)
                ->withQueryString(),
            'filters' => $request->only(['search', 'perPage']),
            'tvShow' => $tvShow,
        ]);
    }

    public function store(TvShow $tvShow, Request $request)
    {
        $seasonNumber = $request->get('seasonNumber');
        $season = $tvShow->seasons()->where('season_number', $seasonNumber)->exists();
        $endPoint = config('services.tmdb.endpoint');
        $apiKey = config('services.tmdb.secret');

        if ($season)
            return back()
                ->with('flash.banner', 'Season already exists')
                ->with('flash.bannerStyle', 'danger');

        $tmdbSeason = Http::get(
            $endPoint . "tv/$tvShow->tmdb_id/season/$seasonNumber?api_key=$apiKey&language=en-US"
        );

        if ($tmdbSeason->successful()) {
            Season::create([
                'tv_show_id' => $tvShow->id,
                'tmdb_id' => $tmdbSeason['id'],
                'name' => $tmdbSeason['name'],
                'poster_path' => $tmdbSeason['poster_path'],
                'season_number' => $tmdbSeason['season_number'],
            ]);

            return back()
                ->with('flash.banner', 'Season added successfully');
        } else
            return back()
                ->with('flash.banner', 'Some error with endpoint')
                ->with('flash.bannerStyle', 'danger');
    }

    public function edit()
    {
        //
    }

    public function destroy(TvShow $tvShow, Season $season)
    {
        $season->delete();

        return back()
            ->with('flash.banner', 'Season deleted successfully');
    }
}
