@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Search</h1>
        <div class="pull-right">
            <div class="btn-group">
                <button class="btn btn-danger delete-selected"><i class="fa fa-times"></i> Delete Multiple</button>
            </div>
        </div>
    </section>

    <hr class="clearfix"/>
    <section class="content">
        <table class="tablesorter table no-margin">
            <thead>
            <tr>
                <th>{!! Form::checkbox('toggle-checkboxes', 'toggle', false, array('class' => '')); !!}</th>
                <th>Keyword</th>
                <th>Count</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            @foreach($searches as $search)
                <tr>
                    <td>{!! Form::checkbox($search->id, $search->id, false, array('class' => '')); !!}</td>
                    <td>{{ $search->keyword }}</td>
                    <td>{{ $search->count }}</td>
                    <td>
                        <a href="{{ URL::to('search/remove/'.$search->id) }}"
                           onClick="return confirm('Are you sure you want to remove this Seach Result?');"><i
                                    class="fa fa-times fa-2x"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
                            url: "/search/multiple/",
                            dataType: "json",
                            data: {ids: ids, _token: '{{ csrf_token() }}'},
                            beforeSend: function (request) {
                                return request.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
                            },
                            success: function (data) {
                                window.location.href = '/search';
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