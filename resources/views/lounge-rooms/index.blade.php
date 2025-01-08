@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Lounge Rooms</h1>
    </section>
    
    <hr class="clearfix" />
    
    <section class="content">
    <h3>Needs Approval</h3>
    <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>Room</th>
            <th>User</th>
            <th>Approved</th>
            <th>Featured</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>
              @if( isset($item->images[0]) )
              <a target="_blank" href="{{ config('filesystems.disks.rackspace.public_url') }}{{ $item->images[0]->url }}">
                <img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $item->images[0]->url }}" style="max-width: 250px; height: auto;" />
              </a>
              @else
                No Image
              @endif
            </td>
            <td>{{ $item->user_id }}</td>
            <td>
              @if ( $item->approved == 1 )
                Yes <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/0') }}">( Disapprove )</a>
              @else
                No  <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/1') }}">( Approve )</a>
              @endif
            </td>
            <td>
              @if ( $item->featured == 1 )
                Yes <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/'.$item->approved.'/0') }}">( Remove Feature )</a>
              @else
                No  <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/1/1') }}">( Feature )</a>
              @endif
            </td>
            <td>
              <a href="{{ URL::to('/lounge-rooms/destroy/'.$item->id) }}" onclick="return confirm('Are you sure you want to remove this Lounge Room?');">Delete</a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>
<hr />
  <h3>Featured Listings</h3>
  <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>Room</th>
            <th>User</th>
            <th>Approved</th>
            <th>Featured</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
    @foreach($featured as $item)
        <tr>
            <td>
              @if( isset($item->images[0]) )
              <a target="_blank" href="{{ config('filesystems.disks.rackspace.public_url') }}{{ $item->images[0]->url }}">
                <img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $item->images[0]->url }}" style="max-width: 250px; height: auto;" />
              </a>
              @else
                No Image
              @endif
            </td>
            <td>{{ $item->user_id }}</td>
            <td>
              @if ( $item->approved == 1 )
                Yes <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/0') }}">( Disapprove )</a>
              @else
                No  <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/1') }}">( Approve )</a>
              @endif
            </td>
            <td>
              @if ( $item->featured == 1 )
                Yes <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/'.$item->approved.'/0') }}">( Remove Feature )</a>
              @else
                No  <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/1/1') }}">( Feature )</a>
              @endif
            </td>
            <td>
              <a href="{{ URL::to('/lounge-rooms/destroy/'.$item->id) }}" onclick="return confirm('Are you sure you want to remove this Lounge Room?');">Delete</a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>
<hr />
<h3>Approved Listings</h3>
<table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>Room</th>
            <th>User</th>
            <th>Approved</th>
            <th>Featured</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
    @foreach($approved as $item)
        <tr>
            <td>
              @if( isset($item->images[0]) )
              <a target="_blank" href="{{ config('filesystems.disks.rackspace.public_url') }}{{ $item->images[0]->url }}">
                <img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $item->images[0]->url }}" style="max-width: 250px; height: auto;" />
              </a>
              @else
                No Image
              @endif
            </td>
            <td>{{ $item->user_id }}</td>
            <td>
              @if ( $item->approved == 1 )
                Yes <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/0') }}">( Disapprove )</a>
              @else
                No  <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/1') }}">( Approve )</a>
              @endif
            </td>
            <td>
              @if ( $item->featured == 1 )
                Yes <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/'.$item->approved.'/0') }}">( Remove Feature )</a>
              @else
                No  <a href="{{ URL::to('/lounge-rooms/update/'.$item->id.'/1/1') }}">( Feature )</a>
              @endif
            </td>
            <td>
              <a href="{{ URL::to('/lounge-rooms/destroy/'.$item->id) }}" onclick="return confirm('Are you sure you want to remove this Lounge Room?');">Delete</a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


