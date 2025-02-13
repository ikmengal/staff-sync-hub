<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $model;

    protected $currentUser;


    public function __construct()
    {
        $this->model = "App\Models\User";
        $this->currentUser = Auth::user();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $this->authorize('users-list');
        $data['per_page_records'] = 10;
        $data['title'] = "Users List";
        $model = $this->model::orderby('id', 'desc')->get();
        if ($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('name', function ($model) {
                    return view('admin.users.partials.user_profile', ['user' => $model])->render();
                })
                ->addColumn('role', function ($model) {
                    $role_name = "";
                    if (isset($model->roles)) {
                        foreach ($model->roles->pluck('name') as $key => $value) {
                            $role_name .= $value ?? "-";
                        }
                    }
                    return $role_name;
                })
                ->addColumn('user_type',function($model){
                    $user_type = "";
                    if($model->user_for_portal == 1 && $model->user_for_api == null){
                          $user_type = "Portal User";
                    }
                    if($model->user_for_portal == null && $model->user_for_api == 1){
                        $user_type = "Api User";
                    }
                    if($model->user_for_portal == 1 && $model->user_for_api == 1){
                        $user_type = "Both";

                    }
                    return $user_type;
                })
                ->addColumn('phone_number',function($model){

                    if(isset($model->profile) && !empty($model->profile->phone_number)){

                        return $model->profile->phone_number;

                    }else{
                        return '-';
                    }
                })
                ->addColumn('joining_date',function($model){
                    if(isset($model->profile) && !empty($model->profile->joining_date)){
                        return formatDate($model->profile->joining_date);
                    }else{
                        return '-';
                    }
                })
                ->addColumn('created_at', function ($model) {
                    return formatDate($model->created_at);
                })

                ->addColumn('action', function ($model) {
                    return view('admin.users.action', ['user' => $model])->render();
                })
                ->filter(function ($query) use ($request) {
                
                    if ($request->has('role')) {
                        $role = $request->role;
                        $query->collection = $query->collection->filter(function ($record) use ($role) {
                            return str_contains(strtolower($record['role']), strtolower($role));
                        });
                    }
                    
                  
                })
               
                ->rawColumns(['name', 'role','user_type','phone_number','joining_date','created_at', 'action'])
                ->make(true);
        }

        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Add User";
        $roles = Role::get();
        $view = view('admin.users.create', compact('roles'))->render();
        return ['success' => true, 'view' => $view, 'title' => $title];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('users-create');
        $roles = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'array|min:1|required',
            'role_id.*' => 'required',
            'user_type' => 'required',
            "password"=>'required|confirmed'


        ];




        $message = [
            'role_id.required' => "Please select role"
        ];

        $validator = Validator::make($request->all(), $roles, $message);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->toArray(), 'validation' => false]);
        }

        $user = new $this->model;
        $first_name = $request->first_name ?? null;
        $last_name = $request->last_name ?? null;
        $user_portal = null;
        $user_api = null;
        if ($request->user_type == 1) {
            $user_portal = 1;
            $user_api = null;
        }
        if ($request->user_type == 2) {
            $user_portal = null;
            $user_api = 1;
        }
        if ($request->user_type == 3) {

            $user_portal = 1;
            $user_api = 1;
        }
        $result = $this->model::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $request->email ?? null,
            'slug' => getSlug(),
            'user_for_portal' => $user_portal,
            'user_for_api' => $user_api,
            'password' =>  Hash::make($request->password) ?? null

        ]);


        if (isset($result) && !empty($result)) {
            $roles = $request->role_id;
            $result->syncRoles($roles);
            $result = ['success' => true, 'message' => 'User successfuly created', 'status' => 200];
        } else {
            $result = ['success' => false, 'message' => 'User not created, something went wrong', 'status' => 501];
        }

        return $result;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = "User Detail";
        $user = User::where('id',$id)->first();
        if(!empty($user)){

            return view('admin.users.show',compact('user','title'));

        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('users-edit');
        $title = "Edit User";
        $roles = Role::get();
        $edit = $this->model::where('id', $id)->first();
        if (isset($edit) && !empty($edit)) {
            $view = view('admin.users.edit', compact('roles', 'edit'))->render();
            return ['success' => true, 'view' => $view, 'title' => $title];
        } else {
            return ['success' => false, 'message' => 'User not found'];
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('users-edit');
        $roles = [
            'first_name' => 'required',
            'email' => "required|email|unique:users,email,$id,id,deleted_at,NULL",
            'role_id' => 'array|min:1|required',
            'role_id.*' => 'required',
            'password' => 'confirmed'
          


        ];

        $message = [
            'role_id.required' => "Please select role"
        ];

        $validator = Validator::make($request->all(), $roles, $message);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(), 'validation' => false]);
        }
        $update = $this->model::where('id', $id)->first();

        $first_name = $request->first_name ?? null;
        $last_name = $request->last_name ?? null;
        $user_portal = null;
        $user_api = null;
        if ($request->user_type == 1) {
            $user_portal = 1;
            $user_api = null;
        }
        if ($request->user_type == 2) {
            $user_portal = null;
            $user_api = 1;
        }
        if ($request->user_type == 3) {

            $user_portal = 1;
            $user_api = 1;
        }
        
        $result = $update->update([
            'first_name' => $request->first_name ?? null,
            'last_name' => $request->last_name ?? null,
            'email' => $request->email ?? null,
            'user_for_portal' => $user_portal,
            'user_for_api' => $user_api,
            'password' =>  Hash::make($request->password) ?? $update->password


        ]);

        if (isset($result) && !empty($result)) {


            $roles = $request->role_id;
            $update->syncRoles($roles);
            $result = ['success' => true, 'message' => 'User successfuly updated', 'status' => 200];
        } else {
            $result = ['success' => false, 'message' => 'User not updated, something went wrong', 'status' => 501];
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('users-delete');
        $record = $this->model::find($id);
        if (isset($record) && !empty($record)) {
            $result = $record->delete();
            if ($result) {

                return ['success' => true, 'message' => 'User deleted successfully', 'status' => 200];
            } else {
                return ['success' => false, 'message' => 'User not deleted', 'status' => 501];
            }
        } else {
            return ['success' => false, 'message' => 'User not found', 'status' => 501];
        }
    }

    public function directPermission($id)
    {
        $title = "Assign Permission";
        $allPermissions = Permission::groupBy('label')
            ->select('label')
            ->get();
        $user = $this->model::where('id', $id)->first();

        $userPermissions = "";
        if (isset($user->permissions) && !empty($user->permissions)) {
            $userPermissions = $user->permissions->pluck('id')->toArray() ?? null;
        }

        if (isset($user) && !empty($user)) {
            $view = view('admin.users.partials.direct_permission_modal', compact('allPermissions', 'userPermissions', 'id', 'title'))->render();
            return ['success' => true, 'view' => $view];
        } else {
            return ['success' => true, 'message' => 'user not found'];
        }
    }

    public function storeDirectPermission(Request $request)
    {
        $roles = [
            'permissions' => 'array|min:1|required',
            'permissions.*' => 'required',
        ];

        $message = [
            'permissions.required' => "Please select permissions"
        ];

        $validator = Validator::make($request->all(), $roles, $message);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(), 'validation' => false]);
        }
        $user = $this->model::find($request->user_id);
        if (!empty($user) && !empty($user)) {
            if (!empty($user->getDirectPermissions())) {
                foreach ($user->getDirectPermissions() as $index => $value) {
                    $user->revokePermissionTo($value);
                }
            }

            if (!empty($request->permissions)) {
                $permissions = Permission::whereIn("id", $request->permissions)->get();
            }

            if (!empty($permissions)) {
                foreach ($permissions as $permission) {
                    if (!empty($user)) {
                        $user->givePermissionTo($permission->name);
                    }
                }
                return ['success' => true, 'message' =>  'Direct permission assign the user', 'status' => 200];
            }
        } else {
            return ['success' => false, 'message', 'User not found', 'status' => 404];
        }
    }

    public function updatePasswordForm(Request $request)
    {
        $this->authorize('users-edit-password');
        $title = "Edit Password";
        $edit = $this->model::where('id', $request->id)->first();
        if (isset($edit) && !empty($edit)) {
            $view = view('admin.users.partials.update_password_modal', compact('edit','title'))->render();
            return ['success' => true, 'view' => $view, 'title' => $title];
        } else {
            return ['success' => false, 'message' => 'User not found'];
        }
    }

    public function updatePassword(Request $request){

        $roles = [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ];

      

        $validator = Validator::make($request->all(), $roles);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'validation' => false]);
        }

        $user = $this->model::where('id',$request->user_id)->first();
        if (!Hash::check($request->current_password, $user->password)) {

            return  ['success' => true, 'message' =>  'Current password is incorrect.', 'status' => 500];
        }

        $user->password = Hash::make($request->new_password);
        if($user->save()){
            return  ['success' => true, 'message' =>  'Password updated successfully.', 'status' => 200];
        }

    }

    public function getSearchData(){
        $data['roles'] = Role::get();
        return ['success' => true, 'message' => null, 'data' => $data, 'status' => 200];
    }

}
