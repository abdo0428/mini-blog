<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index');
    }

    public function data()
    {
        $posts = Post::with(['category','author','tags'])
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'category' => $p->category?->name ?? '-',
                    'status' => $p->status,
                    'author' => $p->author?->name ?? '-',
                    'published_at' => optional($p->published_at)->format('Y-m-d H:i') ?? '-',
                    'created_at' => $p->created_at->format('Y-m-d H:i'),
                ];
            });

        return response()->json(['data' => $posts]);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.posts.create', compact('categories','tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:255'],
            'body' => ['required','string'],
            'category_id' => ['nullable','exists:categories,id'],
            'tags' => ['nullable','array'],
            'tags.*' => ['exists:tags,id'],
            'status' => ['required','in:draft,published'],
            'cover_image' => ['nullable','image','max:2048'],
        ]);

        $data['user_id'] = auth()->id();
        $data['slug'] = Post::makeUniqueSlug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $post = Post::create($data);
        $post->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.posts.index')->with('success', 'تم إنشاء المقال بنجاح');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedTags = $post->tags()->pluck('tags.id')->toArray();

        return view('admin.posts.edit', compact('post','categories','tags','selectedTags'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:255'],
            'body' => ['required','string'],
            'category_id' => ['nullable','exists:categories,id'],
            'tags' => ['nullable','array'],
            'tags.*' => ['exists:tags,id'],
            'status' => ['required','in:draft,published'],
            'cover_image' => ['nullable','image','max:2048'],
            'remove_cover' => ['nullable','boolean'],
        ]);

        // لو تغيّر العنوان: حدّث slug بشكل فريد
        if ($data['title'] !== $post->title) {
            $data['slug'] = Post::makeUniqueSlug($data['title']);
        }

        if ($request->boolean('remove_cover') && $post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
            $data['cover_image'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // إدارة published_at
        if ($data['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        }
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $post->update($data);
        $post->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.posts.index')->with('success', 'تم تحديث المقال بنجاح');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->tags()->detach();
        $post->delete();

        return response()->json(['ok' => true]);
    }
}