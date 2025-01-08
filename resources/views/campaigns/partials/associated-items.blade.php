<table class="table no-margin table-striped table-condensed tablesorter table-hover"
       data-scroll-y="70vh"
       data-scroll-collapse="true"
       data-paging="false"
       data-col-reorder="true">
    <thead>
    <tr>
        <th>ID</th>
        <th>Type</th>
        <th>AdID</th>
        <th>Date</th>
        <th>Entity</th>
        <th>Preview</th>
        <th>Status</th>
        <th>Options</th>
    </tr>
    </thead>
    <tbody>
    @foreach($campaign->items as $campaign_item)
        @if($campaign_item->class == "Advertisement" &&  $campaign_item->ad )
            <tr>
                <td>{{$campaign_item->id}}</td>
                <td>{{$campaign_item->class}}</td>
                <td>{{$campaign_item->ad->id}}</td>
                <td>
                    <a href="{{ url('advertisements#calendar-tab') }}"><span
                                class="badge"><i
                                    class="fa fa-calendar-o"></i> {{Carbon::parse($campaign_item->ad->starts_at)->format('m-d')}}</span></a>
                </td>
                {{--<td>--}}
                    {{--<a href="{{ route('sponsors.edit', $campaign_item->ad->sponsor_id) }}"--}}
                       {{--title="{{$campaign_item->ad->sponsor->url}}"--}}
                       {{--data-toggle="tooltip">{{$campaign_item->ad->sponsor->name}}</a>--}}
                {{--</td>--}}
                <td class="small">
                    @if($campaign_item->ad->advertisedItem )
                        {{$campaign_item->ad->platform->name.' | '.substr($campaign_item->ad->morphable_type, 4 ,strlen($campaign_item->ad->morphable_type)).' | '.$campaign_item->ad->advertisedItem->name.' '.ucfirst(camel_case($campaign_item->ad->advertisedItem->type_id))}}
                        {{--@if($campaign_item->ad->morphable_type == "App\Post" || $campaign_item->ad->morphable_type == "App\Page" || $campaign_item->ad->morphable_type == "App\Show" )--}}
                        {{--<a href="{{$campaign_item->ad->getPreviewUrlAttribute()}}"--}}
                        {{--class="pull-right"--}}
                        {{--target="_blank"><button class="btn btn-xs btn-default"><i class="fa fa-eye"></i> View</button></a>--}}
                        {{--@endif--}}
                    @endif
                </td>
                <td>
                    @if($campaign_item->ad->running)
                        @if($campaign_item->ad->morphable_type == "App\Post" || $campaign_item->ad->morphable_type == "App\Page" || $campaign_item->ad->morphable_type == "App\Show"  )
                            <a href="{{$campaign_item->ad->advertisedItem->url()}}"
                               class="btn-block btn btn-xs btn-default"
                               target="_blank"><i class="fa fa-eye"></i>
                                View Live Ad
                            </a>
                        @endif
                    @else
                        @if(!is_null($campaign_item->ad->preview_url))
                            <a href="{{dev_site_url($campaign_item->ad->remote_preview_path,FALSE, FALSE)}}"
                               class="btn  btn-block btn-warning"
                               target="_blank"><i class="fa fa-eye"></i>
                                View Preview</a>
                        @endif
                    @endif
                </td>
                <td>
                    <label
                            @if($campaign_item->ad->expired) class='label label-danger'>Expired
                        @else
                            @if($campaign_item->ad->running && $campaign->approved)
                                class='label
                                label-success'>Live
                            @else class='label label-warning'>Scheduled
                            @endif

                        @endif
                    </label>
                </td>
                <td>
                    <a href="{{ route('advertisements.edit', $campaign_item->ad->id) }}"><i
                                class="fa fa-pencil"></i></a>
                    <a href="{{ url('campaigns/detach_ad/'.$campaign_item->ad->id.'/'.$campaign->id) }}"><i
                                class="fa text-danger fa-trash-o"></i></a>
                </td>
            </tr>
            @elseif($campaign_item->class == "Post" && $campaign_item->post )
                <tr>
                    <td>{{$campaign_item->id}}</td>
                    <td>{{$campaign_item->class}}</td>
                    <td>{{$campaign_item->post->id}}</td>
                    <td>
                         <span
                                 class="badge"><i
                                     class="fa fa-calendar-o"></i> {{Carbon::parse($campaign_item->post->activates_at)->format('m-d')}}</span>
                    </td>

                    <td class="small">
                            {{ucfirst(camel_case($campaign_item->post->type_id))." | ".$campaign_item->post->title}}
                    </td>
                    <td>
                        @if($campaign_item->post->running)
                            <a href="{{$campaign_item->post->url}}" class="btn-block btn btn-xs btn-default"
                               target="_blank"><i class="fa fa-eye"></i>
                                View Live Post
                            </a>
                            @else
                            <a href="{{$campaign_item->post->dev_url}}" class="btn  btn-block btn-warning"
                               target="_blank"><i class="fa fa-eye"></i>
                                View Preview</a>

                        @endif
                    </td>
                    <td><label
                                @if($campaign_item->post->expired) class='label label-danger'>Expired
                            @else
                                @if($campaign_item->post->running && $campaign->approved)
                                    class='label
                                    label-success'>Live
                                @else class='label label-warning'>Scheduled
                                @endif

                            @endif
                        </label></td>
                    <td>
                        <a href="{{ route('posts.edit', $campaign_item->post->id) }}"><i
                                    class="fa fa-pencil"></i></a>
                        <i class="fa text-danger detach_ad fa-trash-o" data-adid="{{$campaign_item->id}}" data-campaign="{{$campaign->id}}"></i>
                    </td>
                </tr>
        @elseif($campaign_item->class == "GridCell" && $campaign_item->pod )
            <tr>
                <td>{{$campaign_item->id}}</td>
                <td>Pod</td>
                <td>{{$campaign_item->pod->id}}</td>
                <td>
                    @if($campaign_item->pod->gridSchedule)
                        <a href="{{ url('/pods/'.$campaign_item->pod->gridSchedule->grid->id) }}"><span
                                    class="badge"><i
                                        class="fa fa-calendar-o"></i> {{Carbon::parse($campaign_item->pod->gridSchedule->starts_at)->format('m-d')}}</span></a>
                    @endif
                </td>

                <td class="small">
                    {{ucfirst(camel_case($campaign_item->pod->type))." | ".$campaign_item->pod->title}}
                </td>
                <td>
                    @if($campaign_item->pod->active)
                        <a href="{{$campaign_item->pod->path}}" class="btn-block btn btn-xs btn-default"
                           target="_blank"><i class="fa fa-eye"></i>
                            View Live Pod
                        </a>
                    @else
                        <a href="{{$campaign_item->pod->path}}" class="btn  btn-block btn-warning"
                           target="_blank"><i class="fa fa-eye"></i>
                            View Preview</a>

                    @endif
                </td>
                <td>

                    @if($campaign_item->pod->gridSchedule)
                           <label class="label label-warning">Scheduled</label>
                        @endif
                </td>
                <td>
                    <a href="{{ route('pods.edit', $campaign_item->pod->id) }}"><i
                                class="fa fa-pencil"></i></a>
                    <i class="fa text-danger detach_ad fa-trash-o" data-adid="{{$campaign_item->id}}" data-campaign="{{$campaign->id}}"></i>
                </td>
            </tr>

        @else
            <tr class="small muted">
                <td>{{$campaign_item->id}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Associated Ad no longer exists</td>
                <td>
                    <i class="fa fa-pencil text-muted"></i>
                    {{--<a href="{{ url('campaigns/detach_ad/'.$campaign_item->id.'/'.$campaign->id) }}">--}}
                    <i class="fa text-danger detach_ad fa-trash-o" data-adid="{{$campaign_item->id}}" data-campaign="{{$campaign->id}}"></i>
                    {{--</a>--}}
                </td>


            </tr>
        @endif
    @endforeach
    </tbody>
</table>