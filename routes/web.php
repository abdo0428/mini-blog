<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('blog.index');
Route::get('/post/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
Route::get('/sitemap.xml', [BlogController::class, 'sitemap'])->name('blog.sitemap');
Route::get('/robots.txt', [BlogController::class, 'robots'])->name('blog.robots');
Route::get('/feed', [BlogController::class, 'rss'])->name('blog.rss');

Route::middleware(['auth', 'role:admin|editor'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('posts/data', [PostController::class, 'data'])->name('posts.data');
        Route::patch('posts/{post}/toggle', [PostController::class, 'toggle'])->name('posts.toggle');
        Route::post('posts/{post}/duplicate', [PostController::class, 'duplicate'])->name('posts.duplicate');
        Route::patch('posts/{post}/autosave', [PostController::class, 'autosave'])->name('posts.autosave');
        Route::get('posts/{post}/preview', [PostController::class, 'preview'])->name('posts.preview');
        Route::resource('posts', PostController::class);
    });

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('categories/data', [CategoryController::class, 'data'])->name('categories.data');
        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::get('tags/data', [TagController::class, 'data'])->name('tags.data');
        Route::resource('tags', TagController::class)->except(['show']);

        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
