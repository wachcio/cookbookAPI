<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class RecipesController extends Controller
{
    public function getRecipes(){
        $recipes = DB::table('recipes_id_category_id')
        ->select('recipes.ID', 'recipes.name', 'recipes.ingredients', 'recipes.execution', 'recipes.picture','recipes.rating', DB::raw('GROUP_CONCAT(categories.category_name ORDER BY categories.category_name) AS categories'))
        ->join('recipes','recipes.ID', '=', 'recipes_id_category_id.recipes_id')
        ->join('categories','categories.ID', '=', 'recipes_id_category_id.category_id')
        ->groupBy('recipes.name')
        ->orderBy('recipes.ID')
        ->get();


        foreach ($recipes as $key => $value) {
            $value->categories = explode(',', $value->categories);
        }

        return view('getJSON', ['JSONdata'=> $recipes]);
    }

    public function getRecipesID($id){
        $recipe = DB::table('recipes')
        ->select('*')
        ->where('ID', '=', $id)
        ->get();

        if (count($recipe) == 0) {
            $recipe = (object)null;
            $recipe->error = "Recipe nr ".$id." does not exist.";
            $recipe = json_encode($recipe);
        }

        return view('getJSON', ['JSONdata'=> $recipe]);
    }

    public function createRecipesID(Request $request)
    {

        // $inputs = request()->all();
        $inputs = request()->all();

        if ($inputs == [] || !isset($inputs['name']) || !isset($inputs['ingredients']) || !isset($inputs['execution']) || !isset($inputs['category_id'])) {
            return ["error" => "Nothing added to the base"];
        }

        $picture = (isset($inputs['picture'])) ? $inputs['picture'] : "";
        $rating = (isset($inputs['rating'])) ? $inputs['rating'] : "";

        $categories = DB::table('categories')
        ->where('ID', '=', $inputs['category_id'])
        ->get();

        // var_dump($categories);

        $sql = DB::table('recipes')->insert(
            ['name' => $inputs['name'],'ingredients' => $inputs['ingredients'],'execution' => $inputs['execution'], 'picture' => $picture, 'rating' => $rating]
        );

        $recipesID = DB::getPdo()->lastInsertId();

       if (count($categories) > 0 ) {
            DB::table('recipes_id_category_id')->insert(
                ['category_id' => $inputs['category_id'],'recipes_id' => $recipesID]
            );
        } else {
            DB::table('recipes')->delete()->where("ID", "=", $recipesID);
        }



        // var_dump($sql);
        if ($sql == 1 && count($categories) > 0) {
            $response = ["success"=>"Add one recipes to database on ID ", "last_insert_id" => $recipesID];
        } else {
            $response = ["error" => "Nothing added to the base"];
        }

        return $response;
    }

    public function updateRecipesID(Request $request, $ID)
    {
        // $inputs = request()->all();

        // if (count($inputs) == 0 || !isset($inputs['category_name'])) {
        //     return ["error" => "Nothing update in database"];
        // }

        // $category = DB::table('categories')
        // ->select('*')
        // ->where('ID', '=', $ID)
        // ->get();

        // if (count($category) == 0) {
        //     return ["error" => "Nothing update in database"];
        // }

        // $sql = DB::table('categories')
        //       ->where('ID', $ID)
        //       ->update(['category_name' => $inputs['category_name']]);

        // if ($sql == 1) {
        //     $response = ["success"=>"Update one category to database: ".$inputs['category_name']];
        // } else {
        //     $response = ["error" => "Nothing update in database!"];
        // }

        // return $response;
    }

    public function deleteRecipesID(Request $request, $ID)
    {
    //     $inputs = request()->all();

    //     $category = DB::table('categories')
    //     ->select('*')
    //     ->where('ID', '=', $ID)
    //     ->get();

    //     if (count($category) == 0) {
    //         return ["error" => "Nothing delete in database"];
    //     }

    //     $sql = DB::table('categories')
    //           ->where('ID', $ID)
    //           ->delete();

    //     if ($sql == 1) {
    //         $response = ["success"=>"Delete one category in database ID: ".$ID];
    //     } else {
    //         $response = ["error" => "Nothing delete in database!"];
    //     }

    //     return $response;
    }

    public function getRecipesByCategory(){
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

    return view('getJSON', ['JSONdata'=>$categoriesDetails]);
    }
}
