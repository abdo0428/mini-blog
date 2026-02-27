<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.index', compact('categories'));
    }

    public function data()
    {
        $query = Post::with(['category','author','tags'])->latest();
        if (!$this->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $posts = $query->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
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
            'cover_image' => ['nullable','image','max:2048','dimensions:max_width=2400,max_height=2400'],
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

        Cache::flush();

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully');
    }

    public function edit(Post $post)
    {
        $this->ensureCanManage($post);
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $selectedTags = $post->tags()->pluck('tags.id')->toArray();

        return view('admin.posts.edit', compact('post','categories','tags','selectedTags'));
    }

    public function update(Request $request, Post $post)
    {
        $this->ensureCanManage($post);
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:255'],
            'body' => ['required','string'],
            'category_id' => ['nullable','exists:categories,id'],
            'tags' => ['nullable','array'],
            'tags.*' => ['exists:tags,id'],
            'status' => ['required','in:draft,published'],
            'slug' => ['nullable','string','max:255'],
            'cover_image' => ['nullable','image','max:2048','dimensions:max_width=2400,max_height=2400'],
            'remove_cover' => ['nullable','boolean'],
        ]);
        if (!empty($data['slug']) && $data['slug'] !== $post->slug) {
            $data['slug'] = Post::makeUniqueSlug($data['slug']);
        } else {
            unset($data['slug']);
        }

        if ($request->boolean('remove_cover') && $post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
            $data['cover_image'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) Storage::disk('public')->delete($post->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Manage published_at
        if ($data['status'] === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        }
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $post->update($data);
        $post->tags()->sync($data['tags'] ?? []);

        Cache::flush();

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $this->ensureCanManage($post);
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->tags()->detach();
        $post->delete();

        Cache::flush();

        return response()->json(['ok' => true]);
    }

    public function toggle(Post $post)
    {
        $this->ensureCanManage($post);

        if ($post->status === 'published') {
            $post->status = 'draft';
            $post->published_at = null;
        } else {
            $post->status = 'published';
            $post->published_at = now();
        }

        $post->save();
        Cache::flush();

        return response()->json([
            'ok' => true,
            'status' => $post->status,
            'published_at' => optional($post->published_at)->format('Y-m-d H:i') ?? '-',
        ]);
    }

    public function duplicate(Post $post)
    {
        $this->ensureCanManage($post);

        $newTitle = $post->title.' (Copy)';
        $copy = $post->replicate(['slug','published_at','created_at','updated_at','cover_image']);
        $copy->title = $newTitle;
        $copy->slug = Post::makeUniqueSlug($newTitle);
        $copy->status = 'draft';
        $copy->published_at = null;
        $copy->user_id = auth()->id();
        $copy->cover_image = null;
        if ($post->cover_image && Storage::disk('public')->exists($post->cover_image)) {
            $ext = pathinfo($post->cover_image, PATHINFO_EXTENSION);
            $newPath = 'covers/'.Str::random(40).($ext ? '.'.$ext : '');
            Storage::disk('public')->copy($post->cover_image, $newPath);
            $copy->cover_image = $newPath;
        }
        $copy->save();

        $copy->tags()->sync($post->tags()->pluck('tags.id')->toArray());

        Cache::flush();

        return response()->json(['ok' => true, 'id' => $copy->id]);
    }

    public function preview(Post $post)
    {
        $this->ensureCanManage($post);

        $post->load(['category','tags','author']);
        $siteName = \App\Models\Setting::get('site_name', config('app.name'));
        $siteLogo = \App\Models\Setting::get('site_logo');

        return view('blog.show', compact('post', 'siteName', 'siteLogo'));
    }

    public function autosave(Request $request, Post $post)
    {
        $this->ensureCanManage($post);

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'excerpt' => ['nullable','string','max:255'],
            'body' => ['required','string'],
            'category_id' => ['nullable','exists:categories,id'],
            'tags' => ['nullable','array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $post->update($data);
        $post->tags()->sync($data['tags'] ?? []);

        return response()->json([
            'ok' => true,
            'saved_at' => now()->format('Y-m-d H:i'),
        ]);
    }

    private function isAdmin(): bool
    {
        return auth()->user()?->hasRole('admin') === true;
    }

    private function ensureCanManage(Post $post): void
    {
        if ($this->isAdmin()) {
            return;
        }

        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
