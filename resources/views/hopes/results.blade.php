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