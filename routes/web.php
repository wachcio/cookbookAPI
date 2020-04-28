<?php

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

    return view('welcome');
});

Route::get('/recipes', function () {
    $recipes = DB::table('recipes')->get();

    return view('recipes', ['recipes'=> $recipes]);
});

Route::get('/categories', function () {
    $categories = DB::table('categories')->get();

    return view('categories', ['categories'=> $categories]);
});
