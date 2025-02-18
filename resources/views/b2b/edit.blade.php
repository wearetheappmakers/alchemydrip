<div class="kt-portlet__body">
	<div class="row">
		<div class="form-group col-md-3">
			<label>Name</label>
			<input type="text" class="form-control" placeholder="Enter Name" name="name" value="{{$data->name}}" required>
		</div>

		<div class="form-group col-md-3">
			<label>Number</label>
			<input type="tel" class="form-control" placeholder="Enter Number" name="number" value="{{$data->number}}" required>
		</div>
		<div class="form-group col-md-3">
			<label>Email</label>
			<input type="text" class="form-control" placeholder="Enter Email" name="email" value="{{$data->email}}">
		</div>

		<div class="form-group col-md-3">
			<label>Address-1</label>
			<input type="text" class="form-control" placeholder="Enter Address-1" name="address" value="{{$data->address}}" required>
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-3">
			<label>Address-2</label>
			<input type="text" class="form-control" placeholder="Enter Address-2" name="address2" value="{{$data->address2}}">
		</div>
		<div class="form-group col-md-3">
			<label>Address-3</label>
			<input type="text" class="form-control" placeholder="Enter Address-3" name="address3" value="{{$data->address3}}">
		</div>
		<div class="form-group col-md-3">
			<label>GST-IN</label>
			<input type="text" class="form-control" placeholder="Enter Gst No" name="gstin" value="{{$data->gstin}}">
		</div>
		<div class="form-group col-md-3">
			<label>PAN No</label>
			<input type="text" class="form-control" placeholder="Enter Pan No" name="pan_no" value="{{$data->pan_no}}">
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<table cellspacing="0" border="0" class="table table-bordered">
				<thead>
					<tr>
						<th>Name</th>
						<th>Price</th>
						<th>Qty</th>
						<th>Amount</th>
						<th>GST %</th>
						<th>GST Amount</th>
						<th>Total</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@if($edit_b2b_clone)
					@foreach($edit_b2b_clone as $key => $value)
					<tr class="master_clone_b2b_details_edit" >
						<input type="hidden" name="b2b_child_id[]" value="{{ $value->id }}">
						<td>
							<input type="text" class="form-control edit_names"  name="edit_names[]" id="edit_names_{{$value->id}}" data-id="{{$value->id}}" value="{{$value->name}}" readonly>
						</td>
						<td>
							<input type="text" class="form-control edit_price" id="edit_price_{{$value->id}}" data-id="{{$value->id}}" name="edit_price[]" value="{{$value->price}}" >
						</td>
						<td>
							<input type="text" class="form-control edit_qty" id="edit_qty_{{$value->id}}" data-id="{{$value->id}}" name="edit_qty[]" value="{{$value->qty}}">
						</td>

						<td>
							<input type="text" class="form-control edit_amount" id="edit_amount_{{$value->id}}" data-id="{{$value->id}}" name="edit_amount[]" value="{{$value->amount}}" readonly>
						</td>
						<td>
							<input type="text" class="form-control edit_gst" id="edit_gst_{{$value->id}}" data-id="{{$value->id}}" name="edit_gst[]" value="{{$value->gst}}" >
						</td>
						<td>
							<input type="text" class="form-control edit_gst_amount" id="edit_gst_amount_{{$value->id}}" data-id="{{$value->id}}" name="edit_gst_amount[]" value="{{$value->gst_amount}}" readonly>
						</td>
						<td>
							<input type="text" class="form-control edit_total" id="edit_total_{{$value->id}}" data-id="{{$value->id}}" name="edit_total[]" value="{{$value->total}}" readonly>
						</td>
						<td>
							<div style="display: inline-flex !important;">
								<button type="button" id="edit_remove_b2b_details" data-value="{{ $value->id }}" class="btn btn-sm btn-icon btn-icon-md btn-clean edit_remove_b2b_details edit_remove_b2b_details"><i class="fa fa-minus" style="font-size:0.8rem !important"></i></button>
							</div>
						</td>


					</tr>
					@endforeach
					@endif
					<tr class="master_clone_b2b_details" data-id="0" -master="0">  
						<td>
							<input type="hidden" name="main_id[]" class="main_id">
							<input type="text" class="form-control names" name="names[]" id="names_0" data-id="0">
						</td>
						<td>
							<input type="text" class="form-control price"  name="price[]" id="price_0" data-id="0">
						</td>
						<td>
							<input type="number"  class="form-control qty"  name="qty[]" id="qty_0" data-id="0" >
						</td>
						<td>
							<input type="text"  class="form-control amount"  name="amount[]" id="amount_0" data-id="0" readonly>
						</td>
						<td>
							<input type="text"  class="form-control gst"  name="gst[]" id="gst_0" data-id="0">
						</td>
						<td>
							<input type="text"  class="form-control gst_amount"  name="gst_amount[]" id="gst_amount_0" data-id="0" readonly>
						</td>
						<td>
							<input type="text"  class="form-control total"  name="total[]" id="total_0" data-id="0" readonly>
						</td>
						<td>
							<div style="display: inline-flex !important;">
								<button type="button" id="add_b2b_details" class="btn btn-sm btn-clean btn-icon btn-icon-md add_b2b_details"><i class="fa fa-plus" style="font-size:0.8rem !important"></i></button>
								&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
								<button type="button" id="remove_b2b_details" data-id='0' class="btn btn-sm btn-icon btn-icon-md btn-clean remove_b2b_details remove_b2b_details" ><i class="fa fa-minus" style="font-size:0.8rem !important"></i></button>
							</div>
						</td>
					</div>

				</tr>
				<tr>

					<td></td>
					<td></td>
					<td><input type="number" class="form-control"  name="total_qty" id="total_qty" readonly></td>
					<td><input type="number" class="form-control"  name="total_amount" id="total_amount" readonly></td>
					<td></td>
					<td></td>
					<td><input type="number" name="total" class="form-control" id="total" readonly></td>
					<td><h4>Total</h4></td>

				</tr>
			</tbody>
		</table>
	</div>
