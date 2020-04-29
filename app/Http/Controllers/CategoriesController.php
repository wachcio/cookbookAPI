<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class CategoriesController extends Controller
{
    public function create(Request $request)
    {
        return response()->json(['request' => $request], 200);

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
