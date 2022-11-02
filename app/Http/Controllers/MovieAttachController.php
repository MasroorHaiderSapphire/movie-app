<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use Inertia\Inertia;
use App\Models\Movie;
use App\Models\Tag;
use App\Models\TrailerUrl;
use Illuminate\Http\Request;

class MovieAttachController extends Controller
{
    public function index(Movie $movie)
    {
        return Inertia::render('Movies/Attach', [
            'movie' => $movie,
            'trailers' => $movie->trailers,
            'casts' => Cast::all('id', 'name'),
            'tags' => Tag::all('id', 'tag_name'),
            'movieCast' => $movie->casts,
            'movieTags' => $movie->tags,
        ]);
    }

    public function addTrailer(Movie $movie, Request $request)
    {
        $movie->trailers()->create([
            'name' => $request->name,
            'embed_html' => $request->embed_html,
        ]);

        return back()
            ->with('flash.banner', 'Trailer added successfully');
    }

    public function deleteTrailer(TrailerUrl $trailerUrl)
    {
        $trailerUrl->delete();
        return back()
            ->with('flash.banner', 'Trailer deleted successfully');
    }

    public function addCast(Movie $movie, Request $request)
    {
        $casts = $request->casts;
        $castIds = collect($casts)->pluck('id');
        $movie->casts()->sync($castIds);
        return back()
            ->with('flash.banner', 'Cast attached successfully');
    }

    public function addTags(Movie $movie, Request $request)
    {
        $tags = $request->tags;
        $tagsIds = collect($tags)->pluck('id');
        $movie->tags()->sync($tagsIds);
        return back()
            ->with('flash.banner', 'Tags added successfully');
    }
}
