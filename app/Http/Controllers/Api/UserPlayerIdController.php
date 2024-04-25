<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserPlayerId;
use App\Http\Resources\PlayerIdResource;

class UserPlayerIdController extends Controller
{
    public function index(Request $request)
    {
        if($request->bearerToken() == ""){
            return apiResponse(false, "Enter Token", 401);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if(empty($userToken)){
            return apiResponse(false, "Unauthorized", 401);
        }else{
            $user = User::where('id', $userToken->tokenable_id)->first();
            $UserPlayerId = UserPlayerId::where('user_id', $user->id)->get();
        
            if(isset($UserPlayerId) && !blank($UserPlayerId)){
                $data = PlayerIdResource::collection($UserPlayerId);
                return apiResponse(true, $data, 'All user player ids', 200);
            }else{
                return apiResponse(false, null, 'No player ids for this user', 401);
            }
        }
    }

    public function store(Request $request)
    {
        if($request->bearerToken() == ""){
            return  apiResponse(false, "Enter token", 401); 
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();
        
        if(empty($userToken)){
            return  apiResponse(false , "Unauthorized", 401);
        }else{
            $validator = Validator::make($request->all(), [
                'player_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::where('id', $userToken->tokenable_id)->first();
            
            $UserPlayerId = UserPlayerId::create([
                'user_id' => $user->id,
                'player_id' => $request->player_id,
            ]);

            if(isset($UserPlayerId) && !empty($UserPlayerId)){
                $data = new PlayerIdResource($UserPlayerId);
                return  apiResponse(true , $data , "User Player Id added successfully.", 200); 
            }else{
                return  apiResponse(false, null , "User player id not added...!", 401);
            }
        }
    }
}
