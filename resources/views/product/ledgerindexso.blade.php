@extends('main')
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid_item kt-grid_item--fluid">
	<br>
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head">
			<div class="kt-portlet__head-label">
				<h3 class="kt-portlet__head-title">
					Product
				</h3>
			</div>
			
		</div>
		<div class="kt-portlet__body">
			<form class="add_form" method="post" action="{{route('ledger.index.so.data.pdf')}}">
				@csrf
				<div class="row">
					<div class="col-md-1">
						<label>Shop</label>
						<select class="form-control onchanging" name="shops" id="shops" required>
							<option value="">Select</option>
							@foreach($shop as $row)
							<option value="{{ $row->id }}">{{ $row->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-1">
						<label>From Date:</label>
						<input type="date" name="from_date" id="from_date" class="form-control onchanging" required>
					</div>
					<div class="col-md-1">
						<label>To Date:</label>
						<input type="date" name="to_date" id="to_date" class="form-control onchanging" required>
					</div>
					<div class="col-md-1">
						<label>GST</label>
						<select class="form-control" name="gstin" id="gstin" required>
							<option value="">Select</option>
							<option value="5">5</option>
							<option value="12">12</option>
							<option value="18">18</option>
						</select>
					</div>
					<!-- <div class="col-md-1">
						<label>Total Actual Amount:</label>
						<input type="text" class="form-control" name="actual" id="actual" readonly>
					</div>
					<div class="col-md-1">
						<label>Total CGST:</label>
						<input type="text" class="form-control" name="cgst" id="cgst" readonly>
					</div>
					<div class="col-md-1">
						<label>Total SGST:</label>
						<input type="text" class="form-control" name="sgst" id="sgst" readonly>
					</div>
					<div class="col-md-1">
						<label>Total Credit:</label>
						<input type="text" class="form-control" name="credit" id="credit" readonly >
					</div>
					<div class="col-md-1">
						<label>Total Debit:</label>
						<input type="text" class="form-control" name="debit" id="debit" readonly>
					</div>
					<div class="col-md-1">
						<label>Total Balance:</label>
						<input type="text" class="form-control" name="balance" id="balance" readonly>
					</div>
					<div class="col-md-1">
						<br>
					</div> -->
					<div class="col-md-1">
						<br>
						<button type="button" class="btn btn-primary" id="main_data" >Get</button>
						<button type="submit" class="btn btn-primary" id="exportPDF" >Export PDF</button>
					</div>
				</div>
			</form>
			<br>
			<div id="main_table">
				
			</div>
			<br>
			<!-- <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" id="datatable_rows">
					@csrf
					<thead>
						<tr>
							<th>Order No</th>
							<th>Actual Amount</th>
							<th>CGST</th>
							<th>SGST</th>
							<th>Credit</th>
							<th>Debit</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div> -->
		</div>
	</div>
</div>

@stop
@push('scripts')

<script>
	$(document).ready(function() {
		$("#main_data").on("click", function(e)
		{
			var shops = $('#shops').val();
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();
			var gstin = $('#gstin').val();

			e.preventDefault();
			if ($(".add_form").valid()) {

				$.ajax({
					type: "POST",
					url: "{{ route('ledger.index.so.data') }}",
					data: {
						'_token': "{{ csrf_token() }}",
						'shops': shops,
						'from_date': from_date,
						'to_date': to_date,
						'gstin': gstin,
					},
					success: function(data)
					{
						$('#main_table').html();
						$('#main_table').html(data);
					},
				});
			}else{
				e.preventDefault();
			}
		});
	});

</script>



@endpush