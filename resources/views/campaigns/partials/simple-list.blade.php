<ul class="list-group searchable">
    @foreach($campaigns_list as $campaign)
        @if($campaign)
    <li class="list-group-item list-group-item-@if($campaign->approved)success @elseif($campaign->canceled)danger @elseif ($campaign->pendingCreatives)warning @else list-group-item-info @endif ">
        <i class="fa fa-calendar"></i> {{ Carbon::parse(date('m/d/Y g:i A', strtotime($campaign->starts_at)))->format('m/d') }} - {{ Carbon::parse(date('m/d/Y g:i A', strtotime($campaign->ends_at)))->format('m/d') }}
        <a href="{{route('campaigns.edit',$campaign->id)}}" title="{{$campaign->name}}">
        {{$campaign->name}}
        </a>
        @if(Carbon::now()->gte(Carbon::parse(date('m/d/Y g:i A', strtotime($campaign->starts_at)))) && !$campaign->expired )

            @if($campaign->approved)
                <span class="badge label-success ">LIVE</span>
            @elseif($campaign->canceled)
                <span class="badge label-danger">CANCELED <i class="fa fa-ban"></i></span>
            @else
                <span class="badge label-danger">NEEDS APPROVAL <i class="fa fa-exclamation-triangle"></i></span>
            @endif
        @endif

    </li>
        @endif
        @endforeach
</ul>