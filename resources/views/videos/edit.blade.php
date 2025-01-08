@extends("app")
@section('header')
    <style>

        .time-display {
            font-family: Consolas, Menlo, Monaco, monospace;
            color: white;
        }

        .progress {
            margin: 1px;
            background: #555;
        }

        .marker {
            position: absolute;
            margin-top: -2px;
            z-index: 1;
            height: 26px;
            width: 4px;
        }

    </style>
    @stop
    @section("content")
            <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left"><h3>Edit Video Info:</h3></div>
            <div id="debug"></div>
            <div class="pull-right">

                <div class="btn-group">
                    @if(Auth::user()->hasPermission("brightcove"))<a class="btn btn-info" target="_blank" href="https://studio.brightcove.com/products/videocloud/media/videos/{{$video->brightcove_id}}"><i class="fa fa-pencil"></i> Edit on Brightcove</a>@endif
                    <a class="btn btn-primary" href="{{ URL::previous() }}">Back <i class="fa fa-undo"></i></a>
                    <a href="{{ URL::to('/video/delete/'.$video->id) }}"
                       title="Delete"
                       data-toggle="tooltip"
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to remove this video?');" ><i class="fa fa-times"></i> Delete Video</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </section>

        <!-- Main content -->
        <section class="content">

            {{ HTML::ul($errors->all()) }}

            {{--{!! Form::model($video->toArray(), array('route' => array('videos.update', $video->id), 'method' => 'PUT', 'files' => TRUE)) !!}--}}
            <div class="row">
                <div class="col-md-6">
                    <div class="box" id="video-box">
                        <div class="box box-primary pad">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-video-camera"></i> <strong>Brightcove
                                        ID:</strong> {!! (!is_null($video->brightcove_id))? $video->brightcove_id : ""  !!}
                                </h3>

                            </div>
                            <div class="box-body text-center">
                                <div class="row">
                                    <div class="row bg-black">
                                        <div class="col-md-3  time-display"
                                             title="elapsed time"
                                             id="time-display">00:00:00:00
                                        </div>
                                        <div class="col-md-6" style="padding:0;">

                                            <div class="progress">
                                                <div id="mark-in"
                                                     class="marker progress-bar-warning marker-in"
                                                     style="left: 0%"></div>
                                                <div id="mark-out"
                                                     class="marker  progress-bar-danger"
                                                     style="left: 100%"></div>
                                                <div class="progress-bar progress-bar-striped"
                                                     id="seek-bar"
                                                     role="progressbar"
                                                     style=" min-width:1em;">
                                                    <span>0</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-3  time-display" title="total time" id="time-total">
                                            00:00:00:00
                                        </div>
                                    </div>
                                    <div class="col-md-12 bg-black">
                                            <div  class="embed-responsive embed-responsive-16by9">
                                                <video id="video" data-account="3670015105001"
                                                       data-player="rJoSfgYW"
                                                       data-embed="default"
                                                       class="video-js embed-responsive-item"
                                                       >
                                                    {{--<source src="{{asset('proxy.php?type=video&length='.$brightcove_info->length.'&size='.$brightcove_info->FLVFullLength->size.'&filename='.str_slug($brightcove_info->name).'&url='.urlencode($brightcove_info->FLVFullLength->url))}}">--}}
                                                    Your Browser does not support HTML5 Video
                                                    </video>
                                                    <script src="//players.brightcove.net/3670015105001/rJoSfgYW_default/index.min.js"></script>
                                            </div>
                                    </div>
                                    <div class="row bg-black" id="video-tools">
                                        <div class="col-md-3">
                                            <a class="btn btn-sm mark-in"><i class="fa fa-sign-in"></i></a>
                                            <a class="btn btn-sm trim"><i class="fa fa-scissors"></i></a>
                                            <a class="btn btn-sm mark-out"><i class="fa fa-sign-out"></i></a>
                                            <a class="btn btn-sm dl-snapshot"><i class="fa fa-camera-retro"></i></a>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="btn-group">
                                                <a class="btn btn-sm fast-backward"><i class="fa fa-fast-backward"></i></a>
                                                <a class="btn btn-sm step-backward"><i class="fa fa-step-backward"></i></a>
                                                <a class="btn btn-sm play"><i class="fa fa-play"></i></a>
                                                <a class="btn btn-sm step-forward"><i
                                                            class="fa fa-step-forward"></i></a>
                                                <a class="btn btn-sm fast-forward"><i
                                                            class="fa fa-fast-forward"></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="col-md-6">

                                                <input class='pull-left volume-bar'
                                                       type="range"
                                                       id="volume-bar"
                                                       min="0"
                                                       max="1"
                                                       step="0.1"
                                                       value="1">

                                            </div>
                                            <div class="col-md-6">
                                                <div class="btn-group">
                                                    <a class="btn btn-xs mute"><i class="fa fa-volume-off"></i></a> <a
                                                            class="btn btn-xs fullscreen"><i
                                                                class="fa fa-expand"></i></a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">
                                        <small title="{{Carbon::parse(date('r',$brightcove_info->created_at/1000))->toDayDateTimeString()}}"
                                               data-toggle="tooltip"><strong>Created
                                                At: </strong><span class="label label-info"><i
                                                        class="fa fa-calendar"></i> {{Carbon::parse(date('r',$brightcove_info->created_at/1000))->format('m/d g:ia')}}</span>
                                        </small>

                                        <small title="{{Carbon::parse(date('r',$brightcove_info->updated_at/1000))->toDayDateTimeString()}}"
                                               data-toggle="tooltip"><strong>Modified
                                                At: </strong><span class="label label-success"><i
                                                        class="fa fa-calendar"></i> {{Carbon::parse(date('r',$brightcove_info->updated_at/1000))->format('m-d g:ia')}}</span>
                                        </small>
                                    </div>
                                    <div class="row text-center pad">
                                        <h4>Thumbnails</h4>
                                        <div class="col-md-9">
                                            <h5>Large - 480x360px </h5>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <a id="large-thumbnail"
                                                   download="{{str_slug($brightcove_info->name).".png"}}"> <img
                                                            class="embed-responsive-item snapshot"
                                                            src="{{$brightcove_info->images->poster->sources[1]->src}}"/>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <h5>Small - 120x90px</h5>
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <a id="small-thumbnail"
                                                   download="{{str_slug($brightcove_info->name).".png"}}"> <img
                                                            class="embed-responsive-item snapshot"
                                                            src="{{$brightcove_info->images->thumbnail->sources[1]->src}}"/>
                                                </a>
                                            </div>
                                        </div>

                                        <canvas id="video-canvas" class="hide"></canvas>

                                    </div>
                                    <div class="row">

                                        <small>Shotcuts:</small>
                                        <div class="label label-default" title="Snapshot" data-toggle="tooltip">
                                            <strong>S</strong>
                                        </div>
                                        <div class="label label-default" title="Play/Paue" data-toggle="tooltip">
                                            <strong>Space</strong>
                                        </div>
                                        <div class="label label-default" title="Step-Backwards" data-toggle="tooltip">
                                            <i class="fa fa-arrow-left"></i>
                                        </div>
                                        <div class="label label-default" title="Step-Forward" data-toggle="tooltip">
                                            <i class="fa fa-arrow-right"></i>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
</div>
                    </div>
                        <div class="col-md-6">
                            <div class="box box-primary pad">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Video Fields:</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div id="call-to-action-wrapper">
                                        {!! Form::open( array('route' => array('videos.edit', $video->id))) !!}
                                        {!! Form::hidden('brightcove_id', $video->brightcove_id) !!}
                                        <div class="row">
                                            <div class="col-md-12">

                                                    <div class="form-group col-md-12">
                                                        <div data-role="tagsinput" class="tags">
                                                            <label>Tags:</label> <input type="text"
                                                                                        class='form-control'
                                                                                        id="tags"
                                                                                        name="tags"
                                                                                        data-role="tagsinput"
                                                                                        value="@if(!empty($brightcove_info->tags)){!!implode(',',$brightcove_info->tags)!!} @endif"/>
                                                        </div>
                                                    </div>


                                                <div class="form-group col-md-6">
                                                    {!! Form::label("name", 'Title:') !!}
                                                    {!! Form::text('name', $brightcove_info->name , ['class'=> 'form-control'] ) !!}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {!! Form::label("Description", 'Short Desc:') !!}
                                                    {!! Form::text('description', $brightcove_info->description , ['class'=> 'form-control'] ) !!}
                                                </div>
                                                <div class="form-group col-md-12">
                                                    {!! Form::label("Long Description", 'Long Desc:') !!}
                                                    {!! Form::textarea('long_description', $brightcove_info->long_description , ['class'=> 'form-control', 'rows'=>2] ) !!}
                                                </div>
                                                @if($brightcove_info->all_time_plays > 0 )
                                                    <h4>Brightcove Analytics:</h4>
                                                    <div class="form-group col-md-6 ">
                                                        <div class="col-md-10 col-md-offset-1">
                                                            <div class="small-box bg-aqua">
                                                                <div class="inner ">
                                                                    <h3>{{$brightcove_info->all_time_plays}}</h3>
                                                                    <p>Total Plays:</p>
                                                                </div>
                                                                <div class="icon"><i class="fa fa-video-camera"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 ">
                                                        <div class="col-md-10 col-md-offset-1">
                                                            <div class="small-box bg-blue">
                                                                <div class="inner ">
                                                                    <h3>{{$brightcove_info->last_month_plays}}</h3>
                                                                    <p>Last 30 Days:</p>
                                                                </div>
                                                                <div class="icon"><i class="fa fa-film"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="box box-primary pad">
                                <div class="box-header">
                                    <h3 class="box-title">Custom Fields:</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div id="call-to-action-wrapper">

                                        @if(!is_null($brightcove_info->custom_fields))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @foreach(get_object_vars($brightcove_info->custom_fields) as $key => $val)
                                                        <div class="form-group col-md-3">
                                                            {!! Form::label("custom_fields[".$key."]", ucfirst($key).': ') !!}
                                                            {!! Form::text("custom_fields[".$key."]", $val , ['class'=> 'form-control'] ) !!}
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        @else
                                            <h4>no custom fields!</h4>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="box box-info pad">
                                <div class="box-header">
                                    <h3 class="box-title">Available Versions:</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="table-responsive">
                                        @if(!empty($brightcove_info->renditions))
                                            <table class="table no-margin table-striped table-condensed">
                                                <thead>
                                                <tr>
                                                    <th>Codec / Type</th>
                                                    <th>Encoding Rate</th>
                                                    <th>Dimensions</th>
                                                    <th>FileSize</th>
                                                    <th>Options</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($brightcove_info->renditions as $rendition)
                                                    <tr>
                                                        @if(property_exists($rendition,'src') && property_exists($rendition,'height'))
                                                        <td>{{$rendition->codec}} | {{$rendition->container}}</td>
                                                        <td>@if(property_exists($rendition, 'encoding_rate')){{$rendition->encoding_rate}} @endif</td>
                                                        <td>@if(property_exists($rendition, 'height')){{$rendition->height}} x {{$rendition->width}}@endif</td>
                                                        <td>@if(property_exists($rendition, 'size')){{number_format($rendition->size/ 1048576, 2) . ' MB'}} @endif</td>
                                                        <td><a href="{{$rendition->src}}" target="_blank"><i class="fa fa-eye"></i></a> </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                    </div>

                                    @else
                                        <h4>No Versions Available!</h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                @include('templates.partials.savebar')
                    </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop
