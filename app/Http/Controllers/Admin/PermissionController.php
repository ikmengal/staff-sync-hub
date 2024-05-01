<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Support\Str;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('permissions-list');
        $title = 'All Permissions';


        $model = Permission::groupBy('label')
            ->select('label')
            
            ->get();


        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('created_at', function ($permission) {
                    return Carbon::parse($permission->created_at)->format('d, M Y');
                })
                ->editColumn('label', function ($permission) {
                    return '<span class="text-primary">' . Str::upper($permission->label) . '</span>';
                })
                ->addColumn('permissions', function ($permission) {
                    return view('admin.permissions.permissions', ['permission' => $permission])->render();
                })

                ->addColumn('action', function ($permission) {
                    return view('admin.permissions.tickets-action', ['permission' => $permission])->render();
                })
                ->rawColumns(['permissions', 'label', 'action'])
                ->make(true);
        }


        return view('admin.permissions.index', compact('title'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {

       
    //     $request['label'] = Str::lower($request->name);
    //     try {
    //              $rules = [
    //             'name' => 'required:unique:permissions,name',
               
    //         ];

    //         $message = [
    //             'name.unique' => 'This permission name has already been taken.'
    //         ];

    //     $validator = Validator::make($request->all(), $rules, $message);
      
    //     if ($validator->fails()) {
          
    //         return response()->json(['success' => false, 'message' => $validator->errors()->toArray(), 'validation' => false]);
    //     }
    //         $input_permissions = $request->permissions;
    //         if (!empty($request->custom)) {
    //             $input_permissions[] = $request->custom;
    //         }

    //         if (!empty($input_permissions)) {
    //             foreach ($input_permissions as $permission) {
    //                 Permission::create([
    //                     'label' =>  Str::lower($request->name),
    //                     'name' =>  str_replace(' ', '_', Str::lower($request->name)) . '-' . $permission,
    //                     'guard_name' => 'web',
    //                 ]);
    //             }
    //         }

            
          
    //         return ['success' => true, 'message' => 'Permission successfuly created', 'status' => 200];
    //     } catch (\Exception $e) {
          
    //         return ['success' => false, 'message' => 'Permission not created, something went wrong', 'status' => 501];
    //     }
    // }

    public function store(Request $request)
    {
        $this->authorize('permissions-create');
        
        $rules = [
            'name' => 'required:unique:permissions,name',
            'permissions' => 'array|min:1|required',
            'permission.*' => 'required|string',
        ];

        $message = [
            'name.unique' => 'This permission name has already been taken.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors()->toArray(), 'validation' => false];
        }

        if (!empty($request->permissions)) {
            foreach ($request->permissions as $display_name) {
                $name = setPermissionName($request->name, $display_name) ?? null;
                $result = Permission::create([
                    'label' =>  isset($request->name) ? ucfirst($request->name) : null,
                    'name' =>  $name ?? null ,
                    'display_name' => ucfirst($display_name),
                    'guard_name' => 'web',
                ]);
              
            }
        }

        if (isset($result) && !empty($result)) {
            return ['success' => true, 'message' => 'Permission successfuly created', 'status' => 200];
        } else {
            return ['success' => false, 'message' => 'Permission not created, something went wrong', 'status' => 501];
        }

      
    }


    
    public function edit($id)
    {
        $this->authorize('permissions-edit');
        $permission = Permission::where('label', $id)->first();
        if (isset($permission) && !empty($permission)) {
            $view = view('admin.permissions.edit', compact('permission'))->render();
            return ['success' => true, 'view' => $view];
        } else {
            return ['success' => false, 'message' => 'Permission not found'];
        }
    }

    public function update(Request $request, $id)
    {
      
        $rules = [
            'name' => 'required:unique:permissions,name,'.$id,
            'custom_permission' => 'required',
        ];

        $message = [
            'name.unique' => 'This permission name has already been taken.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return ['success' => false, 'message' => $validator->errors(), 'validation' => false];
        }
        $permission = Permission::where('id', $id)->first();
        
        if(isset($permission) && !empty($permission)) {
            if (!empty($request->custom_permission)) {
                $name = setPermissionName($permission->label, $request->custom_permission) ?? null;
                $result = Permission::create([
                    'label' =>  isset($permission->label) ? ucfirst($permission->label) : null,
                    'name' =>  $name ?? null ,
                    'display_name' => ucfirst($request->custom_permission),
                    'guard_name' => 'web',
                ]);
              
            }
    
            if (isset($result) && !empty($result)) {
             
                $result = ['success' => true, 'message' => 'Permission successfuly updated', 'status' => 200];
            } else {
                $result = ['success' => false, 'message' => 'Permission not updated, something went wrong', 'status' => 501];
            }
        } else {
            $result = ['success' => false, 'message' => 'Permission not found', 'status' => 501];
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     
      
        $this->authorize('permission-delete');
        $find = Permission::where('label', $id);
        if (isset($find) && !empty($find)) {
         
            $model = $find->delete();
            if ($model) {
     
                return response()->json([
                    'status' => true,
                ]);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    
}
