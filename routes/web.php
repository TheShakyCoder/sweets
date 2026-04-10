<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FacebookFeedController;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return Inertia::render('Home/Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/news-updates', [PostController::class, 'index'])->name('posts.index');
Route::get('/news-updates/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/api/facebook-feed', [FacebookFeedController::class, 'index'])->name('facebook.feed');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    //  INTERNAL ROUTES
    Route::get('/dashboard', function () {
        return Inertia::render('Internal/Dashboard');
    })->name('dashboard');
    Route::name('internal.')->prefix('internal')->group(function () {
        Route::resource('posts', \App\Http\Controllers\Internal\PostController::class);
    });

    //  ADMIN ROUTES
    Route::get('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::name('admin.')->prefix('admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('users/{user}/roles', [\App\Http\Controllers\Admin\UserRoleController::class, 'index'])->name('user_roles.index');
        Route::put('users/{user}/roles', [\App\Http\Controllers\Admin\UserRoleController::class, 'update'])->name('user_roles.update');
        
        Route::get('media', [\App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('media', [\App\Http\Controllers\Admin\MediaController::class, 'store'])->name('media.store');
        Route::patch('media/{medium}', [\App\Http\Controllers\Admin\MediaController::class, 'update'])->name('media.update');
        Route::delete('media/{medium}', [\App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');

        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::get('roles/{role}/rights', [\App\Http\Controllers\Admin\RoleRightController::class, 'index'])->name('role_rights.index');
        Route::post('roles/{role}/rights', [\App\Http\Controllers\Admin\RoleRightController::class, 'store'])->name('role_rights.store');
        Route::put('roles/{role}/rights', [\App\Http\Controllers\Admin\RoleRightController::class, 'update'])->name('role_rights.update');

        Route::resource('menu-items', \App\Http\Controllers\Admin\MenuItemController::class);
    });
});

require __DIR__.'/auth.php';
