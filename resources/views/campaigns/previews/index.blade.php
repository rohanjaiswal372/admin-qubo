<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>

		.page-break {
			page-break-after: always;
		}
		body{
			font-family:Helvetica Neue, Helvetica, Arial;
		}
	</style>
	{{  HTML::style(elixir('css/admin.css')) }}
</head>
<body>
<h3>ION Television: {{ $campaign->name}} Sponsorship</h3>
<table class="table no-margin table-striped table-condensed">
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
					<td class="text-muted small">N/A</td>
					<td class="text-muted small">N/A</td>
					{{--<td>N/A</td>--}}
				@endif

			</tr>
		@endif
	@endforeach

	</tbody>
</table>

@foreach($campaign->advertisements as $item)
	@if(in_array($item->ad->platform->slug,['mobile','ipad']))
		<img style="max-height:900px;max-width:700px;" src="{{ url("uploads/ion-television-ad-preview-".$item->ad->id.".png") }}" />
	@else
		<img style="width:100%;" src="{{ url("uploads/ion-television-ad-preview-".$item->ad->id.".png") }}" />
	@endif
@endforeach
</body>
</html>