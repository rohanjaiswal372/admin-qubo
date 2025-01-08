@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Contest Results</h1>
        <div class="pull-right">
            <a href="{{ route('contests.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> All Contests </a><br>

        </div>
    </section>

    <hr class="clearfix"/>

    <section class="content">
        <h3>Contest: {{$contest->name}}</h3>

        <table class="table no-margin table-striped table-bordered table-hover table-condensed">
            <thead>
            @foreach($columns as $column)
                <th>{{$column}}</th>
            @endforeach
            <th>Options</th>
            </thead>
            <tbody>
            @foreach( $entries as $entry)
                <tr>
					@foreach($columns as $column )
						<td>{{$entry->$column}}</td>
					@endforeach
                    <td><a href="{{ URL::to('/contests/remove/'.$contest->slug.'/'.$entry->id) }}"
                           title="Remove: {{ $entry->id }}" data-toggle="tooltip"
                           onClick="return confirm('Are you sure you want to remove this Contest Entry?');"><i
                                    class="fa fa-times fa-2x"></i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
		<div class="pull-right">
		{!! $entries->render() !!}
		</div>
		
    </section><!-- /.content -->
	
</div><!-- /.content-wrapper -->

@stop
