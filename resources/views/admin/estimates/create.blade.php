@extends('admin.layouts.app')
@section('title', $title.' | '.appName())
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0">
                                <span class="text-muted fw-light">Home /</span> {{ $title }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <form action="{{route('estimates.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label for="">Purchase Request </label>
                                <select name="request_id" class="form-control" id="" required>
                                    <option value="">--------</option>
                                    @if(isset($requests) && !empty($requests))
                                    @foreach($requests as $index => $value)
                                    <option value="{{$value->id}}">{{$value->subject ?? '-'}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <small>Leave this drop down empty if you wanna create an estimate on the basis of company</small>
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="">Company <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-control" id="">
                                    <option value="">--------</option>
                                    @if(isset($companies) && !empty($companies))
                                    @foreach($companies as $index => $value)
                                    <option value="{{$value->id}}">{{$value->name ?? '-'}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <!-- <input type="text" name="" id="company_name" class="form-control " readonly placeholder="Select Purchase Request First">
                                <input type="hidden" name="company_id" id=""> -->
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control " placeholder="Title of estimate">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="">Price <span class="text-danger">*</span></label>
                                <input type="text" name="price" id="price" class="form-control " placeholder="Estimated Price">
                            </div>
                            <div class="col-md-12 mb-5">
                                <label for="">Description</label>
                                <textarea name="description" id="" cols="30" rows="10" class="form-control" placeholder="Enter description"></textarea>
                            </div>
                            <div class="col-md-12 mb-5">
                                <label for="">Attachments</label>
                                <input type="file" name="attachments[]" id="" class="form-control" multiple>

                            </div>
                            <div class="col-md-6 mb-5">
                                <label for="">Status</label> (<small>Default status will always be pending</small>)
                                <h3 class="text-warning" style="font-weight: bold;">PENDING</h3>
                            </div>
                            <div class="col-md-6 mb-5 " style="text-align: right;">
                                <button type="submit" class="btn btn-success w-50">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

@endsection