<?php

namespace App\Http\Controllers;

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
    return view('home');
})->name('home');

Route::get('urls', [Engine::class, 'showUrls'])->name('showUrls');

Route::get('/urls/{id}', [Engine::class, 'showUrl'])->name('showUrl');


Route::post('/', [Engine::class, 'addUrl'])->name('addUrl');

Route::post('/urls/{id}/checks', [Engine::class, 'checkUrl'])->name('checkUrl');
