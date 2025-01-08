
{!! Form::label('morphable_id', ucwords($type).': ') !!}		  
<select name="schedule[morphable_id]" class="form-control morphable_id_selector select2" data-live-search='true'>
	<option value="">Select One</option>	  
	@foreach($items as $item)
		<option value="{{$item->id}}" @if(!is_null($selected_item_id) && $selected_item_id == $item->id ) {{ "selected" }} @endif >
		@if($item->name){{ ucwords($item->name) }} @endif
		@if($item->title){{ ucwords($item->title) }} @endif
		</option>
	@endforeach		  
</select>
