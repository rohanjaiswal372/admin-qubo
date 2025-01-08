<!-- Post Stats -->
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Current New Shows</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

        @foreach($new_shows as $show)
            @if($show->logo)
                <div class="col-md-2" style="background-image:url('{{ $show->pod_image ? image($show->pod_image->url) : ""}}') no-repeat center center ;">
						<a href="/shows/{{$show->id}}/edit">
							<h3 class="box-title" style="font-size:1.2em;">{{str_limit($show->name,16)}}</h3>
						</a>
						<div class="new-show-container">
							<a href="/shows/{{$show->id}}/edit">
								<center><img src="{{image($show->logo->url)}}" class="img img-responsive"/></center>
							</a>
						</div>
                </div>
            @else
                <div class="col-md-2">
                    {{$show->name}}
                </div>
            @endif
        @endforeach

    </div>
    <div class="box-footer clearfix">
        <a href="/shows/create" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus"></i> New Show</a>
        <a href="/shows" class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-eye"></i> View All Shows</a>
    </div>
</div>