<input type="hidden" value="{{ $status }}">
<input type="checkbox" id="status-{{$id}}" href="status-{{$status}}" class="change_status" @if($status==1)? checked @endif value="{{ $id }}">