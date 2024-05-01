<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('name', function($model){
                    return $model->first_name .' '.$model->last_name;
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
               ->addColumn('created_at', function ($model) {
                    return formatDate($model->created_at);
                })
               
                ->addColumn('action', function($model){
                    return view('admin.users.action', ['user' => $model])->render();
                })
                ->rawColumns(['name','role','created_at', 'action'])
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
        $last_name = $request->last_name?? null;
        $result = $this->model::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $request->email ?? null,
            'password' => Hash::make('12345678'),
            'slug' => getSlug(),
         
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
        //
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
           

        ];

       

       

        $message = [
            'role_id.required' => "Please select role"
        ];

        $validator = Validator::make($request->all(), $roles, $message);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors(), 'validation' => false]);
        }
        $update = $this->model::where('id', $id)->first();

        $first_name =$request->first_name ?? null;
        $last_name =$request->last_name ?? null;
        $result = $update->update([
            'first_name' =>$request->first_name ?? null,
            'last_name' =>$request->last_name ?? null,
            'email' =>$request->email ?? null,
            'password' => Hash::make('12345678'),
       
         
        ]);

        if (isset($result) && !empty($result)) {


            $roles =$request->role_id;
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
}
