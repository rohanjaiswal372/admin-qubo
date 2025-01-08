{{--<h2>{{$ad->misc_content}}</h2>--}}
{{--<h3>Flight Date: {{Carbon::parse($ad->starts_at)->toDayDateTimeString()}}</h3>--}}
{{--<h3>End Date: {{Carbon::parse($ad->ends_at)->toDayDateTimeString()}}</h3>--}}
{{--<h4>Clicks: {{$ad->click}}</h4>--}}
{{--<h4>Impressions: {{$ad->impression}}</h4>--}}

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Start</th>
        <th>End</th>
        <th>Client</th>
        <th>Click-Through URL</th>
        <th>Platform</th>
        <th>Type
        <th>Entity</th>
        <th>Clicks</th>
        <th>Impressions</th>
        {{--<th>CPC</th>--}}
    </tr>
    </thead>
    <tbody>
    @foreach($campaign->advertisements as $ad)
        @if($ad->ad)
            <tr class="small">
                <td>{{$ad->ad->id}}</td>
                <td>
                    <span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad->ad['starts_at'])->format('m-d')}}</span>
                </td>
                <td>
                    <span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad->ad['ends_at'])->format('m-d')}}</span>
                </td>
                <td>{{$ad->ad->sponsor->name}}</td>
                <td>{{$ad->ad->sponsor->url}}</td>
                <td>{{$ad->ad->platform->name}}</td>
                <td>{{ mb_substr($ad->ad['morphable_type'], 4 ,strlen($ad->ad['morphable_type'])) }}</td>
                <td>@if($ad->ad->morphable_type =="App\Post"){{ucfirst($ad->ad->advertisedItem->type_id)}}
                    | {{$ad->ad->advertisedItem->title}}@else{{str_limit($ad->ad->advertisedItem->name,50)}}@endif</td>
                @if($ad->ad->category->platform->slug == "website")
                    <td>{{$ad->ad->click}}</td>
                    <td>{{$ad->ad->impression}}</td>
                    {{--<td>$</td>--}}
                @else
                    <td>N/A</td>
                    <td>N/A</td>
                    {{--<td>N/A</td>--}}
                @endif

            </tr>
        @endif
    @endforeach
   
    </tbody>
</table>

