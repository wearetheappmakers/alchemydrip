@php
$edit = $data['edit'];
@endphp
<div class="kt-portlet__body">
    <div class="form-group row">
        <div class="col-lg-4">
            <label>Name:</label>
            <input type="text" class="form-control" value="{{$data->name}}" placeholder="Enter name" name="name" required>
        </div>
        <div class="col-lg-4">
            <label>Code:</label>
            <input type="text" class="form-control" value="{{$data->code}}" placeholder="Enter code" name="code" required>
        </div>
    </div>
     @include('layouts.status_checkbox',array('data' => $data->status))
</div>