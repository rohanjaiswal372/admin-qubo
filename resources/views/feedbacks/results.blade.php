<table class="tablesorter table no-margin table-condensed table-hover table-responsive table-striped">
    <thead>
    <tr>
        <th>Day</th>
        <th>Date</th>
        <th>Subject</th>
        <th>User Info</th>
        <th>Market</th>
        <th>Provider</th>
        <th>Newsletter</th>
        <th>Message</th>
        <th>options</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr class="small">
            <td class="small">{{ $item->created_at->format('D') }}</td>
            <td class="small">{{ $item->created_at->format('m-d-y g:i a') }}</td>
            <td class="text-center">
                @if( isset($item->subject->name) )
                    {{ $item->subject->name }}
                @else
                    <i class="fa fa-ban"></i>
                @endif
            </td>
            <td><strong>Full Name:</strong> {{ $item->firstname.' '.$item->lastname }}<br>
                <span class="email"><strong>Email:</strong> <a href="mailto:{{$item->email}}"
                                                               target="_blank">{{ $item->email }}</a></span>
                <br><strong>Phone:</strong> {{ $item->phone }}</td>
            <td>{{ $item->market }}</td>
            <td>{{ $item->provider }}</td>
            <td class="text-center">
                @if( $item->newsletter )
                    Yes
                @else
                    No
                @endif
            </td>
            <td width="45%">{{ str_limit($item->message,1000) }}</td>
            {{--<td>{!! Form::select('status',['New' => 'new', 'Replied' => 'replied', 'Closed' => 'closed'], 1 , ['class' => 'form-control', 'data-id' => $item->id]) !!}</td>--}}
            <td>
                <a href="{{ url('/audience-relations/feedbacks/edit', $item->id) }}" title="Edit"
                   data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                <a href="{{ url('/audience-relations/feedbacks/remove/'.$item->id) }}"
                   title="Remove: {{ $item->id }}" data-toggle="tooltip"
                   onClick="return confirm('Are you sure you want to remove this Source?');"><i
                            class="fa fa-trash text-danger fa-2x"></i></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
