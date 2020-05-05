<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

class RecipesController extends Controller
{
    public function getAllRecipes()
    {
        $recipes = DB::table('recipes_id_category_id')
        ->select('recipes.ID', 'recipes.name', 'recipes.description', 'recipes.ingredients', 'recipes.execution', 'recipes.picture', 'recipes.rating', DB::raw('GROUP_CONCAT(categories.category_name, ";;", categories.ID ORDER BY categories.category_name) AS categories'))
        ->join('recipes', 'recipes.ID', '=', 'recipes_id_category_id.recipes_id')
        ->join('categories', 'categories.ID', '=', 'recipes_id_category_id.category_id')
        ->groupBy('recipes.name')
        ->orderBy('recipes.name')
        ->get();


        foreach ($recipes as $key => $value) {
            $temp = explode(',', $value->categories);
            $arr = [];
            foreach ($temp as $key2 => $value2) {
                $obj = (object)[];

                $temp2 = explode(';;', $value2);

                $obj->ID = $temp2[1];
                $obj->category_name = $temp2[0];
                array_push($arr, $obj);
            }

            $value->categories = $arr;
        }

        return response()->json($recipes);
    }

    public function getRecipesID($id)
    {
        $recipes = DB::table('recipes_id_category_id')
        ->select('recipes.ID', 'recipes.name', 'recipes.description', 'recipes.ingredients', 'recipes.execution', 'recipes.picture', 'recipes.rating', DB::raw('GROUP_CONCAT(categories.category_name, ";;", categories.ID ORDER BY categories.category_name) AS categories'))
        ->join('recipes', 'recipes.ID', '=', 'recipes_id_category_id.recipes_id')
        ->join('categories', 'categories.ID', '=', 'recipes_id_category_id.category_id')
        ->groupBy('recipes.name')
        ->orderBy('recipes.ID')
        ->where('recipes.ID', '=', $id)
        ->get();


        foreach ($recipes as $key => $value) {
            $temp = explode(',', $value->categories);
            $arr = [];
            foreach ($temp as $key2 => $value2) {
                $obj = (object)[];

                $temp2 = explode(';;', $value2);

                $obj->ID = $temp2[1];
                $obj->category_name = $temp2[0];
                array_push($arr, $obj);
            }

            $value->categories = $arr;
        }

        return response()->json($recipes);
    }


    public function createRecipes(Request $request)
    {
        $statusCode = 200;
        $inputs = request()->all();
        $inputsCategories = [];

        if (isset($inputs['category_id'])) {
            $inputsCategories = explode(', ', $inputs['category_id']);
        }

        if ($inputs == [] || !isset($inputs['name']) || !isset($inputs['description'])|| !isset($inputs['ingredients']) || !isset($inputs['execution']) || !isset($inputs['category_id'])) {
            $response = ["error" => "Nothing added to the base"];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        $picture = (isset($inputs['picture'])) ? $inputs['picture'] : "";
        $rating = (isset($inputs['rating'])) ? $inputs['rating'] : "";

        $categories = null;

        foreach ($inputsCategories as $key => $value) {
            $categories = DB::table('categories')
             ->where('ID', '=', $value)
                ->get();
            if (count($categories) <1) {
                $response = ["error" => "Nothing added to the base"];
                $statusCode = 400;
                return Response::json($response, $statusCode);
            }
        }
        try {
            $sql = DB::table('recipes')->insert(
                ['name' => $inputs['name'],'description' => $inputs['description'],'ingredients' => $inputs['ingredients'],'execution' => $inputs['execution'], 'picture' => $picture, 'rating' => $rating]
            );

            $recipesID = DB::getPdo()->lastInsertId();

            if (count($categories) > 0) {
                foreach ($inputsCategories as $key => $value) {
                    DB::table('recipes_id_category_id')->insert(
                        ['category_id' => $value,'recipes_id' => $recipesID]
                    );
                }
            } else {
                DB::table('recipes')->delete()->where("ID", "=", $recipesID);
            }

            if ($sql == 1 && count($categories) > 0) {
                $response = ["success"=>"Add one recipes to database on ID ", "last_insert_id" => $recipesID];
            } else {
                $response = ["error" => "Nothing added to the base"];
                $statusCode = 400;
                return Response::json($response, $statusCode);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $response = ["error" => "Nothing added to the base", "msg"=>$ex];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        return Response::json($response, $statusCode);
    }
    public function updateRecipes(Request $request, $ID)
    {
        $inputs = request()->all();
        $inputsCategories = [];
        if (isset($inputs['category_id'])) {
            $inputsCategories = explode(', ', $inputs['category_id']);
        }

        if ($inputs == []  || !isset($inputs['name']) || !isset($inputs['description']) || !isset($inputs['ingredients']) || !isset($inputs['execution']) || !isset($inputs['category_id'])) {
            $response = ["error" => "Nothing update. Please fill correct everyting filds."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        $picture = (isset($inputs['picture'])) ? $inputs['picture'] : "";
        $rating = (isset($inputs['rating'])) ? $inputs['rating'] : "";

        $categories = null;

        foreach ($inputsCategories as $key => $value) {
            $categories = DB::table('categories')
             ->where('ID', '=', $value)
                ->get();
            if (count($categories) <1) {
                $response = ["error" => "Nothing update. Please fill correct everyting filds."];
                $statusCode = 400;
                return Response::json($response, $statusCode);
            }
        }

        $sql = DB::table('recipes')
        ->where('ID', $ID)
        ->update(['name' => $inputs['name'], 'description' => $inputs['description'],'ingredients' => $inputs['ingredients'], 'execution' => $inputs['execution'], 'picture' => $picture, 'rating' => $rating, ]);

        if (count($categories) > 0) {
            DB::table('recipes_id_category_id')->where("recipes_id", "=", $ID)->delete();

            foreach ($inputsCategories as $key => $value) {
                DB::table('recipes_id_category_id')->insert(
                    ['category_id' => $value,'recipes_id' => $ID]
                );
            }
        } else {
            DB::table('recipes')->where("ID", "=", $ID)->delete();
        }

        if (count($categories) > 0) {
            $response = ["success"=>"Update one recipes in database"];
            $statusCode = 200;
            return Response::json($response, $statusCode);
        } else {
            $response = ["error" => "Nothing update. Please fill correct everyting filds."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        return $response;
    }

    public function deleteRecipes(Request $request, $ID)
    {
        $inputs = request()->all();

        $recipes = DB::table('recipes')
        ->select('*')
        ->where('ID', '=', $ID)
        ->get();

        if (count($recipes) == 0) {
            $response = ["error" => "Nothing delete in database"];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        DB::table('recipes_id_category_id')->where("recipes_id", "=", $ID)->delete();

        $sql = DB::table('recipes')
        ->where('ID', $ID)
        ->delete();

        if ($sql> 0) {
            $response = ["success"=>"Update one recipes in database"];
            $statusCode = 200;
            return Response::json($response, $statusCode);
        } else {
            $response = ["error" => "Nothing update. Please fill correct everyting filds."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        return $response;
    }

    public function getRecipesByCategory()
    {
        $categories = DB::table('categories')
    ->orderBy('category_name')
    ->get();

        $categoriesDetails = [];

        foreach ($categories as $key => $value) {
            $temp = DB::table('recipes_id_category_id')
        ->select('recipes_id_category_id.recipes_id', 'recipes.name', 'recipes.description', 'recipes.ingredients', 'recipes.execution', 'recipes.picture', 'recipes.rating')
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


        return response()->json($categoriesDetails);
    }
}