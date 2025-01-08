{{--<h3>ION Newsletter TotalSignups: {{count($newsletterusers)}}</h3> --}}
<table>
    <thead>
    <tr>
        @foreach($columns as $column)
            <th>{{$column}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach( $entries as $entry)
        <tr>
            @foreach($columns as $column )
                <td>{{$entry->$column}}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>