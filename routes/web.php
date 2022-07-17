<?php

use App\UI\Http\Web\Controller\Laravel\Shared\Post\PostCategoryController;
use App\UI\Http\Web\Controller\Laravel\Shared\Post\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/post/category/create', [PostCategoryController::class, 'create'])->name('post.category.create');
Route::post('/post/category/store', [PostCategoryController::class, 'store'])->name('post.category.store');

Route::get('/post', [PostController::class, 'index'])->name('post.post.index');
Route::get('/post/category/{category?}', [PostController::class, 'category'])->name('post.post.category');
Route::get('/post/create', [PostController::class, 'create'])->name('post.post.create');
Route::post('/post/store', [PostController::class, 'store'])->name('post.post.store');

Route::redirect('/', '/post');
