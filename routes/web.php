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
    // $sql = "SELECT recipes_id_category_id.ID, recipes.ID AS recipes_ID, recipes.name, GROUP_CONCAT(categories.category_name ORDER BY categories.category_name SEPARATOR ', ') AS categories FROM recipes_id_category_id INNER JOIN recipes on recipes.ID = recipes_id_category_id.recipes_id INNER JOIN categories on categories.ID = recipes_id_category_id.category_id GROUP BY recipes.name ORDER BY recipes.name ASC";

    // $categories_of_recipes = DB::select(DB::raw($sql));

    $categories_of_recipes = DB::table('recipes_id_category_id')
    ->select('recipes_id_category_id.ID', 'recipes.ID AS recipes_ID', 'ecipes.name', 'DB::raw("GROUP_CONCAT(categories.category_name ORDER BY categories.category_name SEPARATOR ', ')")')
    ->join('recipes','recipes.ID', '=', 'recipes_id_category_id.recipes_id')
    ->join('categories','categories.ID', '=', 'recipes_id_category_id.category_id')
    ->groupBy('recipes.name')
    ->orderBy('recipes.name');
    //select recipes_id_category_id.ID, recipes.ID AS recipes_ID, recipes.name, GROUP_CONCAT(categories.category_name ORDER BY categories.category_name SEPARATOR ', ') AS categories from recipes_id_category_id inner join recipes on recipes.ID = recipes_id_category_id.recipes_id inner join categories on categories.ID = recipes_id_category_id.category_id GROUP by recipes.name ORDER BY recipes.name ASC
    // $categories_of_recipes = DB::table('recipes_id_category_id')->select('recipes_id_category_id.ID', 'recipes.name', 'categories.category_name')->join('recipes', 'recipes.ID', '=', 'recipes_id_category_id.recipes_id')->join('categories', 'categories.ID', '=', 'recipes_id_category_id.category_id')->get();

    return view('categories_of_recipes', ['categories_of_recipes'=> $categories_of_recipes]);
});
