@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Create New</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <h3>Create New Constant Contact List</h3>
        <small class="text-muted">This creates a new contact list using the CC api</small>

        {{ HTML::ul($errors->all()) }}

        {!! Form::open(array('route' => array('newsletter.store'), 'method' => 'POST','files' => true)) !!}



        <div class="form-group">
            {!! Form::label('name', 'Name: ') !!}
            {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'List name']) !!}
        </div>


        <div class="form-group">
            {!! Form::label('status', 'Status: ') !!}
            {!! Form::select('status', ['HIDDEN' => 'HIDDEN', 'ACTIVE' => 'ACTIVE'], Input::old('status')) !!}
        </div>



        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}
        or <a href="{{ route('newsletter.index') }}">Cancel</a>

        {!! Form::close() !!}

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


