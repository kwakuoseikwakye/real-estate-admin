<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ItemsController extends Controller
{
    public function index($userId)
    {
        $data = DB::table("saved_items")->where("user_id", $userId)->get();

        return response()->json([
            "status" => true,
            "data" => $data
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "property" => "required",
                    "user" => "required",
                ],
                [
                    "property.required" => "No property ID supplied",
                    "user.required" => "No user ID supplied",
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    "status" => false,
                    "msg" => "Saving item failed. " . join(". ", $validator->errors()->all()),
                ], 400);
            }

            DB::table("saved_items")->insert([
                "property_id" => $request->property,
                "user_id" => $request->user,
                "createdate" => date('Y-m-d H:i:s'),
            ]);

            return response()->json([
                "status" => true,
                "msg" => "Property saved successfully",
            ], 200);
        } catch (\Exception $e) {
            Log::error("Failed to add items: " . $e->getMessage());
            return response()->json([
                "status" => false,
                "msg" => "Saving Items failed. An internal error occured",
            ], 500);
        }
    }
}
