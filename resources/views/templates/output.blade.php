@extends("app")
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="box box-primary box-solid">
            <section class="content-header box-header with-border">
                <h3 class="box-title">Debug</h3>

                <div class="box-tools pull-right">

                </div>

            </section>

            <!-- Main content -->
            <div class="content box-body">

             <br clear="all"/>@foreach($debug as $line)

                                         @if(!is_array($line))
                                            <p><pre>{!! $line !!}</pre></p>
                                         @else
                                                <ul>
                                                @foreach($line as $item)
                                                    <li>{!! $item !!}</li>
                                                    @endforeach
                                                </ul>

                                         @endif

                                     @endforeach



            </div><!-- /.content -->
            <div class="box-footer">
                <a class="btn btn-danger" href="{{ URL::previous() }}">Back <i class="fa fa-ban"></i></a>
            </div>
    </div>
    </div><!-- /.content-wrapper -->

@stop
@section('footer_js')

@stop



