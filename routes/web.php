<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PictureController;
use App\Models\Album;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('albums.index');
});

Route::resource('albums', AlbumController::class);
Route::post('/getAlbum', [AlbumController::class, 'getAlbum'])->name('getAlbum');
Route::post('/updateAlbum', [AlbumController::class, 'updateAlbum'])->name('updateAlbum');
Route::post('/DeleteAlbum', [AlbumController::class, 'DeleteAlbum'])->name('DeleteAlbum');
Route::post('/MoveAlbum', [AlbumController::class, 'MoveAlbum'])->name('MoveAlbum');
Route::get('/Chart', [AlbumController::class, 'Chart'])->name('Chart');
Route::resource('pictures', PictureController::class);
