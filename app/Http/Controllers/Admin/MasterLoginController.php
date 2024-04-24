<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterLoginController extends Controller
{
     public function login(Request $request , $company_id) {
        $company_id = base64_decode($company_id);
        dd($company_id);
     }
}
