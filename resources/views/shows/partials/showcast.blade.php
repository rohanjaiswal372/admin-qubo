<table class="tablesorter table no-margin table-striped table-hover table-bordered">
    <thead>
    <tr>
        <th>ID</th>
        <th>Age</th>
        <th>Picture</th>
        <th>Title</th>
        <th>Name</th>
        <th>Real Name</th>
        <th>Options</th>
    </tr>
    </thead>
    <tbody>
    @foreach($show->casts as $cast)
        <tr>
            <td>{{ $cast->id }}</td>
            <td>{{ $cast->age }}</td>
            <td>
                <div class="col-md-4">
                @if($cast->image)
                        <img src="{{ $cast->image ? image($cast->image->url) : "https://placehold.it/320x240" }}"
                             class="img img-responsive"/>

               @else
                        <img src="{{ $cast->pod_image ? image($cast->pod_image->url) : "https://placehold.it/320x240" }}"
                             class="img img-responsive"/>
                @endif
                </div>
            </td>
            <td>{{ $cast->title}}</td>
            <td>{{ $cast->name }}</td>
            <td>{{ $cast->real_name }}</td>
            <td>
                <a href="{{ route('casts.edit', $cast->id) }}" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                <a href="{{ URL::to('shows/casts/delete/'. $cast->id) }}" title="Delete" data-toggle="tooltip"  onclick="return confirm('Are you sure you want to remove this?');"><i class="fa fa-trash-o text-danger fa-2x"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>