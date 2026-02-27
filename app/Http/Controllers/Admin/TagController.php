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

        return redirect()->route('admin.tags.index')->with('success','تم إنشاء التصنيف');
    }

    public function edit(Tag $Tag) { return view('admin.tags.edit', compact('Tag')); }

    public function update(Request $request, Tag $Tag)
    {
        $data = $request->validate(['name' => ['required','string','max:255']]);

        if ($data['name'] !== $Tag->name) {
            $slug = Str::slug($data['name']);
            $i = 1; $base = $slug;
            while (Tag::where('slug',$slug)->where('id','!=',$Tag->id)->exists()) $slug = $base.'-'.$i++;
            $Tag->slug = $slug;
        }

        $Tag->name = $data['name'];
        $Tag->save();

        return redirect()->route('admin.tags.index')->with('success','تم تحديث التصنيف');
    }

    public function destroy(Tag $Tag)
    {
        $Tag->delete();
        return response()->json(['ok' => true]);
    }
}