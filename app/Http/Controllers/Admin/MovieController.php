<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->get('search');
        $paginate = $request->get('perPage') ?? 5;

        return Inertia::render(
            'Movies/Index',
            [
                'movies' => Movie::where('title', 'like', "%$searchTerm%")
                    ->paginate($paginate)
                    ->withQueryString(),
                'filters' => $request->only(['search', 'perPage']),
            ]
        );
    }

    public function store(Request $request)
    {
        $movie = Movie::where('tmdb_id', $request->movieTMDBId)->exists();
        if ($movie) {
            return back()
                ->with('flash.banner', 'Movie already exists')
                ->with('flash.bannerStyle', 'danger');
        }

        $newTMDBId = $request->get('movieTMDBId');
        $endPoint = config('services.tmdb.endpoint');
        $apiKey = config('services.tmdb.secret');

        $tmdbTvShow = Http::get(
            $endPoint . "movie/$newTMDBId?api_key=$apiKey&language=en-US"
        );

        if ($tmdbTvShow->successful()) {
            $newMovie = $tmdbTvShow->json();

            $created_movie = Movie::create([
                'tmdb_id' => $newMovie['id'],
                'title' => $newMovie['title'],
                'runtime' => $newMovie['runtime'],
                'rating' => $newMovie['vote_average'],
                'release_date' => $newMovie['release_date'],
                'lang' => $newMovie['original_language'],
                'video_format' => 'HD',
                'is_public' => false,
                'overview' => $newMovie['overview'],
                'poster_path' => $newMovie['poster_path'],
                'backdrop_path' => $newMovie['backdrop_path']
            ]);

            $tmdb_genres = $newMovie['genres'];
            $tmdb_genres_ids = collect($tmdb_genres)->pluck('id');
            $genres = Genre::whereIn('tmdb_id', $tmdb_genres_ids)->get();
            $created_movie->genres()->attach($genres);
            return back()
                ->with('flash.banner', 'Movie added successfully');
        } else
            return back()
                ->with('flash.banner', 'Some error with endpoint')
                ->with('flash.bannerStyle', 'danger');
    }

    public function edit(Movie $movie)
    {
        return Inertia::render('Movies/Edit', [
            'movie' => $movie
        ]);
    }

    public function update(Movie $movie, Request $request)
    {
        $movie->update(
            $request->validate([
                'title' => 'required',
                'overview' => 'required',
                'poster_path' => 'required',
            ])
        );
    }
}
