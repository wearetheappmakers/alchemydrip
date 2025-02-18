<?php
$edit = route($route.'.edit',$id);
?>

<a style="background: white;" href="{{ $edit }}" title="Edit details" class="btn btn-sm btn-clean btn-icon btn-icon-md">
	<i style="color: green;" class="la la-edit"></i>
</a>
&nbsp
<button style="background: white;" title="Delete" data-id="{{$id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md delete-record">
    <i style="color: red;" class="la la-trash">
    </i>
</button>
&nbsp

@if($route === 'b2b')
@php $pdf = route('b2b.invoice','$id');
if($route === 'b2b')
{
    $name = App\B2b::where('id',$id)->value('id');    
}
@endphp 
<button style="background: white;" title="PDF" onclick="getpdf('{{$id}}','{{$name}}')" id="pdf_{{$id}}" class="btn btn-sm btn-clean btn-icon btn-icon-md pdf">
    <i style="color: black;" class="fas fa-print"></i>
</button>
@endif


<script type="text/javascript">

    @if($route === 'b2b')
    function getpdf(id,name,invoiceno)
    {
        var parent = $('#pdf_'+id);           

        parent.attr('disabled',true);

        parent.html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            type: "POST",
            url: "{{ $pdf }}",
            data: {
                '_token': $('input[name="_token"]').val(),
                'id': id
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(blob) {
                parent.attr('disabled',false);

                parent.html('<i style="color: black;" class="fas fa-print"></i >');

                var link=document.createElement('a');
                link.href=window.URL.createObjectURL(blob);
                @if($route === 'b2b')
                link.download= id+'-DRS.pdf';
                @endif
                link.click();
            }
        });
    }
    @endif
</script>
