@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Settings</h1>
            <div class="pull-right">
                <a href="{{ route('settings.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i>
                    Create New</a>
            </div>
        </section>

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
                            <table class="tablesorter table no-margin">
                                <thead>
                                <tr>
                                    <th>Setting</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->setting }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>
                                            @if ( $item->type == 'switch' )
                                                @if( $item->on_off )
                                                    On
                                                @else
                                                    Off
                                                @endif
                                            @else
                                                {{ $item->value }}
                                            @endif
                                        </td>
                                        <td><a href="{{ route('settings.edit', $item->id) }}"><i
                                                        class="fa fa-pencil-square fa-2x"></i></a></td>
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


