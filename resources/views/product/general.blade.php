<form  method="POST" id="product-general-form" class="product-general-form">
    @csrf 
    <div class="row">
        <div class="form-group col-md-2">
            <label>School</label>
            <select name="school_id" class="form-control">
                <option>Select</option>
                @foreach($school as $sub)
                <option value="{{$sub->id}}" @if($sub->id == $data->school_id) selected @endif>{{$sub->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-2">
            <label>Product Name</label>
            <input type="text" class="form-control" placeholder="Enter Name" name="name" value="{{$data->name}}">
        </div>

        <div class="form-group col-md-2">
            <label>Product Code</label>
            <input type="text" class="form-control" placeholder="Enter code" name="code" value="{{$data->code}}">
        </div>
        <div class="form-group col-md-2">
            <label>GST %</label>
            <input type="number" class="form-control" placeholder="Enter GST %" name="gst" value="{{ $data->gst }}" required>
        </div>
    </div>
    <div class="row">
        <div class=" form-group col-lg-4">
            @include('layouts.status_checkbox',array('data' => $data->status))
        </div>

    </div>
    <div class="row">
        <div class="col-lg-12">
            <center>
                <button type="button" class="btn btn-brand" id="submit">Update</button>
                <a href="{{$index}}"><button type="button" class="btn btn-outline-secondary">Cancel</button></a>
            </center>
        </div>
    </div>
</form>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("#submit", "#product-general-form", function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('product.general_update') }}",
                data: new FormData($('.product-general-form')[0]),

                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status == 'success') {
                        toastr["success"](data.message, "Success");
                    } else if (data.status == 'error') {
                        toastr["error"]("Something went wrong", "Error");
                    }
                }
            });
            return false;
        });
    });

</script>
@endpush


