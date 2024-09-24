<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\StadiumController;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/maps', [MapController::class, 'show'])->name('maps.show');



Route::controller(PostController::class)->middleware(['auth'])->group(function(){
    Route::get('/stores/{store}/posts/create', 'create')->name('stores.posts.create');
    Route::post('/stores/{store}/posts', 'store')->name('stores.posts.store');
    Route::get('/stores/{store}/posts/{post}', 'show')->name('stores.posts.show');
    Route::put('/posts/{post}', 'update')->name('update');
    Route::delete('/posts/{post}', 'delete')->name('delete');
    Route::delete('/stores/{store}/posts/{post}', 'destroy')->name('posts.destroy');
    Route::get('stores/{store}/posts/{post}/edit', 'edit')->name('stores.posts.edit');
});

Route::get('/stores/{store}/posts', [StoreController::class, 'index'])->name('stores.posts.index');
Route::post('routes/{route}/stores/search', [StoreController::class, 'searchAndSave'])->name('stores.searchAndSave');


Route::get('/categories/{category}', [CategoryController::class,'index'])->middleware("auth");

Route::middleware('auth')->group(function () {
    Route::get('/profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
    Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
    Route::get('/profiles/{profile}',[ProfileController::class, 'show'])->name('profiles.show');;
    Route::put('/profiles/{profile}', [ProfileController::class, 'update'])->name('profiles.update');
    Route::get('/profiles/{profile}/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
});

Route::middleware('auth')->group(function () {
    Route::post('/stores/{store}/posts/{post}/comments', [CommentController::class, 'store'])->name('stores.posts.comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::delete('/posts/{post}/like', [PostController::class, 'unlike'])->name('posts.unlike');
});

Route::get('/stadiums', [StadiumController::class, 'index'])->name('stadiums.index');
Route::get('/stadiums/create', [StadiumController::class, 'create'])->name('stadiums.create');
Route::post('/stadiums', [StadiumController::class, 'store'])->name('stadiums.store');
Route::get('/stadiums/{stadium}', [StadiumController::class, 'show'])->name('stadiums.show');
Route::get('/stadiums/{stadium}/edit', [StadiumController::class, 'edit'])->name('stadiums.edit');
Route::put('/stadiums/{stadium}', [StadiumController::class, 'update'])->name('stadiums.update');
Route::delete('/stadiums/{stadium}', [StadiumController::class, 'destroy'])->name('stadiums.destroy');

Route::get('/routes', [RouteController::class, 'index'])->name('routes.index');
Route::get('/routes/create', [RouteController::class, 'create'])->name('routes.create');
Route::post('/routes', [RouteController::class, 'store'])->name('routes.store');
Route::get('/routes/{route}', [RouteController::class, 'show'])->name('routes.show');
Route::get('/routes/{route}/edit', [RouteController::class, 'edit'])->name('routes.edit');
Route::put('/routes/{route}', [RouteController::class, 'update'])->name('routes.update');
Route::delete('/routes/{route}', [RouteController::class, 'destroy'])->name('routes.destroy');

require __DIR__.'/auth.php';
