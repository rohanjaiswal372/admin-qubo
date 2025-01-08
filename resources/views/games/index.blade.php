@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Games</h1>
        <div class="pull-right">
            <a href="{{ route('games.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Create New</a>
        </div>
    </section>

    <hr class="clearfix"/>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">
                        <table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Path</th>
                                <th>Tags</th>
                                <th>Embed ID</th>
                                <th>Scope</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr @if(!$item->active) class="text-muted" @endif>
                                    <td>@if($item->active)
                                            <span class="label label-success">&nbsp;</span>
                                        @else
                                            <span class="label label-danger">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td><h3>{{ $item->title }}</h3></td>
                                    <td class="col-md-1">@if($item->images)
                                            @foreach($item->images as $image)
                                                <img src="{{image($image->url)}}" class="img img-thumbnail"/>
                                            @endforeach
                                        @endif</td>

                                    <td>{{ $item->path }}</td>
                                    <td class="col-md-2">@if($item->tags)
                                            @foreach($item->tags as $tag)
                                                       <span class="label label-info">{{$tag->name}}</span>
                                                @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $item->embed_id }}</td>
                                    <td>{{ $item->scope }}</td>
                                    <td>
                                        <a href="{{ route('games.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                                        <a href="{{ URL::to('games/delete/'. $item->id) }}"><i class="fa fa-trash-o text-danger fa-2x"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop



