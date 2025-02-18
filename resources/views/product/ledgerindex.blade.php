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
			<form action="{{ route('ledger.pdf') }}" method="post">
				@csrf
				<div class="row">
					<div class="col-md-1">
						<label>Shop</label>
						<select class="form-control onchanging" name="shops" id="shops">
							<option value="">Select</option>
							@foreach($shop as $row)
							<option value="{{ $row->id }}">{{ $row->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-1">
						<label>Date:</label>
						<input type="date" name="from_date" id="from_date" class="form-control onchanging" required>
					</div>
					<!-- <div class="col-md-1">
						<label>To Date:</label>
						<input type="date" name="to_date" id="to_date" class="form-control onchanging">
					</div> -->
					<div class="col-md-1">
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
						<button type="submit" class="btn btn-primary" id="exportPDF" >Export PDF</button>
					</div>
					<!-- <div class="col-md-1">
						<br>
						<button type="button" class="btn btn-primary" id="main_data" >All</button>
					</div> -->
				</div>
			</form>
			<br>
			<div id="main_table">
				
			</div>
			<br>
			<div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4">
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
			</div>
		</div>
		@include('layouts.multiple_action', array(
		'table_name' => 'product',
		'is_orderby'=>'',
		'folder_name'=>'',
		'action' => array()
		))
	</div>
</div>
@php

$datatable_url = route('ledger.index');

@endphp

@stop
@push('scripts')

<script>

	$(document).ready(function() {

		$("#main_data").on("click", function(e)
		{
			var shops = $('#shops').val();
			var from_date = $('#from_date').val();
			var to_date = $('#to_date').val();

			$.ajax({
				type: "POST",
				url: "{{ route('ledger.index.new') }}",
				data: {
					'_token': "{{ csrf_token() }}",
					'shops': shops,
					'from_date': from_date,
					'to_date': to_date,
				},
				success: function(data)
				{
					$('#main_table').html();
					$('#main_table').html(data);
				},
			});
		});

		var oTable = $('#datatable_rows').DataTable({

			processing: true,
			serverSide: true,
			searchable: true,
			scrollX: true,
			stateSave: true,
			pageLength: 1000,
			lengthMenu: [10, 25, 50, 100, 500, 1000],
			columnDefs: [{
				orderable: false,
				targets: -1,
			}],
			dom: "lfrtip",
			// ajax: "{{ route('ledger.index') }}",
			ajax: {
				url:'{{ $datatable_url }}',
				data: function(d) {
					d.from_date=$('#from_date').val(), 
					d.to_date=$('#to_date').val(),
					d.shops= $('#shops').val()
				}
			},
			columns: [
			{
				orderable: true,
				searchable: true,
				data: "so_id"
			},
			{
				orderable: true,
				searchable: true,
				data: "actual_amount"
			},
			{
				orderable: true,
				searchable: true,
				data: "cgst"
			},
			{
				orderable: true,
				searchable: true,
				data: "sgst"
			},
			{
				orderable: true,
				searchable: true,
				data: "credit"
			},
			{
				orderable: true,
				searchable: true,
				data: "debit"
			}
			]
		});

		setInterval(getTotal,1000);
		function getTotal()
		{
			var grid = document.getElementById("datatable_rows");
			var rows = grid.getElementsByTagName("TR");
			var amount = 0;
			var debitamount = 0;
			var cgst = 0;
			var actual = 0;

			for (var i = 1; i < rows.length; i++) {
				var cells = rows[i].getElementsByTagName("TD");
				amount += parseFloat(cells[4].innerHTML);
				
			}
			$('#credit').val(amount);

			for (var i = 1; i < rows.length; i++) {
				var cells = rows[i].getElementsByTagName("TD");
				actual += parseFloat(cells[1].innerHTML);
				
			}
			$('#actual').val(actual.toFixed(3));

			for (var i = 1; i < rows.length; i++) {
				var cells = rows[i].getElementsByTagName("TD");
				cgst += parseFloat(cells[2].innerHTML);
				
			}
			$('#cgst').val(cgst.toFixed(3));
			$('#sgst').val(cgst.toFixed(3));

			for (var i = 1; i < rows.length; i++) {
				var cells = rows[i].getElementsByTagName("TD");
				debitamount += parseFloat(cells[5].innerHTML);
				
			}
			$('#debit').val(debitamount);

			var balance = parseFloat(amount) - parseFloat(debitamount);

			$('#balance').val(balance);
		}
		$('.onchanging').change(function(){

			oTable.draw();
		});
	});

</script>



@endpush