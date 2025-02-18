@extends('main')
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
  <br>
  <div class="kt-portlet">
    <div class="kt-portlet__head">
      <div class="kt-portlet__head-label">
        <h3 class="kt-portlet__head-title">
          Edit Setting
        </h3>
      </div>
    </div>

    <form class="kt-form" class="edit_form" id="edit_form" method="POST">
      @method('put')
      @csrf
      <div class="kt-portlet__foot">
        <div class="row">
          <div class="form-group col-sm-6">
            <label>Logo</label>
            <input type="file" name="logo" value="{{$edit->logo}}" class="form-control" >
          </div>
          @if($edit->logo)
          <div class="form-group col-sm-3">
            <a href="{{ asset('/setting/'.$edit->logo) }}" target="_blank">
              <img src="{{ asset('/setting/'.$edit->logo) }}" height="100px" width="100px">
            </a>
          </div>
          @endif
        </div>
        <div class="row">
          <div class="form-group col-sm-6">
            <label>Name</label>
            <input type="text" name="name" value="{{$edit->name}}" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6">
            <label>Address-1</label>
            <input type="text" name="address" value="{{$edit->address}}" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6">
            <label>Address-2</label>
            <input type="text" name="address2" value="{{$edit->address2}}" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6">
            <label>Address-3</label>
            <input type="text" name="address3" value="{{$edit->address3}}" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6">
            <label>Email</label>
            <input type="email"  name="email" value="{{$edit->email}}" class="form-control">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6">
            <label>Contact</label>
            <input type="text" name="contact" value="{{$edit->contact}}" class="form-control">
          </div>
        </div>
        <div class="kt-form__actions">
          <center>
            <button type="reset" class="btn btn-brand submit" id="submit">Update</button>
          </center>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
  $(document).ready(function(){

    $("#submit").on("click",function(e){
      e.preventDefault();
      if($("#edit_form").valid()){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type:"POST",
          url:"{{ route('setting.update',1) }}",
          data: new FormData($('#edit_form')[0]),
          processData: false,
          contentType:false,

          success: function(data){
            if(data.status==='success'){
              toastr["success"](" Updated Successfully", "Success");
              location.reload();
            }else if(data.status==='error'){
              toastr["error"]("Unsuccessfull", "Error");
            }
          }
        });
      }else{
        e.preventDefault();
      }
    });
  });
</script>
@endpush