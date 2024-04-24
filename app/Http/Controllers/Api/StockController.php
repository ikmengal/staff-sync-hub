<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Stock;
use App\Models\Company;
use App\Models\StockImage;

class StockController extends Controller
{
    public function store(Request $request){
        if($request->bearerToken() == ""){
            return  apiResponse($success = false, $message = "Enter token", $code = 401); 
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();
        
        if(empty($userToken)){
            return  apiResponse($success = false , $message = "Unauthorized", $code = 401);
        }else{
            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'quantity' => 'required',
                'images' => 'required|image',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::where('id', $userToken->tokenable_id)->first();
            return $company;

            // $stock = Stock::create([
            //     'user_id' => $request
            //     'company_id'
            //     'title'
            //     'description'
            //     'quantity'
            // ]);

            return  apiResponse($success = false, $data =  null  , $message = "Stock not added...!", $code = 401); 
        }
    }
}
