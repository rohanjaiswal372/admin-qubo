@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Order Details</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing #{{ $item->id }} - {{$item->full_name}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('orders.update', $item->id), 'method' => 'PUT')) !!}
            
        <div class="form-group">
          {!! Form::label('status', 'Status: ') !!}
          {!! Form::select('status', ['0' => 'Pending', '1' => 'Shipped'], Input::old('status')) !!}
        </div>
  
        <div class="form-group">
          {!! Form::label('full_name', 'Full Name: ') !!}
          {!! Form::text('full_name', Input::old('full_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('phone', 'Phone: ') !!}
          {!! Form::text('phone', Input::old('phone'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('email', 'Email: ') !!}
          {!! Form::text('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('address', 'Address: ') !!}
          {!! Form::text('address', Input::old('address'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('city', 'City: ') !!}
          {!! Form::text('city', Input::old('city'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('state', 'State: ') !!}
          {!! Form::text('state', Input::old('state'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('zip', 'Zip: ') !!}
          {!! Form::text('zip', Input::old('zip'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <h3>Items Ordered</h3>
        <p>The user spent {{ $item->total_points }} on this order.</p>
        <table class="table table-striped table-hover">
          <tr>
            <th>Quantity</th>
            <th>Item</th>
            <th>Points</th>
          </tr>
          @foreach ( $item->prizes as $prize )
          <tr>
            <td>{{ $prize->quantity }}</td>
            <td>{{ $prize->prize->title }}</td>
            <td>{{ $prize->points }}</td>
          </tr>
          @endforeach
        </table>

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/orders/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


