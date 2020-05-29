<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;

class CategoriesController extends Controller
{
    public function getAllCategories()
    {
        $statusCode = 200;
        try {
            $response = DB::table('categories')
    ->orderBy('category_name')
    ->get();
        } catch (\Illuminate\Database\QueryException $ex) {
            $response = ["error" => "Nothing get from base", "msg"=>$ex];
            $statusCode = 400;
        }
        // return response()->json($response);
        // return response(json($categories), $statusCode);
        return Response::json($response, $statusCode);
    }

    public function getCategoriesID($id)
    {
        $category = DB::table('categories')
        ->select('*')
        ->where('ID', '=', $id)
        ->get();

        if (count($category) == 0) {
            $category = (object)null;
            $category->error = "Recipe nr ".$id." does not exist.";
            $category = json_encode($category);
        }

        return response()->json($category);
    }
    public function createCategory(Request $request)
    {
        $statusCode = 200;
        $inputs = request()->all();

        if ($inputs == [] || !isset($inputs['category_name'])) {
            $response = ["error" => "Nothing added to the base"];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }
        try {
            $sql = DB::table('categories')->insert(
                ['category_name' => $inputs['category_name']]
            );

            if ($sql == 1) {
                $response = ["success"=>"Add one category to database: ".$inputs['category_name']];
                $statusCode = 200;
                return Response::json($response, $statusCode);
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

    public function updateCategory(Request $request, $ID)
    {
        $inputs = request()->all();

        if (count($inputs) == 0 || !isset($inputs['category_name'])) {
            $response = ["msgEN" => "Nothing update. Please fill correct fild.", "msgPL" => "Nic nie zostało zaktualizowane. Proszę wypełnić poprawnie pole."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        $category = DB::table('categories')
        ->select('*')
        ->where('ID', '=', $ID)
        ->get();

        if (count($category) == 0) {
            $response = ["msgEN" => "Nothing update. Please fill correct fild.", "msgPL" => "Nic nie zostało zaktualizowane. Proszę wypełnić poprawnie pole."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        $sql = DB::table('categories')
              ->where('ID', $ID)
              ->update(['category_name' => $inputs['category_name']]);

        if ($sql == 1) {
            $response = ["msgEN"=>"Update one categories in database", "msgPL" => "Zaktualizowano jedną kategorię w bazie."];
            $statusCode = 200;
            return Response::json($response, $statusCode);
        } else {
            $response = ["msgEN" => "Nothing update. Please fill correct fild.", "msgPL" => "Nic nie zostało zaktualizowane. Proszę wypełnić poprawnie pole."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        return $response;
    }

    public function deleteCategory(Request $request, $ID)
    {
        $inputs = request()->all();

        $category = DB::table('categories')
        ->select('*')
        ->where('ID', '=', $ID)
        ->get();

        if (count($category) == 0) {
            $response = ["msgEN" => "Nothing delete in database", "msgPL" => "Nie usunięto nic z bazy."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        DB::table('recipes_id_category_id')->where("category_id", "=", $ID)->delete();

        $sql = DB::table('categories')
              ->where('ID', $ID)
              ->delete();

        if ($sql == 1) {
            $response = ["msgEN"=>"Remove one category in database", "msgPL" => "Usunięto jedną kategorię z bazy."];
            $statusCode = 200;
            return Response::json($response, $statusCode);
        } else {
            $response = ["msgEN" => "Nothing delete in database", "msgPL" => "Nie usunięto nic z bazy."];
            $statusCode = 400;
            return Response::json($response, $statusCode);
        }

        return $response;
    }
}