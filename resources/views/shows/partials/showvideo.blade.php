<div class="video-preview pad text-center">
    @if($show->preview && $show->preview->brightcove_id)
        <a href="#"
           data-videoid="{{ $show->preview->brightcove_id  }}"
           class="video_preview"> <img
                    class="img-responsive"
                    src="{{ (!is_null($show->preview->still()))? $show->preview->still() : "missing image" }}"/>
            <em class="fa fa-play-circle-o play-btn fa-4x"></em></a>

    @else
        <a class="video_preview_add"
           title="Enter a Brightcove Video ID below and save to add a video preview to this episode"
           data-toggle="tooltip"> <em class="fa fa-plus-circle text-success fa-4x"></em> </a>
        <a href="#" title="Upload new Preview"
           data-toggle="tooltip" id="upload_preview"> <em class="fa fa-cloud-upload fa-4x"></em></a>
    @endif
    <br class="cleafix">
    <div class="form-group col-md-6">
        @if($show->preview)
            {!! Form::label('brightcove_id', 'Video ID: ') !!}
            {!! Form::input('text','brightcove_id', ($show->preview->brightcove_id)? $show->preview->brightcove_id : " No ID Entered", ['class' => 'form-control','id' => 'brightcove_id']) !!}
        @else
            {!! Form::label('brightcove_id', 'Video ID: ') !!}
            {!! Form::input('text','brightcove_id', '', ['class' => 'form-control', 'placeholder' => 'Brightcove ID','id' => 'brightcove_id']) !!}
        @endif
    </div>
    <div class="form-group col-md-6">
        {!! Form::label('published_at', 'Publication Date: ') !!}
        <div class="input-group">
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
            {!! Form::text('published_at', ($show->published_at)? Carbon::parse($show->published_at)->format('m/d/Y'): "", ['class' => 'form-control datepicker', 'placeholder' => '']) !!}
        </div>
    </div>
</div>

