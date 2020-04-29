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
    $recipes = DB::table('recipes_id_category_id')
    ->select('recipes.ID', 'recipes.name', 'recipes.ingredients', 'recipes.execution', 'recipes.picture','recipes.rating', DB::raw('GROUP_CONCAT(categories.category_name ORDER BY categories.category_name) AS categories'))
    ->join('recipes','recipes.ID', '=', 'recipes_id_category_id.recipes_id')
    ->join('categories','categories.ID', '=', 'recipes_id_category_id.category_id')
    ->groupBy('recipes.name')
    ->orderBy('recipes.name')
    ->get();


    foreach ($recipes as $key => $value) {
        $value->categories = explode(',', $value->categories);
    }

    return view('recipes', ['recipes'=> $recipes]);
});

Route::get('/categories', function () {
    $categories = DB::table('categories')
    ->orderBy('category_name')
    ->get();

    return view('categories', ['categories'=> $categories]);
});


