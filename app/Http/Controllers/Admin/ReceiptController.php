<?php

namespace App\Http\Controllers\Admin;

use Str;
use App\Models\User;
use App\Models\Stock;
use App\Models\Company;
use App\Models\StockImage;
use Illuminate\Http\Request;
use App\Models\UserPlayerId;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Ladumor\OneSignal\OneSignal;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
        // $this->authorize('stock-list');
        $title = 'All Receipts';

        // $model = Stock::orderby('id', 'desc')->get();
        $model = Stock::query()->orderBy('id', 'desc');
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('title', function ($model) {
                return $model->title;
            })
            ->editColumn('description', function ($model) {
                $description = $model->description;
                $words = str_word_count($description);
                $limit = 5;
                if ($words > $limit) {
                    $wordsArray = explode(' ', $description, $limit+1);
                    $description = implode(' ', array_slice($wordsArray, 0, $limit)) . '...';
                    $description .= '<a href="'.route("receipts.show",$model->id).'"> Read more</a>';
                }
                return $description;
            })                
            ->editColumn('creator', function ($model) {
                return $model->hasUser->first_name.' '.$model->hasUser->last_name;
            })
            ->editColumn('company', function ($model) {
                return $model->hasCompany->name;
            })
            ->editColumn('quantity', function ($model) {
                return $model->quantity;
            })
            ->editColumn('status', function ($model) {
                return view('admin.receipts.status', ['model' => $model])->render();
            })
            ->addColumn('action', function($model){
                return view('admin.receipts.action', ['model' => $model])->render();
            })
            ->filter(function ($query) use ($request) {
                if (!empty($request['search'])) {
                    $search = $request['search'];
                    $query->where('title', "LIKE", "%$search%");
                    $query->orWhere('description', "LIKE", "%$search%");
                    $query->orWhere('quantity', "LIKE", "%$search%");
                }
                
                if (!empty($request['company'])) {
                    $search = $request['company'];
                    $query->where('company_id', $search);
                }

                if (!empty($request['creator'])) {
                    $search = $request['creator'];
                    $query->where('user_id', $search);
                }

                if (!empty($request['filter_status'])) {
                    $search = $request['filter_status'];
                    $query->where('status', $search);
                }
            })
            ->rawColumns(['title', 'description', 'creator', 'company', 'quantity', 'status', 'action'])
            ->make(true);
        }
        return view('admin.receipts.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Receipt Detail';
        $stock = Stock::where('id', $id)->first();
        return view('admin.receipts.show', compact('title', 'stock'));
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

    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remark' => 'required',
        ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }
    
    $stock = Stock::find($request->stock_status_id);
    $userPlayerId = UserPlayerId::where('user_id', $stock->user_id)->orderBy('id', 'DESC')->first();
    if($stock){
      DB::beginTransaction();
        if(isset($request->status_data) && $request->status_data == 2) {
            $stockStatus = 'Approved';
        }elseif(isset($request->status_data) && $request->status_data == 3) {
            $stockStatus = 'Rejected';
        }

        $updated = $stock->update([
            'remarks' => $request->remark, 
            'status' => $request->status_data,
        ]);
        try { 
            $responseMessage = "";
            if($updated){ 
                if(isset($userPlayerId->player_id) && !empty($userPlayerId->player_id)){
                    $fields['include_player_ids'] = [$userPlayerId->player_id];
                    $title = $stock->title;
                    $message = "Your receipt ".$stock->title." has ".$stockStatus;
                    $fields['headings'] = ['en' => $title];
                    $oneSignal = \OneSignal::sendPush($fields, $message);
                    if (isset($oneSignal['errors']) && !empty($oneSignal['errors'])) {
                        $responseMessage .=  $oneSignal['errors'][0] ?? ''; 
                    } 
                }
                $responseMessage .= "Receipt status Updated successfully"; 
                 DB::commit();
                return response()->json(['success' => true, "message" => $responseMessage ], 200);
            }else{
                DB::rollback();
                return response()->json(['success' => true, "message" => 'Receipt status not updated successfully'], 401);
            }
        }
        catch(\Exception $e) {
            DB::rollback();
             return response()->json(['success' => false, "message" =>  $e->getMessage()], 401);
        }
    }else{
        DB::rollback();
        return response()->json(['success' => false, "message" => 'No record found'], 401);
    }
}

    public function getSearchDataOnLoad(Request $request){
        $data['companies'] = Company::get();
        $data['users'] = User::get();
        return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
    }
}
