

<style>

    .quantity_input_style {

        width: 100% !important;

    }

</style>

<form method="post" id="product_inventory_form" class="product_inventory_form">

    @csrf

    <div class="row">

        <div class="col-lg-4">

            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">

                <input type="checkbox" id="onchangecheckbox"> Bulk Entry

                <span></span>

            </label>

        </div>
        <div class="col-lg-8 row new_class_checkbox" style="display: none;">
            <div class="col-lg-4">
                <label>Quantity</label>
                <input type="number" class="form-control new_quantity">
            </div>
            <div class="col-lg-4">
                <label>Min Qty</label>
                <input type="number" class="form-control new_quantity_min">
            </div>
            <div class="col-lg-4">
                <label>Max Qty</label>
                <input type="number" class="form-control new_quantity_max">
            </div>
        </div>

    </div><br>
    
    <div class="col-sm-12">

        <div class="row">

            <div class="clearfix"></div>

            <input type="hidden" name="product_id" value="{{ $data->id }}" class="product_id" />

                <?php foreach ($data->genders as $gender) : ?>

                    <div class="col-sm-4">

                        <table class="table table-striped- table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th>{{$gender->name}}</th>

                                    <th>Quantity</th>

                                    <th>Min Qty</th>

                                    <th>Max Qty</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($data->sizes as $size) : ?>

                                    <tr>

                                        <td>{{$size->name}}</td>

                                        <td>

                                            <input type="number" class="quantity_input_style checking_qty form-control"  name="quantity[{{$gender->id}}][{{$size->id}}]" @if(isset($selected_lot[$gender->id][$size->id])) value="{{$selected_lot[$gender->id][$size->id] }}"  @endif />

                                        </td>

                                        <td>

                                            <input type="number" class="quantity_input_style form-control checking_qty_min" name="min_order_qty[{{$gender->id}}][{{$size->id}}]"  @if(isset($selected_lot1[$gender->id][$size->id])) value="{{$selected_lot1[$gender->id][$size->id] }}"  @endif/>

                                        </td>

                                        <td>

                                            <input type="number" class="quantity_input_style form-control checking_qty_max" name="max_order_qty[{{$gender->id}}][{{$size->id}}]"  @if(isset($selected_lot2[$gender->id][$size->id])) value="{{$selected_lot2[$gender->id][$size->id] }}"  @endif/>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                
                            </tbody>

                        </table>

                    </div>

                <?php endforeach; ?>

            
               

            <div class="clearfix"></div>

        </div>

    </div>

    <input type="hidden" id="product_id" class="product_id" name="product_id" value="{{ $data->id }}" >

    <input type="hidden" id="is_saved" class="is_saved" name="is_saved" value="1" >

    <div class="form-group row">

        <div class="col-lg-4"></div>

        <div class="col-lg-8">

            <button type="button" class="btn btn-brand inventory_update" id="inventory_update">Update</button>

        </div>

    </div>

</form>

<script type="text/javascript">
 $(document).ready(function() {
     $('#onchangecheckbox').change(function() {
        if(this.checked) {

            $('.new_class_checkbox').css('display','flex');
        }else{
            $('.new_class_checkbox').css('display','none');

        }
        $('#textbox1').val(this.checked);        
    });
     $(document).on('change','.new_quantity',function(){
        $('.checking_qty').val($('.new_quantity').val());
    });
     $(document).on('change','.new_quantity_min',function(){
        $('.checking_qty_min').val($('.new_quantity_min').val());
    });
     $(document).on('change','.new_quantity_max',function(){
        $('.checking_qty_max').val($('.new_quantity_max').val());
    });
 });
</script>