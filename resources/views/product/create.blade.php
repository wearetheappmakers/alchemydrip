<div class="kt-portlet__body">
<form  method="POST" id="product-general-form" class="product-general-form">
    @csrf 
    <div class="row">
        <div class="form-group col-md-2">
            <label>School</label>
            <select name="school_id" class="form-control">
                <option>Select</option>
                @foreach($school as $sub)
                <option value="{{$sub->id}}">{{$sub->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-2">
            <label>Product Name</label>
            <input type="text" class="form-control" placeholder="Enter Name" name="name" required>
        </div>

        <div class="form-group col-md-2">
            <label>Product Code</label>
            <input type="text" class="form-control" placeholder="Enter code" name="code" required>
        </div>
        <div class="form-group col-md-2">
            <label>GST %</label>
            <input type="number" class="form-control" placeholder="Enter GST %" name="gst" required>
        </div>
    </div>
    <div class="row">
        <div class=" form-group col-lg-4">
            @include('layouts.status_checkbox',array('data' => ""))
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <center>
                <button type="button" class="btn btn-primary" id="submit">Submit</button>
                <a href="{{$index}}"><button type="button" class="btn btn-outline-secondary">Cancel</button></a>
            </center>
        </div>
    </div>
</form>
</div>
    


