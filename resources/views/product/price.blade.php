

<style>

    .quantity_input_style {

        width:50%

    }

    </style>

<form method="post" id="product_price_form" class="product_price_form">

    @csrf

    <div class="row">

        <div class="col-lg-4">

            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">

                <input type="checkbox" id="onchangecheckbox_price"> Bulk Entry

                <span></span>

            </label>

        </div>
        <div class="col-lg-8 row new_class_checkbox_price" style="display: none;">
            <div class="col-lg-4">
                <label>MRP</label>
                <input type="number" class="form-control new_price">
            </div>
            <div class="col-lg-4">
                <label>Selling Price</label>
                <input type="number" class="form-control new_wholesale_price">
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

                                    <th>MRP</th>

                                    <!-- <th>Selling Qunatity</th> -->

                                    <th>Selling Price</th>

                                </tr>

                            </thead>

                            <tbody>
                                <?php foreach ($data->sizes as $size) : ?>

                                    <tr>

                                        <td>{{$size->name}}</td>

                                        <td><input type="text" class="quantity_input_style form-control checking_price" name="price[{{$gender->id}}][{{$size->id}}]" @if(isset($selected_lot[$gender->id][$size->id])) value="{{$selected_lot[$gender->id][$size->id] }}"  @endif /></td>


                                        <td><input type="text" class="quantity_input_style form-control checking_wholesale_price" name="wholesale_price[{{$gender->id}}][{{$size->id}}]" value="{{ isset($selected_lot_whole_sale[$gender->id][$size->id]) ? $selected_lot_whole_sale[$gender->id][$size->id] : '' }}"/></td>

                                        

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

        <button type="button" class="btn btn-brand price_update" id="price_update" >Update</button>

    </div>

</div>

</form>

<script type="text/javascript">
 $(document).ready(function() {
     $('#onchangecheckbox_price').change(function() {
        if(this.checked) {

            $('.new_class_checkbox_price').css('display','flex');
        }else{
            $('.new_class_checkbox_price').css('display','none');

        }
        $('#textbox1').val(this.checked);        
    });
     $(document).on('change','.new_price',function(){
        $('.checking_price').val($('.new_price').val());
    });
     $(document).on('change','.new_wholesale_price',function(){
        $('.checking_wholesale_price').val($('.new_wholesale_price').val());
    });
 });
</script>