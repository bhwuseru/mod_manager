<?php

use App\Http\Controllers\ModController;
use App\Livewire\Mod;
use Illuminate\Support\Facades\Route;
use App\Livewire\SearchMods;

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

Route::controller(SearchMods::class)
    ->prefix('mod')->group(function(){
        Route::match(['get', 'post'], '/', 'render')->name('mod');
    });
Route::get('/reg', [SearchMods::class, 'batch'])->name('reg');

// Route::get('/mod', [Mod::class, 'search'])->name('mod');
// Route::get('/counter', Counter::class);

Route::get('/', function () {
    return view('index');
});
