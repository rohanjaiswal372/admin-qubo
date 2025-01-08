@extends("app")
@section('header')

@stop
@section("content")
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3 class="pull-left">Editing Campaign: <strong>{{$campaign->name}}</strong>
                </h3>
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ url('campaigns')}}" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i> Back
                        <a href="{{ route('campaigns.create')}}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> New</a>
                    @if(!$campaign->advertisements->isEmpty())
                        <a href="{{url('campaigns/report/'.$campaign->id)}}" class="btn btn-sm btn-info"><i
                                    class="fa fa-eye tippy"
                                    title="View Campaign Report"
                                    ></i> View Report</a>
                    @endif
                    <a href="{{ url('campaigns/remove/'.$campaign->id) }}"
                       title="Remove: {{ $campaign->name }}"
                       class="btn btn-sm btn-danger tippy"
                       onClick="return confirm('Are you sure you want to remove this campaign?');"><i
                                class="fa fa-trash-o"></i></a>
                </div>
            </div>
            <div class="clearfix"></div>
        </section>
        <!-- Main content -->
        <section class="content">
            {{ HTML::ul($errors->all()) }}

            {!! Form::model($campaign->toArray(), array('route' => array('campaigns.update', $campaign->id), 'id' => 'campaign-form', 'method' => 'PATCH', 'files' => TRUE)) !!}
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        {{--  -------- CAMPAIGN INFO BOX ---------  --}}
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title pull-left"><i class="fa fa-info-circle text-info"></i> Info:</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group-sm col-md-6 tippy"
                                         title="Owner of the campaign"
                                         >
                                        {!! Form::label('user_id', 'Assigned To: ') !!}
                                        <select name="user_id" class="form-control select2">
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}"
                                                        @if($user->id == $campaign->user_id)selected @endif >{{$user->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group-sm col-md-6 tippy"
                                         title="Status of the campaign, this controls if the advertisements under this campaign are visible on the live website."
                                         >
                                        {!! Form::label('status_id', 'Status: ') !!}
                                        <select name="status_id" class="form-control select2">
                                            @foreach($statuses as $status)
                                                <option value="{{$status->id}}"
                                                        name="{{$status->name}}"
                                                        @if( $status->id == $campaign->status->status_id) selected @endif><span class="small">{{$status->name}}</span></option>
                                            @endforeach
                                        </select>
                                        @if($campaign->approved)
                                            <span class="small"><i class="fa fa-check-circle fa-2x text-success"></i> Approved By: <strong>{{$campaign->approver->fullName}}</strong> <small>{{Carbon::parse($campaign->status->updated_at)->format('jS \o\f F, Y g:i:s a')}}</small></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group-sm col-md-6 tippy"
                                         title="Name of the campaign."
                                         >
                                        {!! Form::label('campaign[name]', 'Name: ') !!}
                                        {!! Form::text('campaign[name]', $campaign->name, ['class' => 'form-control' ,'placeholder' => 'Campaign Name', 'required' => 'required']) !!}
                                    </div>
                                    <div class="form-group-sm col-md-3">
                                        {!! Form::label('campaign[starts_at]', 'Start Date: ') !!}
                                        <div class="input-group form-group">
                                            <label class="input-group-btn" for="starts_at">
                                               <span class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></span>
                                            </label>
                                            {!! Form::text('campaign[starts_at]',Carbon::parse($campaign->starts_at)->format('m/d/Y'), ['class' => 'form-control datepicker', 'id' => 'starts_at', 'placeholder' => 'Start Date', 'required' => 'required']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group-sm col-md-3">
                                        {!! Form::label('campaign[ends_at]', 'Start Date: ') !!}
                                        <div class="input-group form-group">
                                            <label class="input-group-btn" for="ends_at">
                                                <span class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></span>
                                            </label>
                                            {!! Form::text('campaign[ends_at]',Carbon::parse($campaign->ends_at)->format('m/d/Y'), ['class' => 'form-control datepicker', 'id' => 'ends_at', 'placeholder' => 'End Date', 'required' => 'required']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group-sm col-md-12 tippy"
                                         title="People associated with aspects of the campaign, including designers, developers, sales and anyone needing to be updated on the statuses."
                                         >
                                        <i class="fa fa-user text-info"></i>
                                        {!! Form::label('campaign_followers[]', 'Followers: ') !!}
                                        <select name="campaign_followers[]"
                                                class="form-control select2" multiple>
                                            @foreach($campaign->followers as $follower)
                                                <option value="{{$follower->user->id}}"
                                                        selected>{{$follower->user->fullname}}</option>
                                            @endforeach
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group-sm col-md-12 tippy"
                                         title="Description of the campaign and any notes needed"
                                        >
                                        <hr/>
                                        {!! Form::label('campaign[description]', 'Description: ') !!}
                                        {!! Form::textarea('campaign[description]',$campaign->description, ['class' => 'form-control ckeditor', 'rows'=>'5']) !!}
                                    </div>
                                    <div class="form-group-sm col-md-12 text-center pad">
                                        <strong>Created At:</strong>
                                        <span class="label label-info"><i class="fa fa-calendar"></i> {{Carbon::parse($campaign->created_at)->format('m-d-y g:i:s a')}} </span>&nbsp;
                                        <strong>Updated At:</strong>
                                        <span class="label label-info"> <i class="fa fa-calendar"></i> {{Carbon::parse($campaign->updated_at)->format('m-d-y g:i:s a')}} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Add New Buttons  --}}
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-plus text-success"></i> Create Items:</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body text-center">
                                    <div class="btn-group">
                                    <a href="{{url('advertisements/create/'.$campaign->id)}}" class="btn btn-lg btn-info"><i class="fa fa-plus text-success"></i> Create Ad</a>
                                    <a href="{{url('posts/create/'.$campaign->id)}}" class="btn btn-lg btn-primary"><i class="fa fa-plus  text-success"></i> Create Post</a>
                                    <a href="{{url('pods/create/'.$campaign->id)}}" class="btn btn-lg btn-info"><i class="fa fa-plus  text-success"></i> Create Pod</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--  -------- ASSOCIATED ADS BOX ---------  --}}
                        <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-list text-info"></i> Items:</h3>
                                <div class="box-tools pull-right">
                                    <a href="{{ url('campaigns/items/') }}"
                                       title="View All Ads" class="tippy"
                                       ><i class="fa fa-list"></i></a>

                                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                {{--<div class="form-group-sm col-md-12">--}}
                                    {{--{!! Form::label('ads[]', 'Associate New Ad: ', ['class'=>'pull-left']) !!}--}}
                                    {{--<select name="ads[]" class="form-control select2" multiple>--}}
                                        {{--@foreach($ads as $ad)--}}
                                            {{--<option value="{{$ad->id}}">{{$ad->misc_content}}</option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                <div class="form-group-sm col-md-12">
                                    <div class="table-responsive" id="associated-items">
                                        @include('campaigns.partials.associated-items',['campaign'=>$campaign, 'col' => 'col-md-6'])
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer clearfix">
                                <a href="{{url('/advertisements/create/'.$campaign->id)}}"
                                   class="btn btn-sm btn-flat btn-default pull-right"><i class="fa fa-plus text-success"></i> Create
                                    New
                                    Ad</a> <a href="/advertisements"
                                              class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-eye"></i>
                                    View All ads</a>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        {{--  -------- SPONSORS BOX ---------  --}}
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><i class="fa fa-users text-info"></i> Sponsors:</h3>
                                    <div class="box-tools pull-right">
                                        @if($campaign->sponsor)<a href="{{ route('sponsors.edit', $campaign->sponsor->id) }}"
                                           title="Edit sponsor" class="tippy"
                                          ><i class="fa fa-pencil"></i></a> &nbsp;
                                    @endif
                                        <a href="{{ route('sponsors.index') }}"
                                           title="View all sponsors" class="tippy"
                                           ><i class="fa fa-list"></i></a>
                                        <a class="btn btn-box-tool tippy"
                                           href="{{ route('sponsors.create') }}"
                                           title="Create new sponsor"
                                           ><i class="fa text-info fa-plus-square "></i></a>
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="form-group-sm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {!! Form::label('sponsor_id', 'Sponsor: ') !!}
                                                <select name="sponsor[sponsor_id]"
                                                        class="form-control sponsor_selector select2"
                                                        data-live-search='true'>
                                                    <option value="">Select One</option>
                                                    @foreach($sponsors as $sponsor)
                                                        <option value="{{$sponsor->id}}"
                                                                url="{{$sponsor->url}}"@if($campaign->sponsor_id == $sponsor->id) {{ "selected" }} @endif >{{$sponsor->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('Logo', 'Logo: ') !!}
                                                @if($campaign->sponsor && !is_null($campaign->sponsor->logo))
                                                    <img id="sponsor-logo"
                                                         class='img img-thumbnail'
                                                         src="{{ $campaign->sponsor->logo->url }}"/>
                                                    @else
                                                    <div class="form-group-sm">
                                                    <label class="btn btn-primary btn-block btn-file tippy"
                                                           title="Upload new image"
                                                    ><span>
                                   <i class="fa fa-upload"></i> Upload Image</span><input type="file"
                                                                                          id="sponsor-logo-upload"
                                                                                          name="sponsor-logo" class="hide">
                                                    </label>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 tippy"
                                                 title="Please include a full url - http://www.domain.com."
                                               >
                                                {!! Form::label('sponsor[url]', 'URL: ') !!}
                                                {!! Form::url('sponsor[url]', ($campaign->url)? $campaign->url : (($campaign->sponsor)?$campaign->sponsor->url: "") , ['class' => 'form-control sponsor_url', 'id'=>'sponsor_url' ,'placeholder' => 'http://www.domain.com']) !!}
                                            </div>
                                            <div class="col-md-6 tippy" title="Use _blank to link to an outside website and leave it blank to stay
                                within the ION website." >
                                                {!! Form::label('sponsor[link_target]', 'Link Target: ') !!}
                                                {!! Form::select('sponsor[link_target]',
                                                [
                                                "_blank" => "_blank" ,
                                                '_parent'=>'_parent',
                                                '_self'=>'_self',
                                                '_top'=>'_top'
                                                ] ,Input::old('sponsor[link_target]'), ['class' => 'form-control select2', 'placeholder' => '_blank']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('templates.partials.savebar')
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12" id="comments">
                            @include('templates.partials.comments',['item' => $campaign])
                        </div>
                        {{--  -------- ASSETS BOX ---------  --}}
                        <div class="col-md-12">
                            @include('campaigns.partials.assets')
                        </div>
                        {{--  -------- UPLOADER BOX ---------  --}}
                        <div class="col-md-12">
                            <div class="box box-primary" id="uploader_drop_zone">
                                <div class="box-header">
                                    <div class="box-title tippy" title="Upload any files associated with the campaign. "><i class="fa fa-upload text-info"></i> Upload Campaign Assets:</div>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        {!! Form::open(array("role"=>"form","files"=>TRUE,"id"=>"upload-form")) !!}
                                        {!! Form::hidden('upload_object_id', $campaign->id ,['id' => 'upload_object_id']) !!}
                                        {!! Form::hidden('upload_media_type', '23' ,['id' => 'upload_media_type']) !!}
                                        @include('templates.uploader',["auto" => TRUE])
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop
@section('footer_js')
    <script src="{{asset('/js/fineuploader.js')}}"></script>
    <script src="{{ asset("/js/campaigns.js") }}" type="text/javascript"></script>
    <script>

        var vm = new Vue({
            el: '#comments',
            data: {
                name: 'comments'
            },
            methods: {
                getName: function () {
                    alert(this.name);
                }
            }
        });

        $(document).ready(function () {
            load_tools();
            $(".direct-chat-messages").animate({ scrollTop: $('.direct-chat-primary').height()+$('.direct-chat-primary').height() });
        });

        $.contextMenu({
            selector: '#campaign-form',
            items: {
                "save": {
                    name: "Save",
                    icon: function (opt, elem) {
                        elem.html('<i class="fa fa-floppy-o text-success"></i> Save All');
                        return 'context-menu-icon-save';
                    },
                    callback: function (menuitem, obj) {
                        $('#campaign-form').submit();
                    }
                },
                "cancel": {
                    name: "Cancel",
                    icon: function (opt, elem) {
                        elem.html('<i class="fa fa-ban text-danger"></i> Cancel');
                        return 'context-menu-icon-save';
                    },
                    callback: function (menuitem, obj) {
                        window.location.href = '{{URL::previous()}}';
                    }
                },
//                "sep1": "---------"
            }
        });


        $.contextMenu({
            selector: '.campaign-asset',
            items: {
                "copy": {
                    name: "View",
                    icon: function (opt, elem, key, item) {
                        elem.html('<i class="fa fa-eye text-info"></i> View');
                        return 'context-menu-icon-none';
                    },
                    callback: function (menuitem, obj) {
                        var url = $(this).attr('href');
                        $.colorbox({href:url});
                    }
                },
                "sep1": "---------",
                "remove": {
                    name: "Remove",
                    icon: function (opt, elem, key, item) {
                        elem.html('<i class="fa fa-trash-o text-danger"></i> Delete');
                        return 'context-menu-icon-remove';
                    },
                    callback: function (menuitem, obj) {

                        var assetID = $(this).data('imgid');
                        var campaignID = $(this).data('campaignid');
                        deleteCampaignAsset(assetID,campaignID,'image');
                    }
                },


            }
        });

        $.contextMenu({
            selector: '.campaign-document',
            items: {

                "download": {
                    name: "Download",
                    icon: function (opt, elem, key, item) {
                        elem.html('<i class="fa fa-download text-info"></i> Download');
                        return 'context-menu-icon-remove';
                    },
                    callback: function (menuitem, obj) {

                        var url = $(this).attr('href');
                        window.open(url);
                    }
                },
                "sep1": "---------",
                "remove": {
                    name: "Remove",
                    icon: function (opt, elem, key, item) {
                        elem.html('<i class="fa fa-trash-o text-danger"></i> Delete');
                        return 'context-menu-icon-remove';
                    },
                    callback: function (menuitem, obj){

                        var assetID = $(this).data('document-id');
                        var campaignID = $(this).data('campaignid');
                        deleteCampaignAsset(assetID,campaignID,'document');
                    }
                },


            }
        });

        $.contextMenu({
            selector: '.direct-chat-text',
            items: {

                "copy": {
                    name: "Copy",
                    icon: function (opt, elem, key, item) {
                        elem.html('<i class="fa fa-clipboard text-info"></i> Copy Comment');
                        return 'context-menu-icon-none';
                    },
                    callback: function (menuitem, obj) {
                        $(this).trigger('click');
                    }
                },
                "sep1": "---------",
                "remove": {
                    name: "Remove",
                    icon: function (opt, elem, key, item) {
                        elem.html('<i class="fa fa-trash-o text-danger"></i> Delete Comment');
                        return 'context-menu-icon-remove';
                    },
                    callback: function (menuitem, obj) {

                        var commentID = $(this).data('comment-id');
                        deleteComment(commentID);
                    }
                },
            }
        });

        function upload_complete() {
            $.get("/campaigns/assets/"+{{$campaign->id}}, function (response) {
                var assets = $.parseJSON(response);
                console.log(response);
                $('#assets').html(assets.campaign_assets);
                load_tools();
            });
        }

        function deleteComment(commentID) {
            $.get("/comment/delete/{{strtolower(class_basename($campaign))}}/{{$campaign->id}}/" + commentID, function (response) {
                $("#comments").html(response);
                if(typeof load_tools == 'function') load_tools();
            });

        }

        function commentFunctions() {

            $("#comment-submit").click(function (event) {
                event.preventDefault();
                event.stopImmediatePropagation(); // <-- top stop events from bubbling up
                var comment = $("#comment").val();
                $.post("/comment/add/{{strtolower(class_basename($campaign))}}/{{$campaign->id}}", {comment: comment}, function (response) {
                    $("#comments").html(response);
                    $(".direct-chat-messages").animate({ scrollTop: $('.direct-chat-primary').height()+$('.direct-chat-primary').height() });
                    load_tools();
                });
            });
            @if(Auth::user()->hasPermission('admin'))
        $("#purge").click(function () {
                $.get("/comment/purge/{{strtolower(class_basename($campaign))}}/{{$campaign->id}}", function (response) {
                    $("#comments").html(response);
                    if(typeof load_tools == 'function') load_tools();
                });
            });
            @endif

        }

    </script>

@stop