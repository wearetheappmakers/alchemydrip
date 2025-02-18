<div class="kt-portlet__body">
	<div class="row">
		
		<div class="form-group col-lg-3">
			<label>User Name</label>
			<input type="text" class="form-control" placeholder="Enter Name" name="name" required>
		</div>
		<div class="form-group col-lg-3">
			<label>E-Mail</label>
			<input type="email" class="form-control" placeholder="Enter Email" name="email" required>
		</div>
		<div class="form-group col-lg-3">
			<label>Password</label>
			<input type="password" class="form-control" placeholder="Enter Password" name="password" required>
		</div>	
	</div>

	<div class="row">
		<div class=" form-group col-lg-3">
			<label>Role<span class="requied_field"></span></label>
			<select class="form-control" name="role" id="role">
				<option value="">Select</option>
				<!-- <option value="1">Super Admin</option> -->
					<option value="2">Admin</option>
					<option value="3">Sales</option>
					<option value="4">Manager</option>
			</select>
		</div>
		<div class="form-group col-lg-3" id="shop">
				<label>Shop</label><br>
				<select name="shop_id" class="form-control" >
					<option value="">Select</option>
					@foreach($shop as $sub)
					<option value="{{$sub->id}}">{{$sub->name}}</option>
					@endforeach
				</select>		
			</div>
	</div>
	 <div class="row">
        <div class="col-lg-3">
            @include('layouts.status_checkbox',array('data' => ""))
        </div>
        
    </div>
</div>
<script type="text/javascript">


$(function () {
     $('#shop').hide();
     $('#role').change(function () {
         $('#shop').hide();
         if (this.options[this.selectedIndex].value == '3') {
             $('#shop').show();
         }
     });
 });
</script>