<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Estimate;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Http\Resources\EstimateResource;
use App\Http\Resources\AttachmentResource;


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
            $estimates = Estimate::groupBy('request_id')->select("*", DB::raw("count(*) as count"))->get();
            // return $estimates;
            if(isset($estimates) && !blank($estimates)){
                $data = EstimateResource::collection($estimates);
                return apiResponse(true, $data, "All estimates", 200);
            }else{
                return apiResponse(false, null, "No Estimate record found...!", 500);
            }
        }
    }

    public function estimatesDetail(Request $request)
    {
        if($request->bearerToken() == ""){
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if(empty($bearerToken)){
            return apiResponse(false, null, "Unauthorized", 500);
        }else{
            $purchaseRequest = null;
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimates = Estimate::where('request_id', $request->id)->get();
            $purchaseRequest = PurchaseRequest::where('id', $request->id)->first();
            
            if(isset($purchaseRequest) && !empty($purchaseRequest)){
                $purchaseRequest = [
                    'id' => $purchaseRequest->id ?? null,
                    'creator' => $purchaseRequest->creator ?? null,
                    'company' => $purchaseRequest->company->name ?? null,
                    'subject' => $purchaseRequest->subject ?? null,
                    'description' => $purchaseRequest->description ?? null,
                    'status' => isset($purchaseRequest->status) ? ($purchaseRequest->status == 1 ? 'Pending' : ($purchaseRequest->status == 2 ? 'Approved' : ($purchaseRequest->status == 3 ? 'Rejected' : null))) : null,
                ];
            }
            if(isset($estimates) && !blank($estimates)){
                foreach ($estimates as $key => $value) {
                    $data['estimates'][] = [
                        'id' => $value->id ?? null,
                        'creator' => $value->company->name ?? null,
                        'price' => $value->price ?? null,
                        'description' => $value->description ?? null,
                        'status' => $value->status ?? null,
                        'images' => isset($value->attachments) && !empty($value->attachments) ? AttachmentResource::collection($value->attachments) : null,
                    ];
                }
                $data['request_purchase']=$purchaseRequest;
                return apiResponse(true, $data, "All estimates", 200);
            }else{
                return apiResponse(false, null, "No Estimate record found...!", 500);
            }
        }
    }
}
