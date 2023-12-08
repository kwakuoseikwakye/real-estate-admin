<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index()
    {
        $data = DB::table("properties")->get();

        return response()->json([
            "status" => true,
            "data" => $data
        ], 200);
    }

    public function singleProperty($id)
    {
        $data = DB::table("properties")->where("id", $id)->get();

        return response()->json([
            "status" => true,
            "data" => $data
        ], 200);
    }
}
