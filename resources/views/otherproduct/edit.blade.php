<div class="kt-portlet__body">
	<div class="row">

		<div class="form-group col-md-4">
			<label>Name</label>
			<input type="text" class="form-control" placeholder="Enter Name" name="name" required value="{{$data->name}}">
		</div>
		<div class="form-group col-md-4">
			<label>Price</label>
			<input type="number" class="form-control" placeholder="Enter Price" name="price" required value="{{$data->price}}">
		</div>	
	</div>
	<div class=" form-group col-lg-4">
		@include('layouts.status_checkbox',array('data' => $data->status))
	</div>
</div>