<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\PurchaseRequest;
use App\Models\Attachment;
use App\Models\Estimate;
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

    public function store(Request $request)
    {
        ini_set('upload_max_filesize' , '50M' );
        ini_set('post_max_size' , '256M' );

        if($request->bearerToken() == ""){
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if(empty($bearerToken)){
            return apiResponse(false, null, "Unauthorized", 500);
        }else{
            $validator = Validator::make($request->all(), [
                "request_id" => "required|integer",
                "title" => "required|max:255",
                "description" => "required",
                "price" => "required|integer",
                'attachments.*' => 'nullable|image|mimes:png,jpg,jpeg',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 500);
            }

            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $purchaseRequest = PurchaseRequest::where('id', $request->request_id)->first();
            if(isset($purchaseRequest) && !empty($purchaseRequest)){
                DB::beginTransaction();
                try {
                    $create = Estimate::create([
                        "creator_id" => $user->id,
                        "request_id" => $request->request_id ?? null,
                        "company_id" => $purchaseRequest->company_id ?? null,
                        "title" => $request->title ?? null,
                        "description" => $request->description ?? null,
                        "price" => $request->price ?? null,
                    ]);
                    if (!empty($create->id)) {
                        if (isset($request->attachments) && !empty($request->attachments)) {
                            foreach ($request->attachments as $attachment) {
                                $fileName = uploadSingleFile($attachment, config("project.upload_path.estimates"), "ESTIMATE-");
                                if (isset($fileName) && !empty($fileName)) {
                                    $attachment = Attachment::create([
                                        "model_id" => $create->id,
                                        "model_name" => "\App\Models\Estimate",
                                        "attached_by" => $user->id,
                                        "file" => $fileName ?? null,
                                    ]);
                                }
                            }
                        }
                    }
                    DB::commit();
                    return apiResponse(true, null, "Estimate has been added successfully", 200);
                } catch (Exception $e) {
                    DB::rollback();
                    return apiResponse(false, null, $e->getMessage(), 500);
                }
            }else{
                return apiResponse(false, null, "No purchase request record found", 500);
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
                        'status' => $value->getStatus->name ?? null,
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

    public function estimateApprove(Request $request)
    {
        if($request->bearerToken() == ""){
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if(empty($bearerToken)){
            return apiResponse(false, null, "Unauthorized", 500);
        }else{
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimate = Estimate::where("id", $request->id)->first();
            if (!empty($estimate)) {
                if(isset($estimate) && $estimate->status == 1){
                    $otherEstimates = Estimate::where("id", "!=", $estimate->id)->where("request_id", $estimate->request_id)->get();
                    if (!empty($otherEstimates)) {
                        $otherEstimates->toQuery()->update([
                            "status" => 3, // 3  rejected
                            "remarks" => "Rejected",
                        ]);
                    }
                    $update = $estimate->update([
                        "status" => 2, // 2 approved
                        "remarks" => $request->remarks ?? "Approved",
                    ]);
                    $purchaseRequest = PurchaseRequest::where("id", $estimate->request_id)->first();
                    if (!empty($purchaseRequest)) {
                        $purchaseRequest->update([
                            'status' => 2,
                            "remarks" => $request->remarks ?? "Approved",
                            "modified_by" => $user->id ?? NULL,
                            "modified_at" => now() ?? NULL,
                        ]);  // 2 approved
                    }
                    if($update == 1) {
                        return apiResponse(true, null, "Approved successfuly!", 200);
                    }else{
                        return apiResponse(false, null, "Failed to approve!", 500);
                    }
                }else{
                    return apiResponse(false, null, "Estimate status already ". $estimate->getStatus->name, 500);                    
                }
            }else{
                return apiResponse(false, null, "No estimate record found", 500);
            }
        }
    }

    public function getApproveEstimate(Request $request)
    {
        if($request->bearerToken() == ""){
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if(empty($bearerToken)){
            return apiResponse(false, null, "Unauthorized", 500);
        }else{
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimates = Estimate::where('status', 2)->get();
            if(isset($estimates) && !blank($estimates)){
                foreach ($estimates as $key => $estimate) {
                    $data[]=[
                        'id' => $estimate->id ?? null,
                        'creator' => $estimate->creator->first_name.' '.$estimate->creator->last_name ?? null,
                        'company' => $estimate->company->name ?? null,
                        'title' => $estimate->title ?? null,
                        'description' => $estimate->description ?? null,
                        'price' => $estimate->price ?? null,
                        'status' => $estimate->getStatus->name ?? null,
                    ];
                }
                return apiResponse(true, $data, "All Approved Estimates", 200);
            }else{
                return apiResponse(false, null, "No Approved Estimate Record Found...!", 500);
            }
        }
    }
}