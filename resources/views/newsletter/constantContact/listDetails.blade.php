@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">{{$list->name}}</h1>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ URL::to('newsletter/lists') }}" class="btn btn-primary"><i class="fa fa-list"></i> All
                        lists</a>
                </div>

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
                            <table class="table tablesorter no-margin table-striped table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Status</th>
                                    <th>Email</th>
                                    <th>Created</th>
                                    <th>Modified</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>

                                {{--var_dump($contacts->results)--}}

                                @foreach($contacts->results as $contact)
                                {{--{{dump($contact)}}--}}
                                    <tr>
                                        <td>{{$contact->id}}</td>
                                        <td {!!  ($contact->status == 'ACTIVE')? 'class="success"' : '' !!}>{{$contact->status}}</td>
                                        <td>

                                            @foreach($contact->email_addresses as $email)
                                                <p>{{$email->email_address}}</p>
                                            @endforeach

                                        </td>
                                        <td>{{Carbon::parse($contact->created_date)->format('m-d-Y')}}</td>
                                        <td>{{Carbon::parse($contact->modified_date)->format('m-d-Y')}}</td>
                                        <td>
                                            <a href="{{ route('newsletter.edit', $contact->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
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