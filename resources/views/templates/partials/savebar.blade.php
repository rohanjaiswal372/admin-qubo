<div id="save-bar">
    <div class="col-md-4 col-md-offset-8 col-xs-12">
        <div class="box box-primary">
            <div class="box-body text-center">
                @include('flash::message')
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary save-btn"><i class="fa fa-floppy-o"></i> Save Changes
                    </button>
                    <a class="btn btn-danger" href="{{ URL::previous() }}">Cancel <i class="fa fa-ban"></i></a>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
