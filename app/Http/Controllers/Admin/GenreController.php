<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->get('search');
        $paginate = $request->get('perPage') ?? 5;

        return Inertia::render('Genres/Index', [
            'genres' => Genre::query()
                ->when($searchTerm, function ($query, $search) {
                    $query->where('title', 'like', "%$search%");
                })
                ->paginate($paginate)
                ->withQueryString(),
            'filters' => $request->only(['search', 'perPage']),
        ]);
    }

    public function store()
    {
        $endPoint = config('services.tmdb.endpoint');
        $apiKey = config('services.tmdb.secret');

        $tmdbGenres = Http::get(
            $endPoint . "genre/movie/list?api_key=$apiKey&language=en-US"
        );

        if ($tmdbGenres->successful()) {
            $tmdbGenresData = $tmdbGenres->json();
            foreach ($tmdbGenresData['genres'] as $genre) {
                $genreExists = Genre::where('tmdb_id', $genre['id'])->first();
                if (!$genreExists) {
                    Genre::create([
                        'tmdb_id' => $genre['id'],
                        'title' => $genre['name'],
                    ]);
                }
            }
            return back()->with('flash.banner', 'Genres added successfully');
        }

        return back()
            ->with('flash.banner', 'Some error with endpoint')
            ->with('flash.bannerStyle', 'danger');
    }

    public function edit(Genre $genre)
    {
        return Inertia::render('Genres/Edit', [
            'genre' => $genre,
        ]);
    }

    public function update(Genre $genre, Request $request)
    {
        $genre->update(
            $request->validate(
                ['title' => 'required']
            )
        );

        return back()->with('flash.banner', 'Genres updated successfully');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return back()
            ->with('flash.banner', 'Genres deleted successfully')
            ->with('flash.bannerStyle', 'danger');
    }
}
