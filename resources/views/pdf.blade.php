<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		body{
			/*font-size: 10px !important;*/
		}
		td,th{
			border-collapse: collapse;
			border: 1px solid #737373 !important;
			padding: 2px !important; 
		}
		.no-break {
			page-break-before: always !important;
		}
		ol li{
			text-align: justify;
			font-size:70% !important;
		}


	</style>
	<title>DRS</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
	@php $setting = App\Setting::where('id',1)->first() @endphp
	<div class="row">
		<div class="col-sm-6">
			<table style="float:left;width:40%">
				<tr align="right">
					<p style="text-align: left; font-size: xx-large;color: #7545BC;">INVOICE</p>
				</tr>
				<tr>
					<td style="text-align:left;border-collapse:collapse !important;border: none !important;">
						Invoice No # &nbsp&nbsp <b>DRS - {{$datas->id}}</b><br>
						Invoice Date &nbsp&nbsp <b>March 02, 2022</b>
					</td>
				</tr>
			</table>
		</div><br>
		<table style="border-collapse:collapse !important;border: none !important;float:right;position: absolute; top: 0; right: 10; width: 100%;">
			<tr style="border-collapse:collapse !important;border: none !important;">
				<td  style="border-collapse:collapse !important;border: none !important;text-align: center;">
					<img src="{{ asset('/setting/'.$setting->logo) }}" width="100" height="140" style="float:right;">
				</td>
			</tr>
		</table>
	</div><br>
	{!! $data !!}
</body>
</html>