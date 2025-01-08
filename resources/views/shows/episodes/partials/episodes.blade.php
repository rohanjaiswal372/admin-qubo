<div class="table-responsive">
    <table class="table no-margin table-striped table-condensed table-hover tablesorter">
        <thead>
        <tr>
            <th>ID</th>
            <th>Season</th>
            <th>Episode Number</th>
            <th>Name</th>
            <th>Rating</th>
            <th>Publication Date</th>
            <th>Options</th>
        </tr>
        </thead>
        <tbody>
        @foreach($show->episodes as $episode)
            <tr>
                <td>{{ $episode->id }}</td>
                <td>{{$episode->season}}</td>
                <td>EP {{ $episode->episode_number }}</td>
                <td>{{ $episode->name }}</td>
                <td>{{$episode->rating}}</td>
                <td>{{ ($episode->published_at)? Carbon::parse($episode->published_at)->format('m/d/Y') : "" }} @if($episode->new)
                        <span class="label label-danger pull-right">NEW EPISODE</span>@endif
                </td>
                <td>
                    <div class="btn-group">
                        <a class='btn'
                           href="{{ route('shows.episodes.edit', $episode->id) }}"
                           title="Edit"
                           data-toggle="tooltip"><i
                                    class="fa fa-pencil-square fa-2x"></i></a>
                    </div>
            </tr>
        @endforeach
        </tbody>
    </table>