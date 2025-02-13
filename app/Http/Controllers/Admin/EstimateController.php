<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use App\Models\User;
use App\Models\Company;
use App\Models\Estimate;
use App\Models\Attachment;
use App\Models\UserPlayerId;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use Ladumor\OneSignal\OneSignal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('estimates-list');
        $data['title'] = 'Estimates';
        $data['companies'] = Company::get();
        $data['users'] = User::get();
        $data['requests'] = PurchaseRequest::get();
        $records = Estimate::groupBy("request_id")->select("*", DB::raw("count(*) as count"));
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($records)
                ->addIndexColumn()
                ->addColumn('company', function ($model) {
                    return $model->company->name ?? '';
                })
                ->addColumn('creator', function ($model) {
                    return getUserName($model->creator_id) ?? '';
                })
                ->addColumn('requestData', function ($model) {
                    return  $model->requestData->subject ?? '';
                })
                ->addColumn('title', function ($model) {
                    return $model->title;
                })
                ->addColumn('description', function ($model) {
                    $description = $model->description;
                    $words = str_word_count($description);
                    $limit = 5;
                    if ($words > $limit) {
                        $wordsArray = explode(' ', $description, $limit + 1);
                        $description = implode(' ', array_slice($wordsArray, 0, $limit)) . '...';
                        $description .= '<a href="' . route("estimates.show", $model->id) . '">Read more</a>';
                    }
                    return $description;
                })
                ->addColumn('count', function ($model) {
                    $data = '';
                    $data = '<span class="badge bg-label-success">' . $model->count   . '</span>';
                    return $data;
                })
                ->addColumn('price', function ($model) {
                    return $model->price ?? 0;
                })
                ->addColumn('status', function ($model) {
                    $data = '';
                    $class = $model->requestData->getStatus->class  ?? "primary";
                    $name = $model->requestData->getStatus->name  ?? "-";
                    $data .= '<span class="badge bg-label-' . $class  . '">' . $name   . '</span>';
                    return $data;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.estimates.action', ['model' => $model])->render();
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request['search'])) {
                        $search = $request['search'];
                        $instance->where("title", "LIKE", "%$search%");
                        $instance->orWhere("description", "LIKE", "%$search%");
                        $instance->orWhere("price", "LIKE", "%$search%");
                    }

                    if (!empty($request['creator'])) {
                        $search = $request['creator'];
                        $instance->where("creator_id", $search);
                    }

                    if (!empty($request['company'])) {
                        $search = $request['company'];
                        $instance->where("company_id", $search);
                    }

                    if (!empty($request['filter_status'])) {
                        $search = $request['filter_status'];
                        $instance->where("status", $search);
                    }
                })
                ->rawColumns(['title', 'description', 'count', 'creator', 'company', 'requestData',  'price',  'status', 'action'])
                ->make(true);
        }
        return view('admin.estimates.index',  $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('estimates-create');
        $data['title'] = "Add Estimate";
        $data['companies'] = Company::all();
        $data['requests'] = PurchaseRequest::all();
        return view("admin.estimates.create", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "request_id" => "required",
            "title" => "required|max:255",
            "description" => "required",
            "price" => "required|integer",
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $purchaseRequest = PurchaseRequest::where('id', $request->request_id)->first();
        
        if(isset($purchaseRequest) && !empty($purchaseRequest)){
            try {
                $create = Estimate::create([
                    "creator_id" => Auth::user()->id,
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
                                    "attached_by" => Auth::user()->id,
                                    "file" => $fileName ?? null,
                                ]);
                            }
                        }
                    }
                }
                return redirect()->route("estimates.index")->with("success", "Estimate has been created");
            } catch (Exception $e) {
                return back()->with("error", $e->getMessage())->withInput();
            }
        }else{
            return redirect()->route("estimates.index")->with("success", "Purchase Request not found select another record...!");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['title'] = 'Estimate Detail';
        $data['records'] = Estimate::where('request_id', $id)->get();
        $data['requestData'] = PurchaseRequest::where("id", $id)->first();
        if (isset($data['records']) && !empty($data['records'])) {
            if (view()->exists('admin.estimates.show')) {
                return view('admin.estimates.show', $data);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approve(Request $request)
    {

        if (!empty($request->estimate_id)) {
            $estimate = Estimate::where("id", $request->estimate_id)->first();
            if (!empty($estimate)) {
                $otherEstimates = Estimate::where("id", "!=", $estimate->id)->where("request_id", $estimate->request_id)->get();
                if (!empty($otherEstimates) && $otherEstimates->count() > 0) {
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
                $request_data = $purchaseRequest  ?? null;
                if (!empty($purchaseRequest)) {
                    $purchaseRequest->update([
                        'status' => 2,
                        "remarks" => $request->remarks ?? "Approved",
                        "modified_by" => Auth()->id() ?? NULL,
                        "modified_at" => now() ?? NULL,
                    ]);  // 2 approved
                }

                if ($update == 1) {
                    // send the onesignal message for all users
                        try {
                            $users = User::all();
                            $playerIds = [];

                            foreach ($users as $user) {
                                $userPlayerId = UserPlayerId::where('user_id', $user->id)->orderBy('id', 'DESC')->first();
                                if ($userPlayerId && !empty($userPlayerId->player_id)) {
                                    $playerIds[] = $userPlayerId->player_id;
                                }
                            }
                            if (!empty($playerIds)) {
                                $responseMessage = "";
                                if ($update) {
                                    $fields = [];
                                    $fields['include_player_ids'] = $playerIds;
                                    $title = $estimate->title;
                                    $message = "Your Estimate " . $estimate->title . " has " . $request->remarks;
                                    $fields['headings'] = ['en' => $title];
                    
                                    // Send notification using OneSignal
                                    $oneSignal = \OneSignal::sendPush($fields, $message);
                                    
                                    if (isset($oneSignal['errors']) && !empty($oneSignal['errors'])) {
                                        $responseMessage .= $oneSignal['errors'][0] ?? '';
                                        dd($responseMessage);
                                    } else {
                                        $responseMessage .= "Estimate status updated successfully";
                                    }
                
                                    DB::commit();
                                    return response()->json(['success' => true, "message" => $responseMessage], 200);
                                } else {
                                    DB::rollback();
                                    return response()->json(['success' => false, "message" => 'Estimate status not updated successfully'], 401);
                                }
                            } else {
                                DB::rollback();
                                return response()->json(['success' => false, "message" => 'No player IDs found for users'], 404);
                            }
                        } catch (\Exception $e) {
                            DB::rollback();
                            return response()->json(['success' => false, "message" => $e->getMessage()], 500);
                        }
                    // onsignal end
                    $this->updateRequestOnPortal([
                        "remarks" => $request->remarks ?? null,
                        "status" => 2 ?? null,
                        "modified_by" => getUser()->email ?? null,
                        "request_data" => $request_data ?? null,
                    ]);
                    return response()->json([
                        "success" => true,
                        "message" => "Approved successfuly!",
                        "code" => 200,
                    ]);
                } else {
                    return response()->json([
                        "success" => false,
                        "message" => "Failed to approve!",
                        "code" => 200,
                    ]);
                }
            }
        }
    }
    public function updateRequestOnPortal($array = null)
    {
        if (isset($array['request_data']) && !empty($array['request_data'])) {
            $company = $array['request_data']->company;
            $companyBaseUrl = getCompanyBaseUrl($company->company_id);
            $url = $companyBaseUrl . 'api/update-purchase-request';

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
