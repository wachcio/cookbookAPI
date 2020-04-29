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

Route::get('/recipes', 'RecipesController@getRecipes');

Route::get('/recipes/{id}', 'RecipesController@getRecipesID');

Route::get('/recipes_by_category', 'RecipesController@getRecipesByCategory');

Route::get('/categories', 'CategoriesController@getCategories');

Route::get('/categories/{id}', 'CategoriesController@getCategoriesID');

// Route::post('/categories/{category_name}', function ($category_name) {
//     $categories = DB::insert('INSERT INTO categories (ID, category_name) VALUES (NULL, $category_name);');
//     return $category_name;
//     // return view('getJSON', ['JSONdata'=> $categories]);
// });

Route::post('/categories', 'CategoriesController@create');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
