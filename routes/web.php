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

Route::group(['middleware' => 'cors'], function () {
    Route::get('/recipes', 'RecipesController@getAllRecipes');
    Route::get('/recipes/{id}', 'RecipesController@getRecipesID');
    Route::post('/recipes', 'RecipesController@createRecipes');
    Route::put('/recipes/{id}', 'RecipesController@updateRecipes');
    Route::delete('/recipes/{id}', 'RecipesController@deleteRecipes');

    Route::get('/recipes_by_category', 'RecipesController@getRecipesByCategory');

    Route::get('/categories', 'CategoriesController@getAllCategories');
    Route::get('/categories/{id}', 'CategoriesController@getCategoriesID');
    Route::post('/categories', 'CategoriesController@createCategory');
    Route::put('/categories/{id}', 'CategoriesController@updateCategory');
    Route::delete('/categories/{id}', 'CategoriesController@deleteCategory');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');