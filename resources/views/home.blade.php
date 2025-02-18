@extends('main')

@section('content')
<style type="text/css">
	.kt-widget24{
		background-color: #FFFFFF;
		box-shadow:0 0 5px 0 rgba(0, 0, 0, 0.1) ;
		-webkit-box-shadow:0 0 5px 0 rgba(0, 0, 0, 0.1) ;
		position: relative;
		overflow: hidden;
	}
	.kt-widget24 .kt-widget24__icon {
		font-size: 120px;
		position: absolute;
		line-height: 120px;
		right: 0;
		color: rgba(0, 0, 0, 0.05);
		bottom: 0;
		margin-bottom: -24px;
		z-index: 0;
	}
	.kt-widget24 .kt-widget24__details {
		position: relative;
		z-index: 1; 
	}
	body {
		background: #F3F4F6;
	}
	.kt-widget24 .kt-widget24__details .kt-widget24__info .kt-widget24__title {
		color: #000000 !important;
		margin: 0;
		font-size: 16px;
	}
	.kt-widget24 .kt-widget24__details .kt-widget24__stats {
		padding-left:0;
	}
	.kt-widget24 .kt-widget24__details .view-more a {
		background-color: #000000;
		color: #ffffff;
		height: 30px;
		width: 30px;
		border-radius: 30px;
		text-align: center;
		display: flex;
		align-items: center;
		justify-content: center;
	}	
	.kt-widget24.yello-box {
		background-color:#FFF4DE;
		border: 1px solid #FFA800;
	}
	.kt-widget24.yello-box .kt-widget24__details .view-more a {
		background-color: #FFA800;
	}
	.kt-widget24.yello-box .kt-widget24__details .kt-widget24__stats {
		color:#FFA800;
	}
	.kt-widget24.lightblue-box {
		background-color:#E1F0FF;
		border: 1px solid #3699FF;
	} 
	.kt-widget24.lightblue-box .kt-widget24__details .kt-widget24__stats {
		color:#3699FF;
	}
	.kt-widget24.lightblue-box .kt-widget24__details .view-more a {
		background-color: #3699FF;
	}
	.kt-widget24.red-box {
		background-color:#FFE2E5;
		border: 1px solid #F64E60;
	}
	.kt-widget24.red-box .kt-widget24__details .kt-widget24__stats {
		color:#F64E60;
	}
	.kt-widget24.red-box .kt-widget24__details .view-more a {
		background-color: #F64E60;
	}
	.kt-widget24.lightgreen-box {
		background-color:#c9f7f5;
		border: 1px solid #1BC5BD;
	}
	.kt-widget24.lightgreen-box .kt-widget24__details .kt-widget24__stats {
		color:#1BC5BD;
	}
	.kt-widget24.lightgreen-box .kt-widget24__details .view-more a {
		background-color: #1BC5BD;
	}
	.kt-widget24.purple-box {
		background-color:#EEE5FF;
		border: 1px solid #8950FC;
	}
	.kt-widget24.purple-box .kt-widget24__details .kt-widget24__stats {
		color:#8950FC;
	}
	.kt-widget24.purple-box .kt-widget24__details .view-more a {
		background-color: #8950FC;
	}
	.kt-widget24.dark-box {
		background-color:#EBEDF3;
		border: 1px solid #181C32;
	}
	.kt-widget24.dark-box .kt-widget24__details .kt-widget24__stats {
		color:#181C32;
	}
	.kt-widget24.dark-box .kt-widget24__details .view-more a {
		background-color: #181C32;
	}
	.kt-widget24.pink-box {
		background-color: #CFF5CF;
		border: 1px solid #4B7502;
	}
	.kt-widget24.pink-box .kt-widget24__details .kt-widget24__stats {
		color:#4B7502;
	}
	.kt-widget24.pink-box .kt-widget24__details .view-more a {
		background-color: #4B7502;
	}
	.kt-widget24.gray-box {
		background-color:#d4edda;
		border: 1px solid #155724 ;
	}
	.kt-widget24.gray-box .kt-widget24__details .kt-widget24__stats {
		color:#155724;
	}
	.kt-widget24.gray-box .kt-widget24__details .view-more a {
		background-color: #155724;
	}
	.kt-portlet .kt-portlet__head .kt-portlet__head-label .kt-portlet__head-title {
		color: #000000;
		font-size: 16px;
	}
	.kt-portlet .kt-portlet__head .kt-portlet__head-label .kt-portlet__head-title i{
		color: #BC3043; 
		font-size: 20px;
		margin-right: 5px;
	}
	.table th, .table td {
		font-size: 14px;
		padding: 15px;
		font-weight: 400;
	}

	.wavestyle{
		padding: 5px !important;
	}

</style>

<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<br>
	<!-- <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
		<h3><b>General</b></h3>
		<div class="row">
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $todays_income }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Today's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $week_income }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Week's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $month_income }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Month's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $year_income }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Year's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>

			
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $all_income }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Total Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $todays_orders }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Today's Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $week_orders }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Week's Order</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">
							
							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $month_orders }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Month's Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $year_orders }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Year's Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $all_orders }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Total Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>

		
		@foreach($shop as $key => $row)
		<h3><b>{{$row['name']}}</b></h3>
		<div class="row">
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $row['todays_income'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Today's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $row['week_income'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Week's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $row['month_income'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Month's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $row['year_income'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Year's Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										&#8377; {{ $row['all_income'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Total Income</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $row['todays_orders'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Today's Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $row['week_orders'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Week's Order</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">
							
							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $row['month_orders'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Month's Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $row['year_orders'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Year's Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
			<div class="col-lg-2">
				<a href="#" class="kt-portlet kt-iconbox" style="background: #E0E0E0;">
					<div class="kt-portlet__body wavestyle">
						<div class="kt-iconbox__body">

							<div class="kt-iconbox__desc">
								<h3 class="kt-iconbox__title">
									<span class="kt-widget24__stats">
										{{ $row['all_orders'] }}
									</span> 
								</h3>
								<div class="kt-iconbox__content">
									<b>Total Orders</b>
								</div>
							</div>
						</div>
					</div>
				</a>
			</div>
		</div>

		
		@endforeach
	</div> -->

</div>

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

			ajax: "{{ route('so.index') }}",

			columns: [
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
			{
				orderable: false,

				searchable: false,

				data: 'status_full_name',

			},
			]
		});

	});


</script>


<script>

	$(document).ready(function() {

		$('#datatable_rowss').DataTable({

			processing: true,
			serverSide: true,
			searchable: true,
			scrollX: true,
			stateSave: true,
			columnDefs: [{
				orderable: false,
				targets: -1,
			}],

			ajax: "{{ route('b2b.index') }}",

			columns: [
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
				data: "address"
			},
			{
				orderable: false,

				searchable: false,

				data: 'status_full_name',

			},
			]
		});

	});

</script>

@endsection
