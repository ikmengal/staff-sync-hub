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
use App\Http\Resources\StockResource;
use App\Http\Resources\CompanyResource;

class StockController extends Controller
{

    public function companyIndex(Request $request){
        if($request->bearerToken() == ""){
            return  apiResponse($success = false, $message = "Enter token", $code = 401); 
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();
        
        if(empty($userToken)){
            return  apiResponse($success = false , $message = "Unauthorized", $code = 401);
        }else{
            $company = Company::get();
            if(isset($company) && !blank($company)){
                $data = CompanyResource::collection($company);
                return  apiResponse($success = true, $data =  $data  , $message = "All comapnies.", $code = 200); 
            }else{
                return  apiResponse($success = false, $data = null  , $message = "No companies record.", $code = 401);
            }
        }
    }

    public function index(Request $request){
        if($request->bearerToken() == ""){
            return  apiResponse($success = false, $message = "Enter token", $code = 401); 
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();
        
        if(empty($userToken)){
            return  apiResponse($success = false , $message = "Unauthorized", $code = 401);
        }else{
            $user = User::where('id', $userToken->tokenable_id)->first();
            $stock = Stock::where('user_id', $user->id);
            $pageSize = 10;
            //date range
            //status
            // company id
            if(isset($request->company_id) && !empty($request->company_id) ) {
                $stock =  $stock->where("company_id" , $request->company_id);
            }
            if(isset($request->status) && !empty($request->status) ) {
                $stock =  $stock->where("status" , $request->status);
            }
           
            if(isset($request->date) && !empty($request->date) ) {
                $stock =  $stock->whereDate("created_at" , $request->date);
            }

            // date range

            if(isset($request->sorting) && !empty($request->sorting) ) {
                if($request->sorting == 1) {
                    $sorting = 'asc';
                }elseif($request->sorting == 2) {
                    $sorting = 'desc';
                }
            }else{
                $sorting = 'desc';
            }
            $stock = $stock->orderBy("id" , $sorting)->paginate($pageSize);
            if(isset($stock) && !blank($stock)){
                $data = StockResource::collection($stock);
                return apiResponse($success = true, $data = $data  , $message = "All stock", $code = 200);
            }else{
                return  apiResponse($success = false, $data = null  , $message = "No stock record found...!", $code = 401);
            }
        }
    }

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
                'images.*' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::where('id', $userToken->tokenable_id)->first();
            $company = Company::where('company_id', $request->company_id)->first();
            
            $stock = Stock::create([
                'user_id' => $user->id,
                'company_id' => $company->company_id,
                'title' => $request->title,
                'description' => $request->description,
                'quantity' => $request->quantity,
            ]);

            if(isset($stock) && !empty($stock)){
                if($request->hasFile('images')){
                    $files = $request->file('images');
                    foreach ($files as $file) {
                        // dd($file);
                        $originalName = $file->getClientOriginalExtension();
                        $mimeType = $file->getClientMimeType();
                        $fileName = "STOCK-IMAGE-" . time() .'.'. $file->getClientOriginalExtension();
                        $file->move(public_path('admin/assets/img/stock/'), $fileName);
    
                        StockImage::create([
                            'stock_id' => $stock->id,
                            'image' => $fileName,
                        ]);
                    }
                }
                $data = Stock::where("id" , $stock->id)->with("hasImages")->first();
                $data = new StockResource($data);
                return  apiResponse(true ,  $data , "Stock added successfully.",   200); 
            }else{
                return  apiResponse(false,  null  , "Stock not added...!",  401); 
            }
        }
    }

    public function show(Request $request){
        if($request->bearerToken() == ""){
            return  apiResponse(false, $message = "Enter token", $code = 401); 
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();
        
        if(empty($userToken)){
            return  apiResponse($success = false , $message = "Unauthorized", $code = 401);
        }else{
            $user = User::where('id', $userToken->tokenable_id)->first();
            $stock = Stock::where('user_id', $user->id)->where('id', $request->id)->first();
            if(isset($stock) && !empty($stock)){
                $data = new StockResource($stock);
                return apiResponse($success = true, $data = $data  , $message = "Stock Detail.", $code = 200);
            }else{
                return  apiResponse($success = false, $data = null  , $message = "No stock record found...!", $code = 401);
            }
        }
    }
}
