<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Estimate;
use App\Models\User;


class EstimateController extends Controller
{
    public function index(Request $request)
    {
        if($request->bearerToken() == ""){
            return apiResponse(false, null, "Enter token", 500);
        }

        // $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bear)

        if(empty($request->bearerToken())){
            return apiResponse(false, null, "Unauthorized", 500);
        }
        return $request;
    }
}
