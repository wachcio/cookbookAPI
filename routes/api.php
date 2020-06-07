<?php

// use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User;
use Illuminate\Support\Facades\Hash;

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
Auth::routes();



Route::group(['middleware' => 'cors'], function () {
    Route::post('/login', 'LoginController@authenticated');

    Route::group(['prefix'=>'recipes'], function () {
        Route::get('/', 'RecipesController@getAllRecipes');
        Route::post('/', 'RecipesController@createRecipes');
        Route::get('{id}', 'RecipesController@getRecipesID');
    });

    Route::get('/recipes_by_category', 'RecipesController@getRecipesByCategory');
    Route::get('/recipes_by_category/{id}', 'RecipesController@getRecipesByCategoryID');

    Route::get('/categories', 'CategoriesController@getAllCategories');
    Route::get('/categories/{id}', 'CategoriesController@getCategoriesID');

    Route::group(['prefix'=>'recipes','middleware'=> ['auth:sanctum','can:is-admin']], function () {
        Route::put('{id}', 'RecipesController@updateRecipes');
        Route::delete('{id}', 'RecipesController@deleteRecipes');
    });

    Route::group(['prefix'=>'categories','middleware'=> ['auth:sanctum','can:is-admin']], function () {
        Route::post('/', 'CategoriesController@createCategory');
        Route::put('{id}', 'CategoriesController@updateCategory');
        Route::delete('{id}', 'CategoriesController@deleteCategory');
    });
});