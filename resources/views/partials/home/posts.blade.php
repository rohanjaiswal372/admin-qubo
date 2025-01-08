<!-- Post Stats -->
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Latest Posts</h3>

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
        <div class="table-responsive">
            <table class="table no-margin table-striped table-hover table-condensed">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stats->postsrecent as $post)
                    <tr class="small clickable-row" data-href="{{ route('posts.edit', $post['id']) }}">
                        <td>
                            <span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($post['created_at'])->format('m-d')}}</span>
                        </td>
                        <td>{{$post->type->name}}</td>
                        <td><a href="{{ route('posts.edit', $post['id']) }}"> {{$post['title']}}</a>
                        </td>
                        <td>
                            <label {!! ($post['active'])? "class='label label-success'>Live</label>" : "class='label label-danger'>Off</label>" !!}
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer clearfix">
        <a href="/posts/create" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus"></i> Post</a>
        <a href="/posts" class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-eye"></i> View All
            Posts</a>
    </div>
</div>