{!! Form::label('category_id', 'Entity being sponsored: ') !!}		  
<select name="ad[category_id][]" class="form-control category_selector select2" required>
	<option value="">Select One</option>	  
	@foreach($items as $item)
		<option value="{{$item->id}}" @if(!is_null($selected_item_id) && $selected_item_id == $item->id ) {{ "selected" }} @endif >
			{{ ucwords($item->name) }}
		</option>
	@endforeach		  
</select>