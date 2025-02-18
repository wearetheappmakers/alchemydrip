@extends('main')
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid_item kt-grid_item--fluid">
	<br>
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    Purchase Order
                                </h3>
                            </div>
                            <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-wrapper">
                                    <div class="kt-portlet__head-actions">
                                        <a href="{{ route('po.create') }}" class="btn btn-brand btn-elevate btn-icon-sm"><i class="la la-plus"></i>Add Purchase Order</a>
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
							<th>Supplier Name</th>
							<th>Supplier Number</th>
							<th>Total Qty</th>
							<th>Total Amount</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		@include('layouts.multiple_action', array(
		'table_name' => 'po',
		'is_orderby'=>'',
		'folder_name'=>'',
		'action' => array()
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

			ajax: "{{ route('po.index') }}",

			columns: [
			{
				orderable: true,
				searchable: true,
				data: "supplier_name"
			},
			{
				orderable: true,
				searchable: true,
				data: "supplier_number"
			},
			{
				orderable: true,
				searchable: true,
				data: "total_qty"
			},
			{
				orderable: true,
				searchable: true,
				data: "total_amount"
			},
			{
				orderable: false,
				searchable: false,
				'data': 'action',
			},
			]
		});

	});

</script>



@endpush