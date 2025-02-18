<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="kt-portlet__body">
  <div class="row">
<!--     <div class="form-group col-md-4">
      <label>Name</label>
      <input type="text" class="form-control" placeholder="Enter Name" name="name" required>
    </div> -->
    <div class="form-group col-md-4">
      <label>Number</label>
      <input type="tel" class="form-control" placeholder="Enter Number" name="number">
    </div>
<!--     <div class="form-group col-md-4">
      <label>Address</label>
      <input type="text" class="form-control" placeholder="Enter address" name="address">
    </div> -->
  </div><br>
  <div class="row">
    <div class="col-sm-12">
      <table cellspacing="0" border="0" class="table table-bordered">
        <thead>
          <tr>
            <th>School</th>
            <th>Product</th>
            <th>Size,Gender</th>
            <th>Price</th>
            <th>Wholesale Price</th>
            <th>Qty</th>
            <th>Amount</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr class="master_clone_div_container_details" data-id="0" -master="0">  
            <td>
              <input type="hidden" name="main_id[]" class="main_id">
              <select class="form-control school_id" id="school_id_0_0" onchange="getproduct(this)" data-id="0" data-master="0" name="school_id[]" >
                <option value="">Select</option>
                @foreach($school as $sub)
                <option value="{{$sub->id}}">{{$sub->name}}</option>
                @endforeach
              </select>
            </td>

            <td>
              <input type="hidden" name="main_id[]" class="main_id">
              <select class="form-control product_id" id="product_id_0" data-id="0" onchange="getsize(this)" data-master="0" name="product_id[]" >
                <option value="">Select</option>
              </select>
            </td>
            <td>
              <input type="hidden" name="main_id[]" class="main_id">
              <select class="form-control size_id" id="size_id_0" data-id="0" data-master="0" onchange="getprice(this)" name="size_id[]" >
                <option value="">Select</option>

              </select>
            </td>
            <td>
              <input type="text" class="form-control price"  name="price[]" id="price_0" data-id="0" readonly>
            </td>
            <td>
              <input type="text" class="form-control w_price"  name="w_price[]" id="w_price_0" data-id="0" readonly>
            </td>
            <td>
              <input type="number"  class="form-control qty"  name="qty[]" id="qty_0" data-id="0" >
            </td>
            <td>
              <input type="text"  class="form-control amount"  name="amount[]" id="amount_0" data-id="0" readonly>
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
            <td></td>
            <td></td>
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
          <tr class="master_clone_other_details" data-id="0" -master="0"> 
           <td>
              <input type="hidden" name="main_id[]" class="main_id">
              <select class="form-control other_name" id="other_name_0" data-id="0" data-master="0" onchange="getotherprice(this)" name="other_name[]" >
                <option value="">Select</option>
                @foreach($name as $nam)
                <option value="{{$nam->id}}">{{$nam->name}}</option>
                @endforeach
              </select>
            </td>
 
            <td>
              <input type="text" class="form-control other_price"  name="other_price[]" id="other_price_0" data-id="0" readonly>
            </td>
            <td>
              <input type="number"  class="form-control other_qty"  name="other_qty[]" id="other-qty_0" data-id="0" >
            </td>
            <td>
              <input type="text"  class="form-control other_amount"  name="other_amount[]" id="other_amount_0" data-id="0" readonly>
            </td>
            <td>
              <div style="display: inline-flex !important;">
                <button type="button" id="add_other_details" class="btn btn-sm btn-clean btn-icon btn-icon-md add_other_details"><i class="fa fa-plus" style="font-size:0.8rem !important"></i></button>
                &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
                <button type="button" id="remove_other_details" data-id='0' class="btn btn-sm btn-icon btn-icon-md btn-clean remove_other_details remove_other_details" ><i class="fa fa-minus" style="font-size:0.8rem !important"></i></button>
              </div>
            </td>

          </tr>
          <tr>

            <td></td>
            <td></td>
            <td><input type="number" class="form-control"  name="other_total_qty" id="other_total_qty" readonly></td>
            <td><input type="number" class="form-control"  name="other_total_amount" id="other_total_amount" readonly></td>
            <td><h4>Total</h4></td>

          </tr>

        </tbody>
      </table>
    </div>
  </div>


  <div class="row">
    <div class="form-group col-lg-3">
      @include('layouts.status_checkbox',array('data' => ""))
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

      $clone.find('.school_id').val('').attr('data-id',count).attr('id','school_id_'+count);
      $clone.find('.product_id').val('').attr('data-id',count).attr('id','product_id_'+count);
      $clone.find('.size_id').val('').attr('data-id',count).attr('id','size_id_'+count);
      $clone.find('.price').val('').attr('data-id',count).attr('id','price_'+count);
      $clone.find('.w_price').val('').attr('data-id',count).attr('id','w_price_'+count);
      $clone.find('.qty').val('').attr('data-id',count).attr('id','qty_'+count);
      $clone.find('.total_qty').val('').attr('data-id',count).attr('id','total_qty_'+count);
      $clone.find('.amount').val('').attr('data-id',count).attr('id','amount_'+count);
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
  });

  function getproduct($this){

    var school_id = $($this).val();
    var id = $($this).data('id');
    $.ajax({
      type:"POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url:"{{ route('get.product') }}",
      data: {
        'school_id': school_id
      },
      success: function(data){
        $('#product_id_'+id).html(data);
      }
    });

  };



  function getsize($this){

    var product_id = $($this).val();
    var id = $($this).data('id');
    $.ajax({
      type:"POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url:"{{ route('get.size') }}",
      data: {
        'product_id': product_id
      },
      success: function(data){
        console.log(data);
        $('#size_id_'+id).html(data);
      }
    });

  };
  function getprice($this){

    var product_id = $($this).val();
    var id = $($this).data('id');
    $.ajax({
      type:"POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url:"{{ route('get.price') }}",
      data: {
        'product_id': product_id,
      },
      dataType:'json',
      success: function(data){
        console.log(data.data);
        $('#price_'+id).val(data.data.price);
        $('#w_price_'+id).val(data.data.wholesale_price);
      }
    });

  };

  function getotherprice($this){

    var other_name = $($this).val();
    var id = $($this).data('id');
    $.ajax({
      type:"POST",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url:"{{ route('get.other.price') }}",
      data: {
        'other_name': other_name,
      },
      dataType:'json',
      success: function(data){
        console.log(data);
        $('#other_price_'+id).val(data.data);
        
      }
    });

  };

  setInterval(gettotal, 1000);
  

  function gettotal()
  {
    var total_amount = 0;
    var totalqty = 0;

    $('.qty').each(function(){
      var id = $(this).data('id');
      var qty = $(this).val();
      var w_price = $('#w_price_'+id).val();

      if(qty == '' || qty == ''){qty = 0;}
      if(w_price == 0 || w_price == '') {w_price = 0;}
      var amount = qty*w_price;
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





    var other_total_amount = 0;
    var other_totalqty = 0;

    $('.other_qty').each(function(){
      var id = $(this).data('id');
      var other_qty = $(this).val();
      var other_price = $('#other_price_'+id).val();

      if(other_qty == '' || other_qty == ''){other_qty = 0;}
      if(other_price == 0 || other_price == '') {other_price = 0;}
      var other_amount = other_qty*other_price;
      other_totalqty = other_totalqty + parseInt(other_qty);
      $('#other_amount_'+id).val(other_amount);
    });

    $('.other_amount').each(function(i,val){
      var val = $(val).val();
      if(val == ''){val = 0;}
      other_total_amount = other_total_amount + parseInt(val);

    });
    $('#other_total_amount').val(other_total_amount);
    $('#other_total_qty').val(other_totalqty);


  }


  $(document).ready(function() {

    var count = 0;
    $(document).on('click','.add_other_details',function() {
      count++;
      var $row = $(this).closest('.master_clone_other_details');

      var $clone = $row.clone();

      $clone.find('.other_name').val('').attr('data-id',count).attr('id','other_name_'+count);
      $clone.find('.other_price').val('').attr('data-id',count).attr('id','other_price_'+count);
      $clone.find('.other_qty').val('').attr('data-id',count).attr('id','other_qty_'+count);
      $clone.find('.other_amount').val('').attr('data-id',count).attr('id','other_amount_'+count);
      $clone.find('.other_total_qty').val('').attr('data-id',count).attr('id','other_total_qty_'+count);
      $clone.find('.other_total_amount').val('').attr('data-id',count).attr('id','other_total_amount_'+count);



      $row.after($clone);
    });

    $(document).on('click','.remove_other_details',function(){

      var num_of_master_other_payment = $('.master_clone_other_details').length;
      if (num_of_master_other_payment != 1) {
        var obj = $(this).closest('.master_clone_other_details');
        obj.remove();
      }
    });
  });

</script>