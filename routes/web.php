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

Route::get('/categories_of_recipes', function () {

    // $categories_of_recipes = DB::table('recipes_id_category_id_view')->get();
    $categories_of_recipes = DB::table('recipes_id_category_id')->select('recipes_id_category_id.ID', 'recipes.name', 'categories.category_name')->join('recipes', 'recipes.ID', '=', 'recipes_id_category_id.recipes_id')->join('categories', 'categories.ID', '=', 'recipes_id_category_id.category_id')->get();

    return view('categories_of_recipes', ['categories_of_recipes'=> $categories_of_recipes]);
});
