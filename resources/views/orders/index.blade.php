@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Orders</h1>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
      <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>Order #</th>
            <th>Status</th>
            <th>User</th>
            <th>Email</th>
            <th>Address</th>
            <th>Submitted On</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>   
                @if( $item->status )
                    Shipped
                @else
                    Pending
                @endif
            </td>
            <td>{{ $item->full_name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->address }} {{ $item->city }} {{ $item->state }} {{ $item->zip }}</td>
            <td>{{ $item->created_at->format('M d, Y') }}</td>
            <td><a href="{{ route('orders.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a></td>
        </tr>
      @endforeach
    </tbody>
</table>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


