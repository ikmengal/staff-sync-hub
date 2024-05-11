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
                <a href="{{route('estimates.index')}}" class="btn btn-primary waves-effect waves-light">
                  <i class="fas fa-reply me-1"></i>
                </a>
              </h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-12 col-mb-6 col-lg-7 col-12 mt-3">
        <div class="card h-100">
          <div class="card-header">
            <h3>Purchase Request Information:</h3>
          </div>
          <div class="card-body">
            <div class="row mb-4 mb-5">
              <div class="col-md-4">
                <h5 class="card-title">Subject</h5>
                <div>
                  <p class="my-2">{{ $requestData->subject ?? '' }}</p>
                </div>
              </div>
              <div class="col-md-12">
                <h5 class="card-title">Description</h5>
                <div>
                  <p class="my-2">{!! $requestData->description ?? '' !!}</p>
                </div>
              </div>
            </div>
            <div class="row gy-3">
              <div class="col-md-4 col-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="card-info">
                    <h6 class="mb-2">Creator </h6>
                    <small>{{ $requestData->creator ?? '' }}</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="card-info">
                    <h6 class="mb-2">Company Name</h6>
                    <small>{{ $requestData->company->name ?? '' }}</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-6 mb-4">
                <div class="d-flex align-items-center">
                  <div class="card-info">
                    <h6 class="mb-2">Status</h6>
                    <small>
                      @if (isset($requestData->status) && $requestData->status == 1)
                      <span class="badge bg-label-warning" text-capitalized="">Pending</span>
                      @elseif(isset($requestData->status) && $requestData->status == 2)
                      <span class="badge bg-label-success" text-capitalized="">Approve</span>
                      @else
                      <span class="badge bg-label-danger" text-capitalized="">Rejected</span>
                      @endif
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @if(isset($records) && !empty($records))
      @foreach($records as $index => $record)
      <div class="card mt-4">
        <div class="card-header">
          <div class="row">
            <div class="col-md-6">
              <h3>Estimate #{{ ++$index }}</h3>
            </div>
            <div class="col-md-6" style="text-align: right;">
              @if($record->status == 1)
              <a href="javascript:;" class="btn btn-success btn-sm edit_estimate" data-estimate-id="{{$record->id}}" data-estimate-title="{{$record->title ?? ''}}" data-estimate-price="{{$record->price ?? ''}}">
                <i class="menu-icon tf-icons ti ti-check"></i>
              </a>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- <div class="col-md-6 mb-3">
              <h6>Purchase Request</h6>
              <p>{{ $record->requestData->subject ?? '' }}</p>
            </div> -->
            <div class="col-md-6 mb-3">
              <h6>Company</h6>
              <p>{{ $record->company->name ?? '' }}</p>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-6 mb-3">
              <h6>Title</h6>
              <p>{{ $record->title ?? '' }}</p>
            </div>
            <div class="col-md-6 mb-3">
              <h6>Price</h6>
              <p> Rs.{{ !empty($record->price) ? number_format($record->price) :  '0' }}</p>
            </div>
            <div class="col-md-12 mb-3">
              <h6>Description</h6>
              <p>{!! $record->description ?? '' !!}</p>
            </div>
            <div class="col-md-6 mb-3">
              <h6>Status</h6>
              <p>
                <span class="badge bg-label-{{ $record->getStatus->class ?? '' }}">{{ $record->getStatus->name ?? '' }}</span>
              </p>
            </div>
            @if(isset($record) && !empty($record->attachments))
            <div class="col-md-12 row mt-2">
              <h6 for="">Attachments</h6>
              @foreach ($record->attachments as $key => $item)
              <div class="col-md-2 mb-3">
                <img src="{{ asset('public/attachments/estimates')}}/{{$item->file}}" alt="No Image" class="img-fluid" style="max-width:100px !important">
              </div>
              @endforeach
            </div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
      @endif
    </div>

  </div>
</div>
</div>
<!-- edit estimate modal  -->
<div class="modal fade" id="edit-estimate-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered modal-add-new-role">
    <div class="modal-content p-3 p-md-5">
      <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">

        <div class="mb-4">
          <h3 class="role-title mb-2">Approve Estimate</h3>
        </div>
        <input type="hidden" name="" id="estimate_id">
        <div class="row">
          <div class="col-md-12">
            <div class="mb-1 fv-plugins-icon-container col-12">
              <label class="form-label" for="remark">Remarks<span class="text-danger">*</span></label>
              <textarea class="form-control" id="remarks" placeholder="Enter Remarks" name="remark" cols="15" rows="5"></textarea>
              <div class="fv-plugins-message-container invalid-feedback"></div>
              <span id="remarks_error" class="text-danger error"></span>
            </div>
          </div>
          <div class="col-md-12 mb-3">
            <select name="" class="form-control" id="estimate_status">
              <option value="2">Approved</option>
            </select>
            <span id="status_error" class="text-danger error"></span>
          </div>
        </div>

        <strong class="text-warning " style="font-size:15px; ">
          NOTE: <br> If you approves any estimate, the rest of the estiamates from the same purchase request will automatically be rejected!
        </strong>

        <div class="col-12 action-btn">
          <div class="demo-inline-spacing sub-btn">
            <button type="submit" id="approve_estimate" class="btn btn-primary me-sm-3 me-1  ">Approve</button>
            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
              Cancel
            </button>
          </div>
          <div class="demo-inline-spacing loading-btn" style="display: none;">
            <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
              <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
              Loading...
            </button>
            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- edit estimate modal  -->
@endsection
@push("js")
<script>
  $(document).on("click", ".edit_estimate", function() {
    $("#remarks_error").html("");
    $("#status_error").html("");
    var estimate_id = $(this).attr("data-estimate-id");
    var estimate_title = $(this).attr("data-estimate-title");
    var estimate_price = $(this).attr("data-estimate-price");
    $("#edit-estimate-modal").modal("show");
    $("#estimate_id").val(estimate_id)

  });
  $(document).on("click", "#approve_estimate", function() {
    $("#remarks_error").html("");
    var errors = [];
    var estimate_status = $("#estimate_status").val();
    var estimate_id = $("#estimate_id").val()
    if (!estimate_status) {
      errors.push(1)
      $("#status_error").html("Please select status");
    }
    var remarks = $("#remarks").val();
    if (!remarks) {
      errors.push(1)
      $("#remarks_error").html("Please insert remarks before approving the estimate");
    }
    if (errors.length > 0) {
      return false;
    } else {
      console.log(errors.length)
      $.ajax({
          url: "{{route('estimates.approve')}}",
          method: "POST",
          data: {
            _token: "{{csrf_token()}}",
            estimate_id: estimate_id,
            estimate_status: estimate_status,
            remarks: remarks,
          },
          beforeSend: function() {
            console.log("processing");
          },
          success: function(res) {
            if (res.success == true) {
              Swal.fire({
                text: res.message,
                icon: "success"
              });
              $("#edit-estimate-modal").modal("hide");
              var estimate_id = $("#estimate_id").val("")
              var remarks = $("#remarks").val("");

              setTimeout(() => {
                  location.reload() 
              }, 500);
          } else {
            Swal.fire({
              text: res.message,
              icon: "error"
            });
          }
        },
        error: function(xhr, status, error) {
          Swal.fire({
            text: error,
            icon: "error"
          });
        }
      });
  }


  });
</script>

@endpush