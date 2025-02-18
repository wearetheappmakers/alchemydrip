<div class="kt-portlet__body">
	<div class="row">
		<div class="form-group col-md-4">
			<label>Shop Name</label>
			<input type="text" class="form-control" placeholder="Enter Name" name="name" required>
		</div>
		<div class="form-group col-md-4">
			<label>Number</label>
			<input type="tel" class="form-control" placeholder="Enter Number" name="number">
		</div>
		<div class="form-group col-md-4">
			<label>Address</label>
			<input type="text" class="form-control" placeholder="Enter address" name="address">
		</div>
		
	</div>
	<div class="row">
        <div class="col-lg-3">
            @include('layouts.status_checkbox',array('data' => ""))
        </div>
        
    </div>
</div>