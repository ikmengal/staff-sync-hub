<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeRequisitionController extends Controller
{
    public function index(){
        $data = [];
        $data['title'] = 'Requisition';
        return view('admin.requisitions.index', compact('data'));
    }
}
