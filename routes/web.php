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

Route::get('/', function (): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View {
    return view('home');
});

Route::get('urls', [Engine::class, 'store'])->name('urls.store');

Route::get('urls/{id}', [Engine::class, 'show'])->name('urls.show')->whereNumber('id');


Route::post('urls', [Engine::class, 'create'])->name('urls.create');

Route::post('urls/{id}/checks', [Engine::class, 'update'])->name('urls.update');
