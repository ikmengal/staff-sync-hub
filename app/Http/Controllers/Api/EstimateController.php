<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Estimate;
use App\Models\User;
use App\Http\Resources\EstimateResource;


class EstimateController extends Controller
{
    public function index(Request $request)
    {
        if($request->bearerToken() == ""){
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if(empty($bearerToken)){
            return apiResponse(false, null, "Unauthorized", 500);
        }else{
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimates = Estimate::get();
            if(isset($estimates) && !blank($estimates)){
                $data = EstimateResource::collection($estimates);
                return apiResponse(true, $data, "All estimates", 200);
            }else{
                return apiResponse(false, null, "No Estimate record found...!", 500);
            }
        }
    }
}
