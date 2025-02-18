<div class="kt-portlet__body">
	<div class="row">
		<div class="form-group col-md-4">
			<label>Name</label>
			<input type="text" class="form-control" placeholder="Enter Name" name="name" required>
		</div>
		<div class="form-group col-md-4">
			<label>Price</label>
			<input type="number" class="form-control" placeholder="Enter Price" name="price" required>
		</div>
		
	</div>
	 <div class="row">
        <div class="col-lg-3">
            @include('layouts.status_checkbox',array('data' => ""))
        </div>
        
    </div>
</div>