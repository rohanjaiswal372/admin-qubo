@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-newspaper-o"></i> NewsLetter Users Signups</h1>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ URL::to('newsletter/export/xls') }}" class="btn btn-primary"><i
                                class="fa fa-file-excel-o"></i> Export XLS</a>
                    <a href="{{ URL::to('newsletter/export/csv') }}" class="btn btn-primary"><i
                                class="fa fa-file-text-o"></i> Export CSV</a>
                    <button class="btn btn-danger delete-selected"><i class="fa fa-times"></i> Delete Multiple</button>
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
                                    <th>{!! Form::checkbox('toggle-checkboxes', 'toggle', FALSE, array('class' => '')); !!}</th>
                                    <th>email</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{!! Form::checkbox($user->id, $user->id, FALSE, array('class' => '')); !!}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a href="{{ URL::to('newsletter/remove/'.$user->id) }}"
                                               onClick="return confirm('Are you sure you want to remove this user?');"><i
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