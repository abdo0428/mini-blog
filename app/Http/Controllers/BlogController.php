<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = (string) $request->query('category', '');
        $tagSlug = (string) $request->query('tag', '');

        $activeCategory = $categorySlug !== ''
            ? Category::where('slug', $categorySlug)->first()
            : null;
        $activeTag = $tagSlug !== ''
            ? Tag::where('slug', $tagSlug)->first()
            : null;

        $query = Post::with(['category','tags','author'])
            ->where('status', 'published');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($activeCategory) {
            $query->where('category_id', $activeCategory->id);
        }

        if ($activeTag) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $activeTag->id));
        }

        $page = $request->integer('page', 1);
        $shouldCache = $search === '' && !$activeCategory && !$activeTag;

        if ($shouldCache) {
            $posts = Cache::remember("blog.index.page.{$page}", 60, function () use ($query, $page) {
                return $query->latest('published_at')->paginate(9, ['*'], 'page', $page);
            });
        } else {
            $posts = $query->latest('published_at')->paginate(9);
        }

        $posts->appends($request->query());

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $siteName = Setting::get('site_name', config('app.name'));
        $siteLogo = Setting::get('site_logo');
        $contextLabel = $activeCategory
            ? 'Category: '.$activeCategory->name
            : ($activeTag
                ? 'Tag: '.$activeTag->name
                : ($search !== '' ? 'Search: '.$search : 'All posts'));

        if ($request->ajax()) {
            return response()->json([
                'html' => view('blog.partials.posts', compact('posts'))->render(),
                'breadcrumb' => view('blog.partials.breadcrumb', compact('activeCategory','activeTag','search'))->render(),
                'count' => $posts->total(),
                'context' => $contextLabel,
                'title' => $siteName.' - '.$contextLabel,
            ]);
        }

        return view('blog.index', compact(
            'posts',
            'categories',
            'tags',
            'activeCategory',
            'activeTag',
            'search',
            'siteName',
            'siteLogo',
            'contextLabel'
        ));
    }

    public function show(string $slug)
    {
        $post = Post::with(['category','tags','author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $siteName = Setting::get('site_name', config('app.name'));
        $siteLogo = Setting::get('site_logo');

        return view('blog.show', compact('post', 'siteName', 'siteLogo'));
    }

    public function category(Request $request, string $slug)
    {
        Category::where('slug', $slug)->firstOrFail();
        $request->query->set('category', $slug);

        return $this->index($request);
    }

    public function tag(Request $request, string $slug)
    {
        Tag::where('slug', $slug)->firstOrFail();
        $request->query->set('tag', $slug);

        return $this->index($request);
    }

    public function sitemap()
    {
        $posts = Post::where('status', 'published')
            ->latest('published_at')
            ->get(['slug','updated_at','published_at']);

        return response()
            ->view('blog.sitemap', compact('posts'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $content = "User-agent: *\nAllow: /\nSitemap: ".route('blog.sitemap');
        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function rss()
    {
        $posts = Post::with(['category','author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(20)
            ->get();

        return response()
            ->view('blog.rss', compact('posts'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
