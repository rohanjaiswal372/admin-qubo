@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Banners</h1>
        <div class="pull-right">
            <a href="{{ route('banners.create') }}"
               class="btn btn-success"><i class="fa fa-pencil-square"></i> Create New</a>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">

                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">
                        <table class="tablesorter table no-margin">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Active</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        @if( isset($item->image) )
                                            <a target="_blank" href="{{ image($item->image->url) }}">
                                                <img src="{{ image($item->image->url) }}"
                                                     style="max-width: 250px; height: auto;"/> </a>
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        @if ( $item->active == 1 )
                                            On
                                        @else
                                            Off
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('banners.edit', $item->id) }}"
                                           title="Edit: {{ $item->title }}"
                                           data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                                        <a href="{{ URL::to('/banners/remove/'.$item->id) }}"
                                           title="Remove: {{ $item->title }}"
                                           data-toggle="tooltip"
                                           onClick="return confirm('Are you sure you want to remove this record?');"><i
                                                    class="fa fa-times fa-2x"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


