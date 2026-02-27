<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index() { return view('admin.tags.index'); }

    public function data()
    {
        $cats = Tag::latest()->get()->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'slug' => $c->slug,
            'created_at' => $c->created_at->format('Y-m-d H:i'),
        ]);

        return response()->json(['data' => $cats]);
    }

    public function create() { return view('admin.tags.create'); }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required','string','max:255']]);
        $slug = Str::slug($data['name']);
        $i = 1; $base = $slug;
        while (Tag::where('slug',$slug)->exists()) $slug = $base.'-'.$i++;

        Tag::create(['name'=>$data['name'],'slug'=>$slug]);

        return redirect()->route('admin.tags.index')->with('success','Tag created successfully');
    }

    public function edit(Tag $tag) { return view('admin.tags.edit', compact('tag')); }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate(['name' => ['required','string','max:255']]);

        if ($data['name'] !== $tag->name) {
            $slug = Str::slug($data['name']);
            $i = 1; $base = $slug;
            while (Tag::where('slug',$slug)->where('id','!=',$tag->id)->exists()) $slug = $base.'-'.$i++;
            $tag->slug = $slug;
        }

        $tag->name = $data['name'];
        $tag->save();

        return redirect()->route('admin.tags.index')->with('success','Tag updated successfully');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(['ok' => true]);
    }
}
