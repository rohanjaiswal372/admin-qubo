@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Users</h1>
            <div class="pull-right">
                <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create
                    New</a>
            </div>
        </section>
        <hr class="clearfix"/>
        <section class="content">
            <div class="box box-default">
                <div class="box-body">
                    <table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Group</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($active_users as $user)
                            <tr>
                                <td>{{ strtolower($user->username) }}</td>
                                <td>{{ $user->firstname }}</td>
                                <td>{{ $user->lastname }}</td>
                                <td>@if($user->group)<span class="label label-warning"> {{$user->group->name}}</span> @endif</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->username) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                                    <a href="{{ url('/users/remove/'.$user->id) }}"
                                       onClick="return confirm('Are you sure you want to remove this User?');"><i class="fa fa-trash-o text-danger fa-2x"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop


