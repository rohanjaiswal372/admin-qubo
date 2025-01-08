@if(isset($campaigns))
    <div class="{{ (isset($col))? $col : "col-md-6" }}">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-money text-info"></i> Campaign:</h3>
                <div class="box-tools pull-right">
                    @if(isset($item->campaignItem) && $item->campaignItem->campaign )
                      <a class="btn  btn-box-tool" href="{{route('campaigns.edit',$item->campaignItem->campaign->id)}}"
                              ><i class="fa fa-pencil text-info"></i> </a>
                    @elseif(isset($campaignSelected) )
                        <a class="btn  btn-box-tool" href="{{route('campaigns.edit',$campaignSelected->id)}}"
                        ><i class="fa fa-pencil text-info"></i> </a>
                    @endif

                    <a href="{{ url('campaigns') }}" class="btn  btn-box-tool tippy" title="List all Campaigns"
                       ><i class="fa fa-list"></i></a>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group col-md-12 tippy" title="Select a campaign to attach to this item">
                    <select name="campaign_id"
                            class="form-control select2 campaign_selector "
                            data-required="true"
                            data-live-search='true'>
                        @if(isset($item->campaignItem) && $item->campaignItem->campaign )
                            <option value="{{$item->campaignItem->campaign->id}}"
                                    selected="selected">{{$item->campaignItem->campaign->name}}</option>
                        @elseif(isset($campaignSelected) )
                            <option value="{{$campaignSelected->id}}"
                                    selected="selected">{{$campaignSelected->name}}</option>
                        @else
                            <option value="-1" url="">Select One</option>
                        @endif
                        @foreach($campaigns as $campaign)
                            <option value="{{$campaign->id}}">{{$campaign->name}}</option>
                        @endforeach
                    </select>
                    @if(isset($item->campaignItem) && $item->campaignItem->campaign && $item->campaignItem->campaign->approved)
                        <div class="form-group text-center">
                            <br>
                            <span class="label label-success"><i class="fa fa-check-circle"></i> Approved By: <strong>{{$item->campaignItem->campaign->approver->fullName}}</strong>{{Carbon::parse($item->campaignItem->campaign->status->updated_at)->format('jS \o\f F, Y g:i:s a')}}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif