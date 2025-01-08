<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Qubo Admin</title>
    {{-- Tell the browser to be responsive to screen width --}}
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {{-- all css compiled - controlled on the gulpfile.js  --}}
    <link href="https://fonts.googleapis.com/css?family=Luckiest+Guy" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet">
    <link href="{{elixir('css/admin.css')}}" rel="stylesheet" type="text/css"/>
    {{-- Datatables --}}
    <link rel="stylesheet"
          type="text/css"
          href="https://cdn.datatables.net/u/bs/dt-1.10.12,cr-1.3.2,fc-3.2.2,fh-3.1.2,r-2.1.0,rr-1.1.2,sc-1.4.2,se-1.2.0/datatables.min.css"/>
    {{-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --}}
    {{-- WARNING: Respond.js doesn't work if you view the page via file:// --}}
    {{--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]--}}
    @yield('header')
</head>
{{--
  BODY TAG OPTIONS:
  =================
  Apply one or more of the following classes to get the
  desired effect
  |---------------------------------------------------------|
  | SKINS         | skin-blue                               |
  |               | skin-black                              |
  |               | skin-purple                             |
  |               | skin-yellow                             |
  |               | skin-red                                |
  |               | skin-green                              |
  |---------------------------------------------------------|
  |LAYOUT OPTIONS | fixed                                   |
  |               | layout-boxed                            |
  |               | layout-top-nav                          |
  |               | sidebar-collapse                        |
  |               | sidebar-mini                            |
  |---------------------------------------------------------|
  --}}
@section('body')
    <body class="qubo-admin sidebar-mini fixed">
    {{--<body class="skin-blue sidebar-mini fixed" oncontextmenu="return false">--}}
    <div class="wrapper">
        @if(Auth::check())
            {{-- Header --}}
            @include('header')
            {{-- Main Sidebar --}}
            @include('main-sidebar')
        @endif
        {{--Main Content --}}
        @yield('content')
        {{-- Footer --}}
        @include('footer')
        @if(Auth::check())
            {{-- Control Sidebar --}}
            @include('control-sidebar')
        @endif
        {{-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar --}}
        <div class="control-sidebar-bg"></div>
    </div>{{-- ./wrapper --}}
    {{-- REQUIRED JS SCRIPTS --}}
    <script src="{{elixir('js/admin.js')}}"></script>
    <script>
        jQuery.ajax({
            url: "https://ionmedia.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/g6wb3c/b/c/3d70dff4c40bd20e976d5936642e2171/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs.js?locale=en-US&collectorId=1ba17699",
            type: "get",
            cache: true,
            dataType: "script"
        });

        window.ATL_JQ_PAGE_PROPS = {
            "triggerFunction": function (showCollectorDialog) {
                jQuery("#bugReport").click(function (e) {
                    e.preventDefault();
                    showCollectorDialog();
                });
            }
        };
    </script>
    {{-- Datables package build with plugins --}}
    <script type="text/javascript"
            src="https://cdn.datatables.net/u/bs/dt-1.10.12,cr-1.3.2,fc-3.2.2,fh-3.1.2,r-2.1.0,rr-1.1.2,sc-1.4.2,se-1.2.0/datatables.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js" type="text/javascript"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    @yield('footer_js')
    {{-- Optionally, you can add Slimscroll and FastClick plugins.
          Both of these plugins are recommended to enhance the
          user experience. Slimscroll is required when using the
          fixed layout. --}}
    </body>
@show
</html>
