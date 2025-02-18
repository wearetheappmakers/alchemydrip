

<div class="kt-portlet__body">
	<div class="row">
		<div class="form-group col-md-4">
			<label>Supplier Name</label>
			<input type="text" class="form-control" placeholder="Enter Name" name="supplier_name" value="{{$data->supplier_name}}" required>
		</div>
		<div class="form-group col-md-4">
			<label>Supplier number</label>
			<input type="tel" class="form-control" placeholder="Enter Number" name="supplier_number" value="{{$data->supplier_number}}" required>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<table cellspacing="0" border="0" class="table table-bordered">
					<thead>
						<tr>
							<th>Product</th>
							<th>Qty</th>
							<th>Price</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						@if($edit_details_clone)
						@foreach($edit_details_clone as $key => $value)
						<tr class="master_clone_div_container_details_edit" >

							<td>

								<input type="hidden" name="po_child_id[]" value="{{$value->id}}">
								<select class="form-control edit_product_id" id="edit_product_id_{{$value->id}}" data-id="{{$value->id}}"  name="edit_product_id[]">
									<option value="">Select</option>
									@foreach($product as $ec)

									<option value="{{$ec->id}}" @if($value->product_id == $ec->id) selected @endif>{{$ec->name}}</option>
									@endforeach
								</select>

							</td>

							<td>
								<input type="text" class="form-control edit_qty" id="edit_qty_{{$value->id}}" data-id="{{$value->id}}" name="edit_qty[]" value="{{$value->qty}}">
							</td>

							<td>
								<input type="text" class="form-control edit_price" id="edit_price_{{$value->id}}" data-id="{{$value->id}}" name="edit_price[]" value="{{$value->price}}" >
							</td>
							<td>
								<div style="display: inline-flex !important;">
									<button type="button" id="edit_remove_container_details" data-value="{{ $value->id }}" class="btn btn-sm btn-icon btn-icon-md btn-clean edit_remove_container_details edit_remove_container_details"><i class="fa fa-minus" style="font-size:0.8rem !important"></i></button>
								</div>
							</td>


						</tr>
						@endforeach
						@endif
						<tr class="master_clone_div_container_details" data-id="0" data-master="0">  
							<td>
								<input type="hidden" name="main_id[]" class="main_id">
								<select class="form-control product_id" id="product_id_0_0" data-id="0" data-master="0" name="product_id[]" >
									<option value="">--Select Product--</option>
									@foreach($product as $pro)
									<option value="{{$pro->id}}">{{$pro->name}}</option>

									@endforeach
								</select>
							</td>
							<td>
								<input type="number"  class="form-control qty"  name="qty[]" id="qty_0" data-id="0">
							</td>
							<td>
								<input type="number" class="form-control price" name="price[]" id="price_0" data-id="0">
							</td>
							<td>
								<div style="display: inline-flex !important;">
									<button type="button" id="add_more_container_details" class="btn btn-sm btn-clean btn-icon btn-icon-md add_more_container_details"><i class="fa fa-plus" style="font-size:0.8rem !important"></i></button>
									&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
									<button type="button" id="remove_container_details" data-id='0' class="btn btn-sm btn-icon btn-icon-md btn-clean remove_container_details remove_container_details" ><i class="fa fa-minus" style="font-size:0.8rem !important"></i></button>
								</div>
							</td>

						</tr>
						
						<tr>

							<td></td>
							<td><input type="number" class="form-control total_qty"  name="total_qty" id="total_qty"
								readonly></td>
								<td><input type="number" class="form-control total_amount"  name="total_amount" id="total_amount" readonly></td>
								<td><h4>Total</h4></td>

							</tr>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<script type="text/javascript">

			$(document).ready(function() {

				var count = 0;
				$(document).on('click','.add_more_container_details',function() {
					count++;
					var $row = $(this).closest('.master_clone_div_container_details');

					var $clone = $row.clone();

					$clone.find('.product_id').val('');
					$clone.find('.qty').val('').attr('data-id',count).attr('id','qty_'+count);
					$clone.find('.total_qty').val('').attr('data-id',count).attr('id','total_qty_'+count);
					$clone.find('.price').val('').attr('data-id',count).attr('id','price_'+count);
					$clone.find('.total_amount').val('').attr('data-id',count).attr('id','total_amount_'+count);



					$row.after($clone);
				});

				$(document).on('click','.remove_container_details',function(){

					var num_of_master_clone_div_payment = $('.master_clone_div_container_details').length;
					if (num_of_master_clone_div_payment != 1) {
						var obj = $(this).closest('.master_clone_div_container_details');
						obj.remove();
					}
				});

				$(document).on('click','.edit_remove_container_details',function(){

					var num_of_master_clone_div_payment = $('.master_clone_div_container_details_edit').length;
					if (num_of_master_clone_div_payment != 1) {


						var obj = $(this).closest('.master_clone_div_container_details_edit');
						obj.remove();
					}
				});
			});


				
			$(document).on('click','.edit_remove_container_details',function(e){
				// $(".edit_remove_container_details").on("click", function(e)
				// 		{
					var id = $(this).data('value');

					var url = "{{ route('po.delete_clone',":rowid") }}";
					url = url.replace(':rowid',id);
					var num_of_master_clone_div_payment = $('.master_clone_div_container_details_edit').length;
					if (num_of_master_clone_div_payment != 1) {

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

				var total_qty = 0;
				var qty = 0;
				var total_amount = 0;
				var price = 0;

				$('.qty').each(function(){
					var val = $(this).val();
					if (val == '') { val = 0; }

					total_qty += parseInt(val);

				});
				var edit_total_qty = 0;
				$('.edit_qty').each(function(){
					var edit_val = $(this).val();
					if (edit_val == '') { edit_val = 0; }

					edit_total_qty += parseInt(edit_val);
				});
				$('#total_qty').val(parseInt(total_qty) + parseInt(edit_total_qty));



				$('.price').each(function(){
					var val = $(this).val();
					if (val == '') { val = 0; }

					total_amount += parseInt(val);

				});
				var edit_total_amount = 0;
				$('.edit_price').each(function(){
					var edit_val = $(this).val();
					if (edit_val == '') { edit_val = 0; }

					edit_total_amount += parseInt(edit_val);

				});
				$('#total_amount').val(parseInt(total_amount) + parseInt(edit_total_amount));


			}


		</script>