</div>

<div class=" form-group col-lg-4">
	@include('layouts.status_checkbox',array('data' => $data->status))
</div>
</div>
<script>
	$(document).ready(function() {

		var count = 0;
		$(document).on('click','.add_b2b_details',function() {
			count++;
			var $row = $(this).closest('.master_clone_b2b_details');

			var $clone = $row.clone();

			$clone.find('.names').val('').attr('data-id',count).attr('id','names_'+count);
			$clone.find('.price').val('').attr('data-id',count).attr('id','price_'+count);
			$clone.find('.qty').val('').attr('data-id',count).attr('id','qty_'+count);
			$clone.find('.amount').val('').attr('data-id',count).attr('id','amount_'+count);
			$clone.find('.total_qty').val('').attr('data-id',count).attr('id','total_qty_'+count);
			$clone.find('.total_amount').val('').attr('data-id',count).attr('id','total_amount_'+count);



			$row.after($clone);
		});

		$(document).on('click','.remove_b2b_details',function(){

			var num_of_master_other_payment = $('.master_clone_b2b_details').length;
			if (num_of_master_other_payment != 1) {
				var obj = $(this).closest('.master_clone_b2b_details');
				obj.remove();
			}
		});
	});

	$(document).on('click','.edit_remove_b2b_details',function(e){
				// $(".edit_remove_container_details").on("click", function(e)
				// 		{
					var id = $(this).data('value');
					var url = "{{ route('b2b.delete',":rowid") }}";
					url = url.replace(':rowid',id);
					var num_of_master_clone_div_payment = $('.master_clone_b2b_details_edit').length;
					if (num_of_master_clone_div_payment) {

						e.preventDefault();



						$.ajax({

							type: "GET",
							url: url,

							processData: false,
							contentType: false,

							success: function(data) {

								if (data.status === 'success') {

									console.log('done');
									location.reload();

								} else if (data.status === 'error') {
									location.reload();
								}

							}

						});
					}



						// });
					});



	setInterval(gettotal, 1000);
	function gettotal()
	{
		var gst = 0;
		var edit_gst = 0;
		var totals = 0;
		var edit_totals = 0;
		var total_amount = 0;
		var totalqty = 0;
		var edit_totalqty = 0;
		var edit_total_amount =0;

		$('.qty').each(function(){
			var id = $(this).data('id');
			var qty = $(this).val();
			var price = $('#price_'+id).val();

			if(qty == '' || qty == ''){qty = 0;}
			if(price == 0 || price == '') {price = 0;}
			var amount = qty*price;
			totalqty = totalqty + parseInt(qty);
			$('#amount_'+id).val(amount);
		});

		$('.amount').each(function(i,val){
			var val = $(val).val();
			if(val == ''){val = 0;}
			total_amount = total_amount + parseInt(val);

		});
		$('.total').each(function(i,val){
			var val = $(val).val();
			if(val == ''){val = 0;}
			
			totals = totals + parseInt(val);

		});
		$('.gst').each(function(i,val){
			var id = $(this).data('id');
			var val = $(val).val();
			if(val == ''){val = 0;}
			var amount = $('#amount_'+id).val();
			if(amount == ''){amount = 0;}
			gst = (parseInt(val) * parseInt(amount))/100 ;
			$('#gst_amount_'+id).val(gst);
			tt = parseInt(gst) + parseInt(amount);
			$('#total_'+id).val(tt);
		});

		$('.edit_qty').each(function(){
			var edit_id = $(this).data('id');
			var edit_qty = $(this).val();
			var edit_price = $('#edit_price_'+edit_id).val();

			if(edit_qty == '' || edit_qty == ''){edit_qty = 0;}
			if(edit_price == 0 || edit_price == '') {edit_price = 0;}
			var edit_amount = edit_qty*edit_price;
			edit_totalqty = edit_totalqty + parseInt(edit_qty);
			$('#edit_amount_'+edit_id).val(edit_amount);
		});
		$('.edit_amount').each(function(i,val){
			var val = $(val).val();
			if(val == ''){val = 0;}
			edit_total_amount = edit_total_amount + parseInt(val);

		});
		$('.edit_gst').each(function(i,val){
			var id = $(this).data('id');
			var val = $(val).val();
			if(val == ''){val = 0;}
			var amount = $('#edit_amount_'+id).val();
			if(amount == ''){amount = 0;}
			gst = (parseInt(val) * parseInt(amount))/100 ;
			$('#edit_gst_amount_'+id).val(gst);

			tt = parseInt(gst) + parseInt(amount);
			$('#edit_total_'+id).val(tt);
		});
		$('.edit_total').each(function(i,val){
			var val = $(val).val();
			if(val == ''){val = 0;}
			edit_totals = edit_totals + parseInt(val);

		});

		$('#total_amount').val(total_amount + edit_total_amount);
		$('#total_qty').val(totalqty + edit_totalqty);
		$('#total').val(totals + edit_totals);

	}




</script>