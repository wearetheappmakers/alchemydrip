
<div class="kt-portlet__body">

	<div class="form-group row">

		<ul class="nav nav-pills nav-fill" role="tablist">

			<li class="nav-item">

				<a class="nav-link active" data-toggle="tab" href="#kt_tabs_5_1">General</a>

			</li>

			<li class="nav-item">

				<a class="nav-link" data-toggle="tab" href="#kt_tabs_size">Size</a>

			</li>

			<li class="nav-item">

				<a class="nav-link" data-toggle="tab" href="#kt_tabs_gender">Gender</a>

			</li>


			<li class="nav-item">

				<a class="nav-link" data-toggle="tab" id="kt_tabs_inventory_tab" class="kt_tabs_inventory" href="#kt_tabs_inventory">Inventory</a>

			</li>


			<li class="nav-item">

				<a class="nav-link" data-toggle="tab"  id="kt_tabs_price_tab"  href="#kt_tabs_price">Price</a>

			</li>

		</ul> 

	</div>

	<div class="tab-content">

		<div class="tab-pane active" id="kt_tabs_5_1" role="tabpanel">

			@include('product.general')

		</div>


		<div class="tab-pane" id="kt_tabs_size" role="tabpanel">

			@include('product.size')

		</div>

		<div class="tab-pane" id="kt_tabs_gender" role="tabpanel">

			@include('product.gender')

		</div>


		<div class="tab-pane" id="kt_tabs_inventory" role="tabpanel">

			@include('product.inventory')

		</div>



		<div class="tab-pane" id="kt_tabs_price" role="tabpanel">

			@include('product.price')

		</div>

	</div>

</div>

@push('scripts')

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on("click", "#kt_tabs_inventory_tab", function(e) {
			e.preventDefault();

			$.ajax({

				type: "Get",

				url: "{{ route('product.inventory_update') }}",

				data: {

					'product_id' : "{{$data->id}}"

				},

				dataType: 'html',

				success: function(data) {

					$('#kt_tabs_inventory').html(data);

				}

			});

			return false;

		});
		$(document).on("click", "#inventory_update", function(e) {

			e.preventDefault();
			$.ajax({
				type: "POST",

				url: "{{ route('product.inventory_update') }}",

				data: new FormData($('.product_inventory_form')[0]),

				processData: false,

				contentType: false,

				success: function(data) {

					if (data.status === 'success') {

						toastr["success"]("Inventory Update Successfully", "Success");

					} else if (data.status === 'error') {

						toastr["error"]("Something went wrong", "Error");

					}

				}

			});

			return false;

		});
		$(document).on("click", "#kt_tabs_price_tab", function(e) {

			

            e.preventDefault();

            $.ajax({

                type: "Get",

                url: "{{ route('product.price_update') }}",

                data: {

					'product_id' : "{{$data->id}}"

				},

				dataType: 'html',

                success: function(data) {

                     $('#kt_tabs_price').html(data);

                }

            });

            return false;

		});

		 $(document).on("click", "#price_update", function(e) {
            e.preventDefault();

            $.ajax({

                type: "POST",

                url: "{{ route('product.price_update') }}",

                data: new FormData($('#product_price_form')[0]),

                processData: false,

                contentType: false,

                success: function(data) {

                    if (data.status === 'success') {
                        // location.reload();
                        toastr["success"]("Price Update Successfully", "Success");

                    } else if (data.status === 'error') {

                        toastr["error"]("Something went wrong", "Error");

                    }

                }

            });

            return false;

		});
	});
</script>
@endpush