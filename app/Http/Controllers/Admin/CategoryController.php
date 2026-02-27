<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() { return view('admin.categories.index'); }

    public function data()
    {
        $cats = Category::latest()->get()->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'slug' => $c->slug,
            'created_at' => $c->created_at->format('Y-m-d H:i'),
        ]);

        return response()->json(['data' => $cats]);
    }

    public function create() { return view('admin.categories.create'); }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required','string','max:255']]);
        $slug = Str::slug($data['name']);
        $i = 1; $base = $slug;
        while (Category::where('slug',$slug)->exists()) $slug = $base.'-'.$i++;

        Category::create(['name'=>$data['name'],'slug'=>$slug]);

        return redirect()->route('admin.categories.index')->with('success','Category created successfully');
    }

    public function edit(Category $category) { return view('admin.categories.edit', compact('category')); }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => ['required','string','max:255']]);

        if ($data['name'] !== $category->name) {
            $slug = Str::slug($data['name']);
            $i = 1; $base = $slug;
            while (Category::where('slug',$slug)->where('id','!=',$category->id)->exists()) $slug = $base.'-'.$i++;
            $category->slug = $slug;
        }

        $category->name = $data['name'];
        $category->save();

        return redirect()->route('admin.categories.index')->with('success','Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['ok' => true]);
    }
}
