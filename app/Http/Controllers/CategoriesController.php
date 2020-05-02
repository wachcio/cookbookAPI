<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CategoriesController extends Controller
{
    public function createCategory(Request $request)
    {
        $inputs = request()->all();

        if ($inputs == [] || !isset($inputs['category_name'])) {
            return ["error" => "Nothing added to the base"];
        }

        $sql = DB::table('categories')->insert(
            ['category_name' => $inputs['category_name']]
        );

        if ($sql == 1) {
            $response = ["success"=>"Add one category to database: ".$inputs['category_name']];
        } else {
            $response = ["error" => "Nothing added to the base"];
        }

        return $response;
    }

    public function updateCategory(Request $request, $ID)
    {
        $inputs = request()->all();

        if (count($inputs) == 0 || !isset($inputs['category_name'])) {
            return ["error" => "Nothing update in database"];
        }

        $category = DB::table('categories')
        ->select('*')
        ->where('ID', '=', $ID)
        ->get();

        if (count($category) == 0) {
            return ["error" => "Nothing update in database"];
        }

        $sql = DB::table('categories')
              ->where('ID', $ID)
              ->update(['category_name' => $inputs['category_name']]);

        if ($sql == 1) {
            $response = ["success"=>"Update one category to database: ".$inputs['category_name']];
        } else {
            $response = ["error" => "Nothing update in database!"];
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
            return ["error" => "Nothing delete in database"];
        }

        $sql = DB::table('categories')
              ->where('ID', $ID)
              ->delete();

        if ($sql == 1) {
            $response = ["success"=>"Delete one category in database ID: ".$ID];
        } else {
            $response = ["error" => "Nothing delete in database!"];
        }

        return $response;
    }

    public function getCategories()
    {
        $categories = DB::table('categories')
    ->orderBy('category_name')
    ->get();

        return view('getJSON', ['JSONdata'=> $categories]);
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

        return view('getJSON', ['JSONdata'=> $category]);
    }
}
