@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Sponsors</h1>
        <div class="pull-right">
            <a href="{{ route('sponsors.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create
                New</a>
        </div>
    </section>

    <hr class="clearfix"/>
    <section class="content">
        <table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>Sponsor Name</th>
                <th>Sponsor URL</th>
                <th># of Ads</th>
                <th>Logo</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sponsors as $sponsor)
                <tr>
                    <td>
                        {{$sponsor->name}}
                    </td>
                    <td><a href="{{$sponsor->url}}" title="{{$sponsor->name}}" target="_blank">{{{ strip_tags
                    ($sponsor->url)
                    }}}</a></td>
                    <td class="text-center"><h3>{{$sponsor->totalAds}}</h3></td>
                    <td class='text-center' style="background-color:{{$sponsor->color}}" >
                        @if(!is_null($sponsor->thumbnail))
                            <a href="{{ route('sponsors.edit', $sponsor->id) }}"><img class='img img-thumbnail'
                                                     src="{{ config('filesystems.disks.rackspace.public_url_ssl') }}{{ $sponsor->thumbnail->url }}"
                                /> </a> @endif</td>

                    <td>
                        <a href="{{ route('sponsors.edit', $sponsor->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                        <a href="{{ URL::to('/sponsors/remove/'.$sponsor->id) }}"
                           onClick="return confirm('Are you sure you want to remove this Source?');"><i class="fa fa-times fa-2x text-danger"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


