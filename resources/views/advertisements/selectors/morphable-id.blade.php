
{!! Form::label('morphable_id', $category->name.': ') !!}		  
<select name="ad[morphable_id][]" class="form-control morphable_id_selector select2" data-live-search='true' required>
	<option value="">Select One</option>	  
	@foreach($items as $item)
		<option value="{{$item->id}}" @if(!is_null($selected_item_id) && $selected_item_id == $item->id ) {{ "selected" }} @endif >
		@if($item->name){{ ucwords($item->name) }} @endif
		@if($item->title){{ ucwords($item->title) }} @endif
		@if(get_class($item) == "App\Program")
			@if($item->show->type_id == "show")
			 Show {{ $item->show->name.", EP ".$item->episode->episode_number." ".$item->episode->name }}
			@elseif($item->show->type_id == "movie")
			 Movie: {{ $item->show->name }}
			@endif
			({!! $item->date()." ".$item->time() !!})
		@endif
		</option>
	@endforeach		  
</select>
