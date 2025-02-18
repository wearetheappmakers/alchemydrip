<div class="kt-portlet__body">
	<div class="row">
		
		<div class="form-group col-lg-3">
			<label>Name</label>
			<input type="text" class="form-control" placeholder="Name" name="name" required value="{{$data->name}}">
		</div>
		<div class="form-group col-lg-3">
			<label>E-Mail</label>
			<input type="email" class="form-control" placeholder="Email Id" name="email" required value="{{$data->email}}">
		</div>

		
		<div class="form-group col-lg-3">
			<label>Password</label>
			<input type="text" class="form-control" placeholder="Password" readonly value="{{$data->show_password}}">
		</div>
		<div class="form-group col-lg-3">
			<label>Change Password</label>
			<input type="text" class="form-control" placeholder="Password" name="password">
			<span>If you want to change password else keep it as blank.</span>
		</div>
		<div class=" form-group col-lg-3">
			<label>Role</label>
			<select class="form-control" name="role" id="role" required>
				<option value="">Select Role</option>
				<!-- <option value="1" @if($data->role == 1) selected @endif>Supar Admin</option> -->
				<option value="2" @if($data->role == 2) selected @endif>Admin</option>
				<option value="3" @if($data->role == 3) selected @endif>Sales</option>
				<option value="4" @if($data->role == 4) selected @endif>Manager</option>
			</select>
		</div>
		<div class="form-group col-lg-3" id="shop" @if($data->role == 2 || $data->role == 4) style="display: none;" @else style="display:block;"@endif>
				<label>Shop</label>
				<select name="shop_id" class="form-control" >
					<option>Select Shop</option>
					@foreach($shop as $sub)
					<option value="{{$sub->id}}" @if($sub->id == $data->shop_id) selected @endif>{{$sub->name}}</option>
					@endforeach
				</select>		
			</div>

	</div>
	<div class=" form-group col-lg-4">
		@include('layouts.status_checkbox',array('data' => $data->status))
	</div>
</div>

<script type="text/javascript">


$(function () {
        $("#role").change(function () {
            if ($(this).val() == "3") {
                $("#shop").show();
            } else {
                $("#shop").hide();
            }
        });
    });



</script>
