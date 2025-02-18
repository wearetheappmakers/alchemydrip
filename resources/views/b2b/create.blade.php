<div class="kt-portlet__body">
	<div class="row">
		<div class="form-group col-md-3">
			<label>Name</label>
			<input type="text" class="form-control" placeholder="Enter Name" name="name" required>
		</div>

		<div class="form-group col-md-3">
			<label>Number</label>
			<input type="tel" class="form-control" placeholder="Enter Number" name="number">
		</div>

    <div class="form-group col-md-3">
      <label>Email</label>
      <input type="email" class="form-control" placeholder="Enter Email" name="email" required>
    </div>

    <div class="form-group col-md-3">
      <label>Address-1</label>
      <input type="text" class="form-control" placeholder="Enter Address-1" name="address">
    </div>
  </div>
  <div class="row">
    <div class="form-group col-md-3">
      <label>Address-2</label>
      <input type="text" class="form-control" placeholder="Enter Address-2" name="address2">
    </div>
    <div class="form-group col-md-3">
      <label>Address-3</label>
      <input type="text" class="form-control" placeholder="Enter Address-3" name="address3">
    </div>
    <div class="form-group col-md-3">
      <label>GST-IN</label>
      <input type="text" class="form-control" placeholder="Enter Gst No" name="gstin">
    </div>
    <div class="form-group col-md-3">
      <label>PAN No</label>
      <input type="text" class="form-control" placeholder="Enter Pan No" name="pan_no">
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
            <th></th>
          </tr>
        </thead>
        <tbody>
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
        <td><h4>Total</h4></td>

      </tr>

    </tbody>
  </table>
</div>
</div>
<div class="row">
  <div class="col-lg-3">
    @include('layouts.status_checkbox',array('data' => ""))
  </div>

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


  setInterval(gettotal, 1000);
  function gettotal()
  {
  	var total_amount = 0;
    var totalqty = 0;

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
    $('#total_amount').val(total_amount);
    $('#total_qty').val(totalqty);


  }
</script>