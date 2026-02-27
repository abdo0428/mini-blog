<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category','tags','author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['category','tags','author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['category','tags','author'])
            ->where('status', 'published')
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts','category'));
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['category','tags','author'])
            ->where('status', 'published')
            ->whereHas('tags', fn($q) => $q->where('tags.id', $tag->id))
            ->latest('published_at')
            ->paginate(9);

        return view('blog.index', compact('posts','tag'));
    }
}