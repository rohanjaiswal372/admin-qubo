@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Recipes</h1>
        <div class="pull-right">
            <div class="btn-group">
                <a href="{{ route('recipes.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i>
                    Create New</a>
                <button class="btn btn-danger delete-selected"><i class="fa fa-times"></i> Delete Multiple</button>
            </div>
        </div>
    </section>

    <hr class="clearfix"/>
    <section class="content">
        <table class="tablesorter table no-margin">
            <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Category</th>
                <th>Yummly</th>
                <th>Featured</th>
                <th>Active</th>
                <th>Created At</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{!! Form::checkbox($item->id, $item->id, false, array('class' => '')); !!}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->mealtypes->title }}</td>
                    <td>{{ $item->yummly_key }}</td>
                    <td>
                        @if( $item->featured )
                            Yes
                        @else
                            No
                        @endif
                    </td>
                    <td>
                        @if( $item->active )
                            Yes
                        @else
                            No
                        @endif
                    </td>
                    <td>{{$item->created_at }}</td>
                    <td>
                        <a href="{{ route('recipes.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                        <a href="{{ URL::to('recipes/remove/'.$item->id) }}"
                           onClick="return confirm('Are you sure you want to remove this Recipe?');"><i
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
                            url: "/recipes/multiple/",
                            dataType: "json",
                            data: {ids: ids, _token: '{{ csrf_token() }}'},
                            beforeSend: function (request) {
                                return request.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
                            },
                            success: function (data) {
                                //$('.content').html(data.results);
                                window.location.href = '/recipes';
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

