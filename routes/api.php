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

    Route::get('/recipes', 'RecipesController@getAllRecipes');
    Route::get('/recipes/{id}', 'RecipesController@getRecipesID');
    Route::get('/recipes_by_category', 'RecipesController@getRecipesByCategory');
    Route::get('/recipes_by_category/{id}', 'RecipesController@getRecipesByCategoryID');

    Route::get('/categories', 'CategoriesController@getAllCategories');
    Route::get('/categories/{id}', 'CategoriesController@getCategoriesID');

    Route::group(['prefix'=>'recipes','middleware'=> ['auth:sanctum','can:is-admin']], function () {
        Route::post('/', 'RecipesController@createRecipes');
        Route::put('{id}', 'RecipesController@updateRecipes');
        Route::delete('{id}', 'RecipesController@deleteRecipes');
    });

    Route::group(['prefix'=>'categories','middleware'=> ['auth:sanctum','can:is-admin']], function () {
        Route::post('/', 'CategoriesController@createCategory');
        Route::put('{id}', 'CategoriesController@updateCategory');
        Route::delete('{id}', 'CategoriesController@deleteCategory');
    });
});




Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/login', function (Request $request) {
//     $data = $request->validate([
//         'email' => 'required|email',
//         'password' => 'required'
//     ]);

//     $user = User::where('email', $request->email)->first();

//     if (!$user || !Hash::check($request->password, $user->password)) {
//         return response(
//             ["msgEN" => "Login fail.", "msgPL" => "Błąd logowania."],
//             404
//         );
//     }

//     $token = $user->createToken('cookbook')->plainTextToken;


//     $response = [
//         'user' => $user,
//         'token' => $token,
//         // 'X-CSRF-TOKEN' => csrf_token()
//     ];

//     return response($response, 201);
// });