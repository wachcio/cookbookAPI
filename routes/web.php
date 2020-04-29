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

Route::get('/recipes/by_category', function () {
    $categories = DB::table('categories')
    ->orderBy('category_name')
    ->get();

    $categoriesDetails = [];

    foreach ($categories as $key => $value) {
        $temp = DB::table('recipes_id_category_id')
        ->select( 'recipes_id_category_id.recipes_id', 'recipes.name', 'recipes.ingredients', 'recipes.execution', 'recipes.picture', 'recipes.rating')
        ->join('recipes', 'recipes.ID', '=', 'recipes_id')
        ->join('categories', 'categories.ID', '=', 'category_id')
        ->where('recipes_id_category_id.category_id', '=', $value->ID)
        ->get();

        if (count($temp)>0) {
            $tempObj = (object)null;
            $tempObj->ID = $value->ID;
            $tempObj->category_name = $value->category_name;
            array_push($categoriesDetails, array($tempObj, $temp));

        }
    }

    $categoriesDetails = json_encode($categoriesDetails);

    return view('categories', ['categories'=>$categoriesDetails]);
});

Route::get('/categories', function () {
    $categories = DB::table('categories')
    ->orderBy('category_name')
    ->get();

    return view('categories', ['categories'=> $categories]);
});





