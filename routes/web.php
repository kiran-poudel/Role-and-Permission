<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Permissions Routes

    Route::get('/permissions/list', [PermissionController::class, 'index'])->name('permissions.list');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/permissions/{id}/update', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{id}/delete', [PermissionController::class, 'destroy'])->name('permissions.destroy');

    //Roles Routes
    Route::get('/roles/list', [RoleController::class, 'index'])->name('roles.list');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}/update', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.destroy');

    //Articles Route

    Route::get('articles/list',[ArticleController::class,'index'])->name('articles.list');
    Route::get('articles/create',[ArticleController::class,'create'])->name('articles.create');
    Route::post('/articles/store', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::post('/articles/{id}/update', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{id}/delete', [ArticleController::class, 'destroy'])->name('articles.destroy');

    //Users Routes

    Route::get('users',[UserController::class,'index'])->name('users.list');
    Route::get('users/create',[UserController::class,'create'])->name('users.create');
    Route::post('users/store',[UserController::class,'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}/update', [userController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');
    
});

require __DIR__.'/auth.php';
