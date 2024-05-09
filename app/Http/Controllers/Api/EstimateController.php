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
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class EstimateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($bearerToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $pageSize = 10;
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimates = Estimate::whereHas('requestData', function ($query) {
                $query->where('status', 1); // get only those estimates whose requests are in pending
            })->groupBy('request_id')->select("*", DB::raw("count(*) as count"))->paginate($pageSize);

            if (isset($estimates) && !blank($estimates)) {
                $data = EstimateResource::collection($estimates);
                return apiResponse(
                    true,
                    $data,
                    'All estimates',
                    200,
                    [
                        'total' => $estimates->total(),
                        'per_page' => $estimates->perPage(),
                        'current_page' => $estimates->currentPage(),
                        'last_page' => $estimates->lastPage(),
                        'from' => $estimates->firstItem(),
                        'to' => $estimates->lastItem(),
                    ]
                );
            } else {
                return apiResponse(false, null, "No Estimate record found...!", 500);
            }
        }
    }

    public function store(Request $request)
    {
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '256M');

        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($bearerToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $validator = Validator::make($request->all(), [
                "request_id" => "required|integer",
                "title" => "required|max:255",
                "description" => "required",
                "price" => "required|integer",
                'attachments.*' => 'required|image|mimes:png,jpg,jpeg',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 500);
            }

            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $purchaseRequest = PurchaseRequest::where('id', $request->request_id)->first();
            if (isset($purchaseRequest) && !empty($purchaseRequest)) {
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
                        if (isset($request->type) && $request->type == 2) {
                            if ($request->hasFile('attachments')) {
                                $attachments = $request->file('attachments');
                                foreach ($attachments as $attachment) {
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
                        } else if (isset($request->type) && $request->type == 1) {
                            if ($request->has('attachments')) {
                                $image = $request->attachments;
                                $mime = explode(':', substr($image, 0, strpos($image, ';')))[1];
                                switch ($mime) {
                                    case 'image/jpeg':
                                        $extension = 'jpeg';
                                        break;
                                    case 'image/png':
                                        $extension = 'png';
                                        break;
                                    case 'image/gif':
                                        $extension = 'gif';
                                        break;
                                    default:
                                        $extension = 'jpg';
                                }

                                $image = str_replace('data:image/' . $extension . ';base64,', '', $image);
                                $image = str_replace(' ', '+', $image);
                                $index = 1;
                                $imageName = "ESTIMATE-" . time() . '.' . $extension;
                                $directory = public_path('attachments/estimates/');
                                $filePath = $directory . $imageName;
                                File::put($filePath, base64_decode($image));
                                if (isset($imageName) && !empty($imageName)) {
                                    $attachment = Attachment::create([
                                        "model_id" => $create->id,
                                        "model_name" => "\App\Models\Estimate",
                                        "attached_by" => $user->id,
                                        "file" => $imageName ?? null,
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
            } else {
                return apiResponse(false, null, "No purchase request record found", 500);
            }
        }
    }

    public function estimatesDetail(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($bearerToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $purchaseRequest = null;
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimates = Estimate::where('request_id', $request->id)
                ->orderByRaw("FIELD(status, 2) DESC")
                ->orderBy('status')
                ->get();

            $purchaseRequest = PurchaseRequest::where('id', $request->id)->first();

            if (isset($purchaseRequest) && !empty($purchaseRequest)) {
                $data['request_purchase'] = [
                    'id' => $purchaseRequest->id ?? null,
                    'creator' => $purchaseRequest->creator ?? null,
                    'company' => $purchaseRequest->company ?? null,
                    'subject' => $purchaseRequest->subject ?? null,
                    'description' => $purchaseRequest->description ?? null,
                    'status' => isset($purchaseRequest->getStatus)  && !empty($purchaseRequest->getStatus) ? $purchaseRequest->getStatus : null,
                ];
            }
            if (isset($estimates) && !blank($estimates)) {
                foreach ($estimates as $key => $value) {
                    $data['estimates'][] = [
                        'id' => $value->id ?? null,
                        'creator' => getUserName($value->creator) ?? null,
                        'title' => $value->title ?? null,
                        'description' => $value->description ?? null,
                        'price' => $value->price ?? null,
                        'status' => isset($value->getStatus)  && !empty($value->getStatus) ? $value->getStatus : null,
                        'images' => isset($value->attachments) && !blank($value->attachments) ? AttachmentResource::collection($value->attachments) : null,
                    ];
                }
            }

            return apiResponse(true, $data, "Data against Request id 2", 200);

            // else {
            //       return apiResponse(true, $data, "All estimates", 200);
            //     return apiResponse(false, null, "No Estimate record found...!", 500);
            // }
        }
    }

    public function estimateApprove(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }
        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($bearerToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimate = Estimate::where("id", $request->id)->first();

            if (!empty($estimate)) {
                if (isset($estimate) && $estimate->status == 1) {
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
                    $purchaseRequest = PurchaseRequest::with('company')->where("id", $estimate->request_id)->first();
                    $request_data = $purchaseRequest  ?? null;

                    if (!empty($purchaseRequest)) {
                        $purchaseRequest->update([
                            'status' => 2,
                            "remarks" => $request->remarks ?? "Approved",
                            "modified_by" => $user->id ?? NULL,
                            "modified_at" => now() ?? NULL,
                        ]);  // 2 approved

                        $this->updateRequestOnPortal([
                            "remarks" => $request->remarks ?? null,
                            "status" => 2 ?? null,
                            "modified_by" => $user->email ?? null,
                            "request_data" => $request_data ?? null,
                        ]);
                    }
                    if ($update == 1) {
                        return apiResponse(true, null, "Approved successfuly!", 200);
                    } else {
                        return apiResponse(false, null, "Failed to approve!", 500);
                    }
                } else {
                    return apiResponse(false, null, "Estimate status already " . $estimate->getStatus->name, 500);
                }
            } else {
                return apiResponse(false, null, "No estimate record found", 500);
            }
        }
    }

    public function getApproveEstimate(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $bearerToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($bearerToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $user = User::where('id', $bearerToken->tokenable_id)->first();
            $estimates = Estimate::where('status', 2)->get();
            if (isset($estimates) && !blank($estimates)) {
                foreach ($estimates as $key => $estimate) {
                    $data[] = [
                        'id' => $estimate->id ?? null,
                        'creator' => $estimate->creator->first_name . ' ' . $estimate->creator->last_name ?? null,
                        'company' => $estimate->company->name ?? null,
                        'title' => $estimate->title ?? null,
                        'description' => $estimate->description ?? null,
                        'price' => $estimate->price ?? null,
                        'status' => $estimate->getStatus->name ?? null,
                        'images' => isset($estimate->attachments) && !blank($estimate->attachments) ? AttachmentResource::collection($estimate->attachments) : null,
                    ];
                }
                return apiResponse(true, $data, "All Approved Estimates", 200);
            } else {
                return apiResponse(false, null, "No Approved Estimate Record Found...!", 500);
            }
        }
    }

    public function updateRequestOnPortal($array = null)
    {


        if (isset($array['request_data']) && !empty($array['request_data'])) {
            $company = $array['request_data']->company;
            $companyBaseUrl = getCompanyBaseUrl($company->company_id);
            $url = config("project.braincell_base_url") . 'api/update-purchase-request';
            $response = Http::post($url, $array);
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && !empty($data['success']) && $data['success'] == true) {
                    return $data;
                }
            } else {
                return ['success' => false, 'message' => 'Api error'];
            }
        }
        // $data  = $array;

    }
}
