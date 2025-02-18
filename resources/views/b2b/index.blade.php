@extends('main')
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid_item kt-grid_item--fluid">
	<br>
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">
                                    B2B
                                </h3>
                            </div>
                            <!-- <div class="kt-portlet__head-toolbar">
                                <div class="kt-portlet__head-wrapper">
                                    <div class="kt-portlet__head-actions">
                                        <a href="{{ route('b2b.create') }}" class="btn btn-brand btn-elevate btn-icon-sm"><i class="la la-plus"></i>Add B2B</a>
                                    </div>
                                </div>
                            </div> -->
                        </div>
		<div class="kt-portlet__body">
			<div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4">
				<table class="table table-striped table-bordered table-hover table-checkable datatable" id="datatable_rows">
					@csrf
					<thead>
						<tr>
							<th>Shop</th>
							<th>Employee</th>
							<th>Name</th>
							<th>Number</th>
							<th>Total Qty</th>
							<th>Total Amount</th>
							<!-- <th>GST %</th> -->
							<!-- <th>GST Amount</th> -->
							<!-- <th>Grand Total</th> -->
							<!-- <th>Status</th> -->
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		@include('layouts.multiple_action', array(
		'table_name' => 'b2b',
		'is_orderby'=>'',
		'folder_name'=>'',
		'action' => array('change-status-1' => _('Active'), 'change-status-0' => _('Inactive'))
		))
	</div>
</div>
@php
if($status_id != '')  {
$datatable_url = route('b2b.index.status', $status_id);

} else {

$datatable_url = route('b2b.index');

}

@endphp
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

			ajax: "{{ $datatable_url }}",

			columns: [
			{
				orderable: true,
				searchable: true,
				"data": "shops"
			},
			{
				orderable: true,
				searchable: true,
				"data": "usersname"
			},
			{
				orderable: true,
				searchable: true,
				data: "name"
			},
			{
				orderable: true,
				searchable: true,
				data: "number"
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
			// {
			// 	orderable: true,
			// 	searchable: true,
			// 	data: "gst"
			// },
			// {
			// 	orderable: true,
			// 	searchable: true,
			// 	data: "gst_amount"
			// },
			// {
			// 	orderable: true,
			// 	searchable: true,
			// 	data: "total_after_gst"
			// },
			// {
			// 	orderable: false,

			// 		searchable: false,

			// 		data: 'singlecheckbox',

			// },
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