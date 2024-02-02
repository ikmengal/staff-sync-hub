<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // return getAllCompaniesEmployees();
        // return getAllCompanies();
        // return getAllCompaniesVehicles()['successGoalPercent'];
        $data = [];
        $data['title'] = 'Dashboard';
        return view('admin.dashboards.dashboard', compact('data'));
    }

    public function loginForm()
    {
        $title = 'Login';
        if (Auth::check()) {
            return redirect()->route('dashboard');
        } else {
            return view('admin.auth.login', compact('title'));
        }
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $user = Auth::user();
            if ($user->status == 1) {
                //Remember me
                if($request->has('remember') && !empty($request->remember)){
                    setcookie("email", $request->email, time()+3600);
                    setcookie("password", $request->password, time()+3600);
                }else{
                    setcookie("email", "");
                    setcookie("password", "");
                }
                return response()->json(['success' => true]);
            } else {
                Auth::logout(); // Log out the user if they are not active
                return response()->json(['error' => 'Your account is not active.']);
            }
        } else {
            return response()->json(['error' => 'Invalid credentials']);
        }
    }

    public function logOut()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('admin.login');
        }
    }
}
