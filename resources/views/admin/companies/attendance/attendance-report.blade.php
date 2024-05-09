@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@section('content')
@dd($data)
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-10">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }} <b>{{ $fullMonthName }}</b></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between border-bottom">
                
            </div>
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label>Company</label>
                        <select class="form-control ">
                            <option value=""></option>
                            @dd($companies)
                            @if (!empty($companies))
                                 @foreach ($companies as $index => $company)
                                 <option value="{{$index}}">{{$company}}</option>
                                     
                                 @endforeach                                
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Employees Filter </label>
                         <select class="form-control" id="employeeList"></select>
                    </div>
                    <div class="col-md-2">
                        <label class="d-block"></label>
                        <button class="btn btn-primary waves-effect waves-light w-100" data-current-month="{{ date('F') }}" id="Slipbutton">Select Month<i class="ti ti-chevron-down ms-2"></i></button>
                    </div>
                    <input type="hidden" id="getMonth" value="{{ $month }}" />
                    <input type="hidden" id="getYear" value="{{ $year }}" />
                    <div class="col-md-2">
                        <label class="d-block"></label>
                        <button type="button" disabled id="process" class="btn btn-primary d-none w-100" style="display:none">Processing...</button>
                        <button type="button" id="filter-btn" class="btn btn-primary monthly-attendance-filter-report-btn d-block w-100" data-show-url=""><i class="fa fa-search me-2"></i> Filter </button>
                    </div>
                </div>
            </div>
            <div class="card-header border-bottom">
                <span id="show-filter-attendance-content">
                    <div class="row">
                        <div class="col-12 ">
                            <table class="table  attendance-table">
                                <thead>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Working Days</th>
                                    <th>Regular</th>
                                    <th>Late In</th>
                                    <th>Early Out</th>
                                    <th>Half Days</th>
                                    <th>Absents</th>
                                </thead>
                               
                            </table>
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection