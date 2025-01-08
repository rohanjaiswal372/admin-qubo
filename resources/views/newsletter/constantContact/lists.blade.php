@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-envelope-o"></i> Constant Contact Email Lists</h1>
            <div class="pull-right">
                <div class="btn-group">
                    {{--  <a href="{{ URL::to('newsletter/create') }}" class="btn btn-primary"><i class="fa fa-list"></i> Create New List</a> --}}
                    <a role="button" data-toggle="collapse" href="#addnewcontactlist" class="btn btn-primary"><i
                                class="fa fa-list"></i> Create New List <span class="caret"></span></a>

                </div>

            </div>
        </section>

        <div id="addnewcontactlist" class="panel panel-collapse collapse">

            <div class="panel-body">

                <small class="text-muted">This creates a new contact list using the CC api</small>

                {{ HTML::ul($errors->all()) }}

                {!! Form::open(array('route' => array('newsletter.store'), 'method' => 'POST','files' => TRUE)) !!}

                <div class="form-group">
                    {!! Form::label('name', 'Name: ') !!}
                    {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'List name']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('status', 'Status: ') !!}
                    {!! Form::select('status', ['HIDDEN' => 'HIDDEN', 'ACTIVE' => 'ACTIVE'], Input::old('status')) !!}
                </div>

                {!! Form::submit('Save Changes', ['class' => 'btn btn-success']) !!}
                or <a href="{{ route('newsletter.index') }}">Cancel</a>

                {!! Form::close() !!}
            </div>
        </div>
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th># of Contacts</th>
                                    <th>Created Date</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($lists as $list)
                                    <tr>
                                        <td>{{ $list->id }}</td>
                                        <td>{{ $list->name }}</td>
                                        <td {!!  ($list->status == 'ACTIVE')? 'class="success"' : '' !!}>{{ $list->status }}</td>
                                        <td>{{ $list->contact_count }}</td>
                                        <td>{{ Carbon::parse($list->created_date)->format('m/d/Y g:i a') }}</td>
                                        <td>
                                            <a href="{{ URL::to('newsletter/list/view/'.$list->id) }}"><i class="fa fa-list fa-2x"></i></a>
                                            <a href="{{ URL::to('newsletter/list/remove/'.$list->id) }}"
                                               onClick="return confirm('Are you sure you want to remove this list?');"><i
                                                        class="fa fa-trash text-danger fa-2x"></i></a>
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
@section('footer_js')

    <script>
        $('.delete-selected').on('click', function () {

            var ids = $("table input:checkbox:checked").map(function () {
                return $(this).val();
            }).get();

            if (confirm('Are you sure you would like to delete all these items?')) {
                $.ajax(
                        {
                            type: "POST",
                            url: "/newsletter/multiple/",
                            dataType: "json",
                            data: {ids: ids, _token: '{{ csrf_token() }}'},
                            beforeSend: function (request) {
                                return request.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
                            },
                            success: function (data) {
                                window.location.href = '/newsletter';
                            }

                        }
                );
            }
        });

        $('table tr').click(function () {

            if (event.target.type !== 'checkbox') {
                $(':checkbox', this).trigger('click');
            }
        });
        $("input[type='checkbox']").change(function (e) {
            if ($(this).is(":checked")) {
                $(this).closest('tr').addClass("danger");
            } else {
                $(this).closest('tr').removeClass("danger");
            }
        });

    </script>
@stop