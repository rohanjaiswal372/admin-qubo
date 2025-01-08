@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Hopes</h1>
      <div class="pull-right">
		  <div class="btn-group">
        	<a href="{{ route('hopes.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
		  	<button class="btn btn-danger delete-selected"><i class="fa fa-times"></i> Delete Multiple</button>
		  </div>
      </div>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
      <table class=" table no-margin table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Hope</th>
					<th>Active</th>
					<th>Date</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
			@foreach($items as $item)
				<tr>
					<td>{!! Form::checkbox($item->id, $item->id, false, array('class' => '')); !!}</td>
					<td>{{ $item->id }}</td>
					<td>{{ $item->firstname }} {{ $item->lastname }}</td>
					<td>{{ $item->email }}</td>
					<td>{{ $item->description }}</td>
					<td>
					  @if( $item->active )
						Yes
					  @else
						No
					  @endif
					</td>
					<td style="white-space:nowrap;">{{ $item->created_at }}</td>
					<td><a href="{{ route('hopes.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
						<a href="{{ URL::to('/hopes/remove/'.$item->id) }}" onClick="return confirm('Are you sure you want to remove this Hope?');"><i class="fa fa-times fa-2x"></i></a></td>
				</tr>
			  @endforeach
			</tbody>
		</table>

		{!! $items->render() !!}
		

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop
@section('footer_js')

	<script>
		$('.delete-selected').on('click',function(){

			var ids = $("table input:checkbox:checked").map(function(){
				return $(this).val();
			}).get();

			if(confirm('Are you sure you would like to delete all these items?')){
				$.ajax(
						{
							type: "POST",
							url: "/hopes/multiple/",
							dataType: "json",
							data: {ids: ids,_token: '{{ csrf_token() }}' },
							beforeSend: function (request) {
								return request.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
							},
							success: function (data) {
								//$('.content').html(data.results);
								window.location.href= '/hopes';
							}

						}
				);
			}
		});

		$('table tr').click(function(){

			if (event.target.type !== 'checkbox') {
				$(':checkbox', this).trigger('click');
			}
		});
		$("input[type='checkbox']").change(function (e) {
			if ($(this).is(":checked")) {
				$(this).closest('tr').addClass("danger");
			} else {
				$(this).closest('tr').removeClass("danger");
			}
		});


	</script>
	@stop



