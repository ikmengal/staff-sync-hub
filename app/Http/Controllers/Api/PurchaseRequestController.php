<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Models\PurchaseRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseRequestController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "creator" => "required|email",
            "company_id" => "required|integer",
            "subject" => "required|max:255",
            "description" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error",
                "error" => $validator->errors()->all(),
            ], 500);
        }
        try {
            $create = PurchaseRequest::create([
                "creator" => $request->creator  ?? null,
                "company_id" => $request->company_id ?? null,
                "subject" => $request->subject ?? null,
                "description" => $request->description ?? null,
            ]);
            if ($create->id) {
                return response()->json([
                    "success" => true,
                    "message" => "Request has been created",
                    "data" => new PurchaseResource($create),
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Error occured",
                "error" => $e->getMessage(),
            ], 500);
        }
    }
}
