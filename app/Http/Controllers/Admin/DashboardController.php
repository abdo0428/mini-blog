<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

class DashboardController extends Controller
{
    public function index()
    {
        $postQuery = Post::query();
        if (!auth()->user()?->hasRole('admin')) {
            $postQuery->where('user_id', auth()->id());
        }

        $draftCount = (clone $postQuery)->where('status', 'draft')->count();
        $publishedCount = (clone $postQuery)->where('status', 'published')->count();
        $categoryCount = Category::count();
        $tagCount = Tag::count();

        $latestPosts = $postQuery->with(['category','author'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'draftCount',
            'publishedCount',
            'categoryCount',
            'tagCount',
            'latestPosts'
        ));
    }
}
