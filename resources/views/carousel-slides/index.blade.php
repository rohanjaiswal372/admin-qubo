@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Carousel Slides</h1>
        <div class="pull-right">
            <a href="{{ route('carousel-slides.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i>
                Create New</a>
        </div>
    </section>

    <hr class="clearfix"/>
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
                                <th>Title</th>
                                <th>Web Slide</th>
                                <th>Mobile Slide</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        @if (isset($item->title) )
                                            {{ $item->title }}
                                        @endif
                                    </td>
                                    <td>
                                        @if( isset($item->image->url) )
                                            <img style="width:200px;" src="{{ image($item->image->url) }}"/>
                                        @endif
                                    </td>
                                    <td>
                                        @if( isset($item->mobile_image->url) )
                                            <img style="width:70px;" src="{{ image($item->mobile_image->url) }}"/>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('carousel-slides.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                                        <a href="/carousel-slides/remove/{{ $item->id }}"
                                           onClick="javascript:return confirm('Are you sure you want to remove this slide?')"><i
                                                    class="fa fa-trash-o text-danger fa-2x"></i></a>
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


