@extends("app")
@section('header')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlWrUQKKui-hNiOfhzKqd8YMrWaCtaO04&v=3.exp"></script>
    <script src="{{ asset('js/google-maps/markerclusterer.js?v=1.1') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <style>
        #map, #chart {
            max-height: 50%;
            height: 500px;
            width: 100%;
        }
        #map{
          height: 600px;
        }
        .progress-bar.animate {
            width: 100%;
            -webkit-transition: width 4s; /* Safari */
            transition: width 4s;

        }
    </style>
@stop
@section("content")
    {{-- Content Wrapper. Contains page content --}}
    <div class="content-wrapper">
        {{-- Content Header (Page header) --}}
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-globe"></i> Channel Finder Analytics</h1>
            <div class="pull-right">

                <div class="input-group" style="width:150px;">
                    <span class="input-group-addon">{!! Form::label('map_years','Year:') !!}</span>
                    {!!  Form::select('map_years',array_combine($map_years,$map_years),$this_year,["id"=>"map_year_selector","class"=>"form-control"]) !!}
                </div>

            </div>
        </section>
        <hr class="clearfix"/>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bg-default">

                        <div id="map"></div>
                        <script>
                            var data = {!! json_encode($data) !!};
                            var stations_data = {!! json_encode($stations_data) !!};
                        </script>

                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body" style="">
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <div id="chart">

                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">


                        <table class="table table-striped table-hover table-bordered table-condensed">
                            <th>Type</th>
                            <th>Count</th>
                            @foreach($chart as $device)
                                <tr>
                                    <td>{{$device['name']}} </td>
                                    <td> {{$device['y']}}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
                <div class="modal js-loading-bar">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <h3 class="text-center modal-title">Loading Data, please wait..<i class="fa fa-spinner fa-spin"></i></h3>
                            <div class="modal-body">
                                <div class="progress progress-popup">
                                    <div class="progress-bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>{{-- /.content --}}
    </div>{{-- /.content-wrapper --}}

@stop
@section('footer_js')

    <script>


        function load_map() {

			var mapStyle = [{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#7f2200"},{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#87ae79"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#495421"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"visibility":"on"},{"weight":4.1}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#abce83"},{"lightness":"10"}]},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry.fill","stylers":[{"lightness":"25"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#769E72"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#7B8758"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#EBF4A4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#8dab68"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#5B5B3F"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ABCE83"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#EBF4A4"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#9BBF72"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#A4C67D"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#aee2e0"}]}];
            var map;

            function initialize() {
                var myLatlng = new google.maps.LatLng(37.09024, -95.712891);
                var mapOptions = {
                    zoom: 4,
                    center: myLatlng,
                    styles: mapStyle
                }

                map = new google.maps.Map(document.getElementById('map'), mapOptions);

                var markers = [];
                for (var i = 0; i < data.length; i++) {
                    var latLng = new google.maps.LatLng(data[i].lat, data[i].lng);
                    var marker = new google.maps.Marker({
                        position: latLng,
                        icon: 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png'
                    });
                    markers.push(marker);
                }
                var markerCluster = new MarkerClusterer(map, markers);

                //add station markers
                //----------------------------------------------------------------------------------------
                var infowindow = new google.maps.InfoWindow();
                for (var i = 0; i < stations_data.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(stations_data[i].lat, stations_data[i].lng),
                        icon: '{{  asset('/images/google_maps/station-marker.png') }}',
                        map: map
                    });

                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {

                            station_info = "<b>Station :</b> " + stations_data[i].market +
                                    "<br/><b>Call Letter :</b> " + stations_data[i].call_letter +
                                    "<br/><b>Channel :</b> " + stations_data[i].channel_number +
                                    "<br/><b>DMA Rank :</b> " + stations_data[i].dma_rank +
                                    "<br/><b>Phone :</b> " + stations_data[i].phone_number +
                                    "<br/><b>Address :</b> " + stations_data[i].address_1 + " " + stations_data[i].address_2 + ", " +
                                    stations_data[i].city + ", " + stations_data[i].state + " - " + stations_data[i].zipcode +
                                    "<br/><b>Email :</b> <a href='mailto:" + stations_data[i].email + "'>" + stations_data[i].email + "</a>";


                            infowindow.setContent("<div style='width:350px'>" + station_info + "</div>");
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
                //----------------------------------------------------------------------------------------

            }

            google.maps.event.addDomListener(window, 'load', initialize);

        }

        load_map();

        $("#map_year_selector").on('change', function () {

            var year = $(this).val();
            $('.js-loading-bar').modal({
                backdrop: 'static',
                show: false
            });
            var $modal = $('.js-loading-bar'),
                    $bar = $modal.find('.progress-bar');

            $modal.modal('show');
            $bar.addClass('animate');

            setTimeout(function() {
                $bar.removeClass('animate');
                $modal.modal('hide');
            }, 20000);
            window.location.href = "/channel-finder/" + year;
        });

        var pie_chart_data = {!! json_encode($chart) !!}

            // Make monochrome colors and set them as default for all pies
                Highcharts.getOptions().plotOptions.pie.colors = (function () {
                    var colors = [],
                            base = Highcharts.getOptions().colors[0],
                            i;
                    for (i = 0; i < pie_chart_data.length; i += 1) {
                        // Start out with a darkened base color (negative brighten), and end
                        // up with a much brighter color
                        colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
                    }
                    return colors;
                }());


        var chart1; // globally available
        $(function () {
            chart1 = new Highcharts.Chart({
                chart: {
                    renderTo: 'chart',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: true,
                    type: 'pie'
                },
                title: {
                    text: 'Percentage of Devices Used'
                },
                rangeSelector: {
                    selected: 1
                },
                plotOptions: {
                    pie: {
                        size: '80%',
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                yAxis:{
                  min: 100,
                    startOnTick: true
                },
                series: [{
                    name: "Percentage",
                    colorByPoint: true,
                    data: pie_chart_data,
                }],
                credits: false
            });
        });


    </script>


@stop