@section('footer_js')
    <script src="//sadmin.brightcove.com/js/BrightcoveExperiences.js"></script>
    <script src="{{ asset('js/bootstrap-tagsinput.min.js') }}"></script>

    <script>
        $(document).ready(function () {

            var myPlayer,
                    changeVideo;
            videojs("video").ready(function(){
                myPlayer = this;
                myPlayer.src({"type":"video/mp4", "src":"{{$brightcove_info->secureVideo}}"});
               // myPlayer.play()
            });


            $('.snapshot').on('click', function (e) {
                //loadSnapshot($(this).attr('id'));

            });

            $('.snapshot').contextmenu(function (e) {
                e.preventDefault();
            });

            $('.dl-snapshot').on('click', function (e) {
                loadSnapshot('large-thumbnail');
                loadSnapshot('small-thumbnail');
            });

            function updateMarker(target, video) {
                var time = parseInt((video.currentTime / video.duration) * 100),
                        inPos = parseInt($("#mark-in").css('left')),
                        outPos = parseInt($("#mark-out").css('left')),
                        markIn = parseInt((inPos / (video.duration * 100)) * 1000),
                        markOut = parseInt((outPos / (video.duration * 100)) * 1000);
                if (markIn < markOut)
                    $("#" + target).css("left", time + "%");
                else if (markOut > markIn)  $("#mark-in").css("left", (time - 10) + "%");
                else $("#mark-out").css("left", 100 + "%");
            }

            var v = document.querySelector('video'),
                    frameTime = 1 / 30,//30 fps
                    tools = $('#video-tools'),
                    seekBar = $('#seek-bar'),
                    progress = seekBar.parent(),
                    toolslist = ['.step-forward', '.step-backward', '.fast-forward', '.fast-backward'],
                    stepForward = tools.find('.step-forward'),
                    stepBackward = tools.find('.step-backward'),
                    fastForward = tools.find('.fast-forward'),
                    fastBackward = tools.find('.fast-backward'),
                    markIn = tools.find('.mark-in'),
                    markOut = tools.find('.mark-out'),
                    trim = tools.find('.trim'),
                    fullScreen = tools.find('.fullscreen'),
                    play = tools.find('.play'),
                    mute = tools.find('.mute'),
                    volume = tools.find('.volume-bar');

            /* Custom Video Player Buttons */
            progress.click(function (e) {
                var value_clicked = e.offsetX * v.duration / 100;
                var time = v.duration * (value_clicked / 100);
                // Update the video time
                v.currentTime = time;
            });
            markIn.click(function (e) {
                updateMarker('mark-in', v);
            });
            markOut.click(function (e) {
                updateMarker('mark-out', v);
            });
            volume.change(function () {
                v.volume = $(this).val();
                if (v.muted) mute.find('i').removeClass('fa-times text-danger').addClass('fa-volume-off');
            });
            mute.click(function () {

                if (!v.muted) {

                    $(this).find('i').removeClass('fa-volume-off').addClass('fa-times text-danger');
                }
                else {
                    $(this).find('i').removeClass('fa-times text-danger').addClass('fa-volume-off');
                }
                v.muted = !v.muted;

            });
            stepForward.click(function () {
                if (v.currentTime < v.duration) v.currentTime = Math.min(v.duration, v.currentTime + frameTime);
            });
            stepBackward.click(function () {
                if (v.currentTime > 0) v.currentTime -= frameTime;
            });
            fastForward.click(function () {
                v.currentTime = v.duration;
            });
            fastBackward.click(function () {
                v.currentTime = 0;
            });
            play.click(function () {
                if (v.paused) {
                    v.play();
                    $(this).find('i').removeClass('fa-play').addClass('fa-pause');
                    seekBar.addClass('active');
                } else {
                    v.pause();
                    $(this).find('i').removeClass('fa-pause').addClass('fa-play');
                    seekBar.removeClass('active');
                }
            });
            fullScreen.click(function () {
                if (v.requestFullscreen) {
                    v.requestFullscreen();
                } else if (v.mozRequestFullScreen) {
                    v.mozRequestFullScreen(); // Firefox
                } else if (v.webkitRequestFullscreen) {
                    v.webkitRequestFullscreen(); // Chrome and Safari
                }
            });

            /* Keybord Events   */
            window.addEventListener('keydown', function (evt) {
                var key = evt.keyCode;

                if (key === 109 | key === 189) { //subtract | volume-down
                    evt.preventDefault();
                    if (v.volume > 0.1) v.volume -= 0.1;
                    volume.val(v.volume);
                }
                else if (key == 107 | key === 187) {//add | volume-up
                    evt.preventDefault();
                    if (v.volume < 0.9) v.volume += 0.1;
                    volume.val(v.volume);
                }
                else if (key === 83) { // s
                    loadSnapshot('large-thumbnail');
                    loadSnapshot('small-thumbnail');
                }
                else {

                    if (v.paused) { //or you can force it to pause here
                        if (key === 37) { //left arrow
                            if (v.currentTime > 0) {
                                //one frame back
                                v.currentTime -= frameTime;
                                tools.find('.step-backward').addClass('btn-primary');
                            }
                        } else if (key === 39) { //right arrow
                            if (v.currentTime < v.duration) {
                                //one frame forward
                                //Don't go past the end, otherwise you may get an error
                                v.currentTime = Math.min(v.duration, v.currentTime + frameTime);
                                tools.find('.step-forward').addClass('btn-primary');
                            }
                        }
                        else if (key === 32) { //space
                            v.play();
                            play.addClass('btn-primary');
                            seekBar.addClass('active');
                        }
//                        else if (key === 38) { //up arrow
//                            v.currentTime = 0;
//                        }
//                        else if (key === 40) { //down arrow
//                            v.currentTime = v.duration;
//                        }
                    }
                    else {
                        v.pause();
                        play.removeClass('btn-primary');
                    }
                }

            });

            window.addEventListener('keyup', function (evt) {
                $(toolslist).each(function (i, e) {
                    $(tools).find(e).removeClass('btn-primary');
                })
            });

            v.addEventListener('loadedmetadata', function (ev) {

                $('#time-total').text("00:" + format(v.duration) + ":00");

                v.addEventListener('timeupdate', function (ev) {
                    time = format(v.currentTime);
                    timeVal = (100 / v.duration) * v.currentTime;
                    remTime = format(v.duration - v.currentTime);

                    $('#time-display').text("00:" + remTime + ":00");

                    seekBar.css("width", timeVal + "%").text(time); // update the seekbar.

                    if (v.paused) {
                        play.removeClass('btn-primary');
                        seekBar.removeClass('active');
                    }

                }, false);
            });

            function loadSnapshot(target) {

                var v = document.querySelector('video'),
                        c = document.querySelector('canvas'),
                        ctx = c.getContext('2d');
                var ratio = v.videoWidth / v.videoHeight,
                        w = v.videoWidth,
                        h = parseInt(w / ratio, 10);

                c.width = w;
                c.height = h + 40;

                if (target == 'large-thumbnail') {
                    ctx.drawImage(v, 0, 0, w, h, 0, 0, w, h);
                }
                else {
                    ctx.drawImage(v, 0, 0, w, h, 0, 0, w / 2, h / 2);
                }
                var img = new Image();
                img.src = ctx.canvas.toDataURL('image/png');
                img.crossOrigin = "Anonymous";

                $("#" + target + ' img').css({"width": "100%"}).attr('src', img.src);
                $("#" + target).attr('href', img.src);

            }

            function format(time) {
                var hours = parseInt((time / 60 / 60) % 60, 10),
                        mins = parseInt((time / 60) % 60, 10),
                        secs = parseInt(time, 10) % 60,
                        frames = parseInt(time * 100, 10) % 30,
                        hourss = (hours < 10 ? '0' : '') + parseInt(hours, 10) + ':',
                        minss = (mins < 10 ? '0' : '') + parseInt(mins, 10) + ':',
                        secss = (secs < 10 ? '0' : '') + (secs % 60),
                        timestring = ( hourss !== '00:' ? hourss : '' ) + minss + secss + ":" + frames;
                return timestring;
            };

        });

    </script>
@stop