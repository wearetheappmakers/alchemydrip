@php
$selected_size = $data->sizes->toArray();
$selected_size = array_column($selected_size, 'id');

@endphp
<form method="post" id="product-size-form" class="product-size-form">
    @csrf
    <input type="hidden" value="{{$data->id}}" name="product_id">
    <div class="form-group row">
        @if(!empty($siz))
        @foreach($sizes as $size)
        <div class="col-lg-12">
            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                <input type="checkbox" class="" name="product[size_id][]" value="{{ $size->id }}" @if(in_array($size->id, $selected_size)) checked="checked" @endif>
                <b>{{ $size->name }}</b>
                <span></span>
            </label>
        </div>

        @endforeach
        @else
        <a class="btn btn-brand" href="{{ route('size.index') }}">Add Size</a>
        @endif
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-8">
                <button type="button" class="btn btn-brand" id="sizebtn">Update</button>
            </div>
        </div>
    </div>
</form>

        

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#sizebtn", function(e) {

            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('product.size_update') }}",
                data: new FormData($('.product-size-form')[0]),
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.status == 'success') {
                        location.reload();
                        toastr["success"](data.message, "Success");
                    } else if (data.status == 'error') {
                        location.reload();
                        toastr["error"]("Something went wrong", "Error");
                    }
                }
            });
            return false;
        });
    });

</script>
@endpush