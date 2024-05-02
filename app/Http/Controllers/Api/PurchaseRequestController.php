<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Models\PurchaseRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PurchaseRequestController extends Controller
{

    public function index(Request $request)
    {
        if($request->bearerToken() == "")
        {
            return apiResponse(false, null, "Enter Token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if(empty($bearerToken))
        {
            return apiResponse(false, null, "Unauthorized", 500);
        }else{
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $purchaseRequest = PurchaseRequest::get();
            if(isset($purchaseRequest) && !blank($purchaseRequest)){
                return PurchaseResource::collection($purchaseRequest);
            }else{
                return apiResponse(false, null, "No purchase request record found");
            }
        }
    }

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
