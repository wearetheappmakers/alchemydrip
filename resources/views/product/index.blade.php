
@extends('main')
<style type="text/css">
	div#import {

		/* IMPORTANT STUFF */
		overflow: hidden;
		position: relative;
		cursor:   pointer;   

		/* SOME CUSTOM STYLING */
		width:  90px;
		padding: 10px; 
		text-align: center;
		border: 1px solid green;
		font-weight: bold
		background: red;
	}

	input#products {
		height: 30px;
		cursor: pointer;
		position: absolute;
		top: 0px;
		right: 0px;
		font-size: 100px;
		z-index: 2;

		opacity: 0.0;
		filter: alpha(opacity=0); /* IE lt 8 */
		-ms-filter: "alpha(opacity=0)"; /* IE 8 */
		-khtml-opacity: 0.0; /* Safari 1.x */
		-moz-opacity: 0.0; /* FF lt 1.5, Netscape */
	}


</style>

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
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<form action="{{ url('productsImport') }}" id="productimport" method="POST" enctype="multipart/form-data" style="display:none;position: absolute;left: 65%;top: 10px;">
							@csrf
							<div id="import" class="btn btn-brand btn-info btn-icon-sm" style="float: right">
								<input type="file" name="products" accept=".csv,application/vnd.ms-excel,.xls,.xlsx" id="products" title="Select File to Import">
								Import
							</div>
						</form>
						<a href="#"
							class="btn btn-brand btn-elevate btn-icon-sm importfile">
							<i class="la la-upload"></i>
							Import
						</a>
						<a href="{{ url('productExport') }}" class="btn btn-brand btn-icon-sm"><i class="la la-download"></i>Export</a>
						<a href="{{ route('sampleDownload') }}" class="btn btn-brand btn-icon-sm"><i class="la la-download"></i>Download Sample</a>
						<a href="{{ route('product.create') }}" class="btn btn-brand btn-elevate btn-icon-sm"><i class="la la-plus"></i>Add Product</a>
					</div>
				</div>
			</div>
		</div>
		<div class="kt-portlet__body">
			<div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" id="datatable_rows">
					@csrf
					<thead>
						<tr>
							<th>School Name</th>
							<th>Product Name</th>
							<th>Product Code</th>
							<th>Status</th>
							<th>Action</th>
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
		'action' => array('change-status-1' => __('Active'), 'change-status-0' => __('Inactive'), 'delete' => __('Delete'), 'discount' => __('Discount'))
		))
	</div>
</div>

@stop
@push('scripts')

<script>

	$(document).ready(function() {

		$('#datatable_rows').DataTable({

			processing: true,
			serverSide: true,
			searchable: true,
			scrollX: true,
			stateSave: true,
			columnDefs: [{
				orderable: false,
				targets: -1,
			}],

			ajax: "{{ route('product.index') }}",

			columns: [
			{
				orderable: true,
				searchable: true,
				data: "school_id"
			},
			{
				orderable: true,
				searchable: true,
				data: "name"
			},
			{
				orderable: true,
				searchable: true,
				data: "code"
			},
			{
				orderable: false,

					searchable: false,

					data: 'singlecheckbox',

			},
			{
				orderable: false,
				searchable: false,
				'data': 'action',
			},
			]
		});
		$('.importfile').on('click', function() {
            $('#masterLength').trigger('click');
		});
		$('#products').change(function(evt) {
			var file = document.getElementById("products");
			if(file.files.length == 0 ){
				alert("No files selected");
			} else {
				$('#productimport').submit();
			}
		});
	});

</script>



@endpush