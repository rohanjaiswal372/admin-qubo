@extends("app")
@section('header')
    <style>
        .clickable-row{cursor:pointer;}
    </style>
    @stop

@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>
    <!-- Main content -->
    <section class="content">

        <!-- Your Page Content Here -->
        <div class="row">
            <div class="col-md-12">

                @include('partials.home.stats')

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('partials.home.new-shows')
            </div>
		</div>
        <div class="row">
            {{--@if(!is_null($stats->postPerms()))--}}
            <div class="col-md-6">
            </div>
            {{--@endif--}}
            <div class="col-md-6">
               {{--@include('partials.home.usage')--}}
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@stop
@section('footer_js')

    @stop
