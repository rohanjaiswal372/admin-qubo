<div id="assets">
    @if($campaign)
        @if(count($campaign->images) || count($campaign->documents))
            <div class="box box-primary pad">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-file text-info"></i> Assets:</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        @foreach($campaign->images as $img)
                            <div class="col-md-4">
                                <a href="{{$img->url}}"
                                   data-imgid="{{$img->id}}"
                                   data-campaignid="{{$campaign->id}}"
                                   data-type="Campaign"
                                   rel="{{$campaign->name}}"
                                   class="campaign-asset tippy"
                                   title="Click to download, right click for options" download>
                                    <img class='img img-thumbnail colorbox' src="{{$img->url}}"/></a>
                            </div>
                        @endforeach
                        @foreach($campaign->documents as $document)
                            <div class="col-md-4 text-center">
                                <a href="{{document($document->path)}}"
                                   data-campaignid="{{$campaign->id}}"
                                   data-document-id="{{$document->id}}"
                                   data-type="Campaign"
                                   rel="{{$campaign->name}}"
                                   title="{{$document->name}}"
                                   target="_blank"
                                   class="tippy campaign-document" download><i class="fa {{$document->icon}} fa-4x"></i><label
                                            class="small">{{str_limit($document->name,10).".".$document->extension}}</label></a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>


