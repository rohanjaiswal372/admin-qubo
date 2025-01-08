
@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Google Analytics</h1>
      <div class="pull-right"></div>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
		
	<script>
		(function(w,d,s,g,js,fs){
		  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
		  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
		  js.src='https://apis.google.com/js/platform.js';
		  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
		}(window,document,'script'));
		</script>
				
		<div id="embed-api-auth-container"></div>
		
		<div id="ch1-container" style="width:100%!important;"></div>
		<div id="vs1-container" style="display:none"></div>
		
		<br clear="all"/>

		<div id="ch2-container"></div>
		<div id="vs2-container" style="display:none"></div>

		<br clear="all"/>
		
		<div id="ch3-container"></div>
		<div id="vs3-container" style="display:none"></div>
		
		<div id="ch4-container"></div>
		<div id="vs4-container" style="display:none"></div>
						
		<script>
			gapi.analytics.ready(function() {

			  /**
			   * Authorize the user immediately if the user has already granted access.
			   * If no access has been created, render an authorize button inside the
			   * element with the ID "embed-api-auth-container".
			   */
			  gapi.analytics.auth.authorize({
				container: 'embed-api-auth-container',
				clientid: '{{$client_id}}',
				serverAuth: {'access_token':'{{$access_token->value}}'}
			  });


			  
			});
		</script>		
		
		{!! HTML::script("js/google-analytics/basic-charts.js") !!}
		
		{!! HTML::script("js/google-analytics/main-and-breakdown-charts.js") !!}		
	
		
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop

@section('footer_js')

@stop



