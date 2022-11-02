<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->get('search');
        $paginate = $request->get('perPage') ?? 5;

        return Inertia::render(
            'Tags/Index',
            [
                'tags' => Tag::where('tag_name', 'like', "%$searchTerm%")
                    ->paginate($paginate)
                    ->withQueryString(),
                'filters' => $request->only(['search', 'perPage']),
            ]
        );
    }

    public function create()
    {
        return Inertia::render('Tags/Create');
    }

    public function store(Request $request)
    {
        $tagName = $request->get('tagName');
        $slug = Str::slug($tagName);

        Tag::create([
            'tag_name' => $tagName,
            'slug' => $slug,
        ]);

        return to_route('admin.tags.index')
            ->with('flash.banner', 'Tag created');
    }

    public function edit(Tag $tag)
    {
        return Inertia::render('Tags/Edit', [
            'tag' => $tag,
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $newTagName = $request->get('tagName');
        $newSlug = Str::slug($request->get('tagName'));

        $tag->update([
            'tag_name' => $newTagName,
            'slug' => Str::slug($newSlug),
        ]);

        return back()
            ->with('flash.banner', 'Tag updated');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return to_route('admin.tags.index')
            ->with('flash.banner', 'Tag deleted')
            ->with('flash.bannerStyle', 'danger');
    }
}
