<div class="kt-portlet__body">
	<div class="row">

		<div class="form-group col-md-4">
			<label>Shop Name</label>
			<input type="text" class="form-control" placeholder="Name" name="name" required value="{{$data->name}}">
		</div>	
		<div class="form-group col-md-4">
			<label>Number</label>
			<input type="tel" class="form-control" placeholder="Enter Number" name="number" value="{{$data->number}}">
		</div>
		<div class="form-group col-md-4">
			<label>Address</label>
			<input type="text" class="form-control" placeholder="Enter address" name="address" value="{{$data->address}}">
		</div>

	</div>
	<div class=" form-group col-lg-4">
		@include('layouts.status_checkbox',array('data' => $data->status))
	</div>
</div>