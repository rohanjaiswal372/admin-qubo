<table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th>ID</th>
        <th>Slug</th>
        <th>Name</th>
        <th>Count</th>
        <th>Options</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{$item->id}}</td>
            <td>{{$item->slug}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->count}}</td>
            <td>
                <a href="{{ route('tags.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                <i class="fa text-danger fa-trash-o fa-2x remove" data-id="{{$item->id}}"></i>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>