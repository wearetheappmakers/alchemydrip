@extends('main')
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid_item kt-grid_item--fluid">
	<br>
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Employee Report
				</h3>
			</div>
		</div>
		<div class="kt-portlet__body">
			<div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4">
				<form class="add_form" id="add_form">
					@csrf
					<div class="row">
						<div class="form-group col-lg-2">
							<label>Shop :</label>
							<select class="form-control" name="shop_id" id="shop_id" required>
								<option value="">Select</option>
								@foreach($shop as $row => $value)
								<option value="{{ $value->id }}">{{ $value->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-lg-2">
							<label>From Date :</label>
							<input type="date" class="form-control" name="from_date" id="from_date" required>
						</div>
						<div class="form-group col-lg-2">
							<label>To Date :</label>
							<input type="date" class="form-control" name="to_date" id="to_date" required>
						</div>
						<div class="col-lg-1">
							<br>
							<button type="button" class="btn btn-primary" id="submit">Get Data</button>
						</div>
						<div class="col-lg-1">
							<br>
							<a href="{{ route('report.employee') }}" class="btn btn-primary">Refresh</a>
						</div>
					</div>
				</form>

				<div id="get_data" style="width: 50%;">
					
				</div>
			</div>
		</div>
	</div>
</div>

@stop
@push('scripts')

<script>

	$(document).ready(function() {

		$("#submit").on("click", function(e) {
			e.preventDefault();

			if ($(".add_form").valid()) {
				$.ajax({
					type: "POST",
					url: "{{ route('report.employee.getdata') }}",
					data: new FormData($('.add_form')[0]),
					processData: false,
                    contentType: false,
					success: function(data)
					{
						if (data.status === 'success')
						{
							$('#get_data').html('');
							$('#get_data').html(data.html);
						}
					}
				});
			}
		});
	});

</script>



@endpush

