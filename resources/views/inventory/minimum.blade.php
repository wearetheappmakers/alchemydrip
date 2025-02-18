@extends('main')



@section('content')



<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />



<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

	<br>

	<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

		<div class="kt-portlet kt-portlet--mobile">

			<div class="kt-portlet__head kt-portlet__head--lg">

				<div class="kt-portlet__head-label">

					<h3 class="kt-portlet__head-title">

						Minimum Inventory

					</h3>

				</div>
			</div>

			<form id="inventory_form" class="inventory_form" name="inventory_form" method="POST">

				@csrf

				<div class="kt-portlet__body">

					<div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4">



						<table class="table table-striped table-bordered table-hover table-checkable datatable" id="datatable_rowsss">
							<thead>

								<tr>

									<th>#</th>

									<th>School</th>
									<th>Product Name</th>

									<th>Gender</th>

									<th>Size</th>



									<th>Inventory</th>

									<th>Used</th>
									<th>Remaining</th>
									<th>Minimum</th>


								</tr>



							</thead>



							<tbody>

								@foreach($get_data as $row)

								<tr>

									<td>{{$row->id}}</td>

									<td>{{$row->schoolname}}</td>
									<td>{{$row->product_name}}</td>

									<td>{{$row->gender_name}}</td>

									<td>{{$row->size_name}}</td>



									<td>{{$row->inventory}}</td>

									<td>{{$row->used}}</td>
									<td>{{$row->remaining}}</td>
									<td>{{$row->min_order_qty}}</td>


								</tr>

								@endforeach

							</tbody>

						</form>

					</table>



				</div>



			</div>


		</form>

	</div>



</div>



</div>



@stop



@push('scripts')







<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>







<script>



	$(document).ready(function() {



		$('#datatable_rowsss').DataTable({
			stateSave: true,
			processing: true,
			// serverSide: true,

			scrollX: true,
			pageLength: 10000,
			lengthMenu: [10, 25, 50, 100, 500, 10000],
			dom: "Blfrtip",
			buttons : [{
				extend : 'pdf',
				text : 'Export to PDF',
				title : 'DRS',
				className: "btn btn-primary export",
				exportOptions : {
					columns: [0,1,2,3,4,5,6,7]
				}
			}],
		});


	});

</script>




@endpush