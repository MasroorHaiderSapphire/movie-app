<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TvShow;

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
}
