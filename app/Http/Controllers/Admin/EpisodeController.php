<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Season;
use App\Models\TvShow;
use App\Models\Episode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class EpisodeController extends Controller
{
    public function index(TvShow $tvShow, Season $season, Request $request)
    {
        $searchTerm = $request->get('search');
        $paginate   = $request->get('perPage') ?? 5;
        $filters    = $request->only(['search', 'perPage']);
        $episodes   = Episode::query()
            ->where('season_id', $season->id)
            ->when($searchTerm, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->paginate($paginate)
            ->withQueryString();

        return Inertia::render(
            'TvShows/Seasons/Episodes/Index',
            [
                'episodes' => $episodes,
                'filters'  => $filters,
                'tvShow'   => $tvShow,
                'season'   => $season,
            ]
        );
    }

    public function store(TvShow $tvShow, Season $season, Request $request)
    {
        $episode = $season->episodes()->where('episode_number', $request->episodeNumber)->exists();

        if ($episode)
            return back()
                ->with('flash.banner', 'Episode already exists')
                ->with('flash.bannerStyle', 'danger');

        $newEpisodeNumber = $request->episodeNumber;
        $seasonNumber     = $season->season_number;
        $endPoint         = config('services.tmdb.endpoint');
        $apiKey           = config('services.tmdb.secret');

        $tmdbEpisode = Http::get(
            $endPoint . "tv/$tvShow->tmdb_id/season/$seasonNumber/episode/$newEpisodeNumber?api_key=$apiKey&language=en-US"
        );

        if ($tmdbEpisode->successful()) {
            Episode::create([
                'season_id' => $season->id,
                'tmdb_id' => $tmdbEpisode['id'],
                'name' => $tmdbEpisode['name'],
                'slug' => Str::slug($tmdbEpisode['name']),
                'episode_number' => $tmdbEpisode['episode_number'],
                'overview' => $tmdbEpisode['overview'],
            ]);

            return back()
                ->with('flash.banner', 'Episode successfully added');
        }

        return back()
            ->with('flash.banner', 'Some error with endpoint')
            ->with('flash.bannerStyle', 'danger');
    }

    public function edit(TvShow $tvShow, Season $season, Episode $episode)
    {
        return Inertia::render('TvShows/Seasons/Episodes/Edit', [
            'tvShow' => $tvShow,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    public function update(TvShow $tvShow, Season $season, Episode $episode, Request $request)
    {
        $episode->update(
            $request->validate(
                [
                    'name' => 'required',
                    'overview' => 'required',
                    'is_public' => 'required',
                ]
            )
        );

        return back()
            ->with('flash.banner', 'Episode updated successfully');
    }

    public function destroy(TvShow $tvShow, Season $season, Episode $episode)
    {
        $episode->delete();
        return back()
            ->with('flash.banner', 'Episode deleted successfully');
    }
}
