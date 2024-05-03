@foreach($permission as $value)
  
<span class="badge bg-label-success" data-bs-toggle="tooltip" title="{{$value->name}}">{{ucfirst($value->display_name)}}</span>
 
@endforeach
