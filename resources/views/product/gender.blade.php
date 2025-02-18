@php
$selected_gender = $data->genders->toArray();
$selected_gender = array_column($selected_gender, 'id');
// dd($data->genders);

@endphp
<form method="post" id="product-gender-form" class="product-gender-form">
    @csrf
    <input type="hidden" value="{{$data->id}}" name="product_id">
    <div class="form-group row">

        @if(!empty($gen))
        @foreach($genders as $gender)
        <div class="col-lg-12">
            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                <input type="checkbox" class="" name="product[gender_id][]" value="{{ $gender->id }}" @if(in_array($gender->id, $selected_gender)) checked="checked" @endif>
                <b>{{ $gender->name }}</b>
                <span></span>
            </label>
        </div>
        @endforeach
        @else
        
        <a class="btn btn-brand" href="{{ route('gender.index') }}">Add gender</a><br>
        
        @endif
        <div class="row">
            <div class="col-lg-4"></div>
            <div class="col-lg-8">
                <button type="button" class="btn btn-brand gender_update" id="gender_update">Update</button>
            </div>
        </div>
    </div>
</form>
       
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#gender_update", function(e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "{{ route('product.gender_update') }}",
                data: new FormData($('.product-gender-form')[0]),

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