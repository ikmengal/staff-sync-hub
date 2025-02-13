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
use App\Models\Estimate;
use Illuminate\Support\Facades\File;
use Str;
use Storage;

class ReceiptController extends Controller
{
    public function companyIndex(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $company = Company::get();
            if (isset($company) && !blank($company)) {
                $data = CompanyResource::collection($company);
                return apiResponse(true, $data, "All comapnies.", 200);
            } else {
                return apiResponse(false, null, "No companies record.", 500);
            }
        }
    }

    public function index(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $user = User::where('id', $userToken->tokenable_id)->first();
            $stock = Stock::where('user_id', $user->id);
            $pageSize = 10;
            //date range
            //status
            // company id
            if (isset($request->company_id) && !empty($request->company_id)) {
                $stock =  $stock->where("company_id", $request->company_id);
            }
            if (isset($request->status) && !empty($request->status)) {
                $stock =  $stock->where("status", $request->status);
            }

            if (isset($request->date) && !empty($request->date)) {
                $stock =  $stock->whereDate("created_at", $request->date);
            }

            // date range

            if (isset($request->sorting) && !empty($request->sorting)) {
                if ($request->sorting == 1) {
                    $sorting = 'asc';
                } elseif ($request->sorting == 2) {
                    $sorting = 'desc';
                }
            } else {
                $sorting = 'desc';
            }
            $stock = $stock->orderBy("id", $sorting)->paginate($pageSize);
            if (isset($stock) && !blank($stock)) {
                $data = StockResource::collection($stock);
                return apiResponse(true, $data, "All Receipts", 200, [
                    'total' => $stock->total(),
                    'per_page' => $stock->perPage(),
                    'current_page' => $stock->currentPage(),
                    'last_page' => $stock->lastPage(),
                    'from' => $stock->firstItem(),
                    'to' => $stock->lastItem(),
                ]);
            } else {
                return apiResponse(false, null, "No Receipt found...!", 500);
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

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $validator = Validator::make($request->all(), [
                "estimate_id" => "required",
                'title' => 'required',
                'description' => 'required',
                'quantity' => 'required',
                'images.*' => 'nullable|file|mimes:png,jpg,jpeg,pdf',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 500);
            }
            $estimate = Estimate::where("id", $request->estimate_id)->first();
            if (!isset($estimate) || empty($estimate)) {
                return apiResponse(false, null, "Invalid Estimate", 500);
            }
            $user = User::where('id', $userToken->tokenable_id)->first();
            $company = Company::where('company_id', $request->company_id)->first();
            $stock = Stock::create([
                'user_id' => $user->id,
                'company_id' => $estimate->company_id,
                'estimate_id' => $estimate->id,
                'title' => $request->title,
                'description' => $request->description,
                'quantity' => $request->quantity,
            ]);

            if (isset($stock) && !empty($stock)) {
                if (isset($request->type) && $request->type == 2) {
                    if ($request->hasFile('images')) {
                        $files = $request->file('images');
                        foreach ($files as $index => $file) {
                            $originalName = $file->getClientOriginalExtension();
                            $mimeType = $file->getClientMimeType();
                            $index = $index + 1;
                            $type = explode('/', $file->getClientMimeType());
                            $fileType = isset($type[1]) && !blank($type[1]) ? $type[1] : NULL;
                            $fileName = "STOCK-IMAGE-" .  $index  . "-" . time() . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('admin/assets/img/stock/'), $fileName);

                            StockImage::create([
                                'stock_id' => $stock->id,
                                'image' => $fileName,
                                'type' => isset($fileType) ? ($fileType == 'pdf' ? 'pdf' : 'image') : NULL,
                            ]);
                        }
                    }
                } else if (isset($request->type) && $request->type == 1) {
                    // if ($request->has('images')) {
                    //     $image = $request->images;
                    //     $mime = explode(':', substr($image, 0, strpos($image, ';')))[1];
                    //     $mime = explode('/', $mime);
                    //     $image = str_replace('data:image/'.'$mime[1]'.';base64,', '', $image);
                    //     $image = str_replace(' ', '+', $image);
                    //     $index = 1;
                    //     $imageName = "STOCK-IMAGE-" . $index . "-" . time() . '.' . $mime[1];
                    //     $directory = public_path('admin/assets/img/stock/');
                    //     $filePath = $directory . $imageName;
                    //     \File::put($filePath, base64_decode($image));
                    //     StockImage::create([
                    //         'stock_id' => 10,
                    //         'image' => $imageName,
                    //         'type' => 'image',
                    //         'request_type' => 1,
                    //     ]);
                    // }
                    if ($request->has('images')) {
                        $image = $request->images;
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
                        $imageName = "STOCK-IMAGE-" . $index . "-" . time() . '.' . $extension;
                        $directory = public_path('admin/assets/img/stock/');
                        $filePath = $directory . $imageName;
                        File::put($filePath, base64_decode($image));
                        StockImage::create([
                            'stock_id' => $stock->id,
                            'image' => $imageName,
                            'type' => 'image',
                            'request_type' => 1,
                        ]);
                    }
                }
                $data = Stock::where("id", $stock->id)->with("hasImages")->first();
                $data = new StockResource($data);
                return apiResponse(true, $data, "Receipt added successfully.", 200);
            } else {
                return apiResponse(false, null, "Receipt not added...!", 500);
            }
        }
    }

    public function show(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $user = User::where('id', $userToken->tokenable_id)->first();
            $stock = Stock::where('user_id', $user->id)->where('id', $request->id)->first();
            if (isset($stock) && !empty($stock)) {
                $data = new StockResource($stock);
                return apiResponse(true, $data, "Stock Detail.", 200);
            } else {
                return apiResponse(false, null, "No stock record found...!", 500);
            }
        }
    }

    public function edit(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $user = User::where('id', $userToken->tokenable_id)->first();
            $stock = Stock::where('user_id', $user->id)->where('id', $request->id)->first();
            if (isset($stock) && !empty($stock)) {

                $data = [
                    'id' => $stock->id,
                    'estimate_id' => $stock->estimate_id,
                    'company_id' => $stock->company_id,
                    'title' => $stock->title,
                    'description' => $stock->description,
                    'quantity' => $stock->quantity,
                    'remarks' => $stock->remarks,
                ];
                return apiResponse(true, $data, "receipt detail.", 200);
            } else {
                return apiResponse(false, null, "No receipt record found...!", 500);
            }
        }
    }

    public function update(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {

            $validator = Validator::make($request->all(), [
                'company_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'quantity' => 'required',
                'remarks' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 500);
            }

            $user = User::where('id', $userToken->tokenable_id)->first();
            $stock = Stock::where('user_id', $user->id)->where('id', $request->id)->first();
            if (isset($stock) && !empty($stock)) {
                $stock->company_id = $request->company_id ?? $stock->company_id;
                $stock->estimate_id = $request->estimate_id ?? $stock->estimate_id;
                $stock->title = $request->title ?? $stock->title;
                $stock->description = $request->description ?? $stock->description;
                $stock->quantity = $request->quantity ?? $stock->quantity;
                $stock->remarks = $request->remarks ?? $stock->remarks;
                $stock->save();
                $data = new StockResource($stock);
                return piResponse(true, $data, "receipt updated successfully.", 200);
            } else {
                return apiResponse(false, null, "No receipt record found...!", 500);
            }
        }
    }

    public function receiptImageDelete(Request $request)
    {
        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $user = User::where('id', $userToken->tokenable_id)->first();
            $stockImage = StockImage::where('id', $request->id)->delete();

            if (isset($stockImage) && !empty($stockImage)) {
                return apiResponse(true, null, "Image deleted successfully.", 200);
            } else {
                return apiResponse(false, null, "No image found...!", 500);
            }
        }
    }

    public function receiptAddImage(Request $request)
    {
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '256M');

        if ($request->bearerToken() == "") {
            return apiResponse(false, null, "Enter token", 500);
        }

        $userToken = DB::table('personal_access_tokens')->where('id', $request->bearerToken())->first();

        if (empty($userToken)) {
            return apiResponse(false, null, "Unauthorized", 500);
        } else {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'images.*' => 'required|file|mimes:png,jpg,jpeg,pdf',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 500);
            }

            $user = User::where('id', $userToken->tokenable_id)->first();

            $stock = Stock::where('id', $request->id)->where('user_id', $user->id)->first();


            if (isset($stock) && !empty($stock)) {
                if (isset($request->type) && $request->type == 2) {
                    if ($request->hasFile('images')) {
                        $files = $request->file('images');
                        foreach ($files as $index => $file) {
                            $originalName = $file->getClientOriginalExtension();
                            $mimeType = $file->getClientMimeType();
                            $index = $index + 1;
                            $type = explode('/', $file->getClientMimeType());
                            $fileType = isset($type[1]) && !blank($type[1]) ? $type[1] : NULL;
                            $fileName = "STOCK-IMAGE-" .  $index  . "-" . time() . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path('admin/assets/img/stock/'), $fileName);

                            $create =   StockImage::create([
                                'stock_id' => $stock->id,
                                'image' => $fileName,
                                'type' => isset($fileType) ? ($fileType == 'pdf' ? 'pdf' : 'image') : NULL,
                                // 'request_type' => 2,
                            ]);
                        }
                    }
                } else if (isset($request->type) && $request->type == 1) {
                    if ($request->has('images')) {
                        $image = $request->images;
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
                        $imageName = "STOCK-IMAGE-" . $index . "-" . time() . '.' . $extension;
                        $directory = public_path('admin/assets/img/stock/');
                        $filePath = $directory . $imageName;
                        \File::put($filePath, base64_decode($image));
                        StockImage::create([
                            'stock_id' => $stock->id,
                            'image' => $imageName,
                            'type' => 'image',
                            'request_type' => 1,
                        ]);
                    }
                }
                return apiResponse(true, null, $index . " Images uploaded successfully.", 200);
            } else {
                return apiResponse(false, null, "Receipt not found...!", 500);
            }
        }
    }
}
