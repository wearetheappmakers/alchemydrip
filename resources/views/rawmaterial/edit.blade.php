<div class="kt-portlet__body">
	<div class="row">

		<div class="form-group col-md-4">
			<label>Raw Material Name</label>
			<input type="text" class="form-control" placeholder="Name" name="name" required value="{{$data->name}}">
		</div>	
	</div>
	<div class=" form-group col-lg-4">
		@include('layouts.status_checkbox',array('data' => $data->status))
	</div>
</div>