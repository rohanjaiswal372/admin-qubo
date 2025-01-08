
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
				
	<style is="custom-style">

    /**
     * Page styles.
     */
    * {
      box-sizing: inherit;
    }
    body {
      background: #eee;
      box-sizing: border-box;
      color: #222;
      font: 13px/1.3 'Open Sans', sans-serif;
      margin: 0;
      padding: 0;
    }
   

    /**
     * <google-signin> styles.
     */
    google-signin {
      margin: 0;
    }

    /**
     * <google-analytics-dashboard> styles.
     */
    google-analytics-dashboard {
      display: block;
      padding: 2em;
      transition: opacity .3s ease;
    }
    google-analytics-dashboard:not([authorized]) {
      opacity: .3;
      pointer-events: none;
    }

    /**
     * <google-analytics-chart> styles.
     */
    google-analytics-chart {
      box-shadow: 0 0 .5em rgba(0,0,0,.1);
      background: #fff;
      float: left;
      margin: 0 2em 2em 0;
      padding: 2em 2em 1em;
      transition: opacity .2s ease;
      max-width: 100%;
    }
    google-analytics-chart:first-of-type {
      clear: both;
    }
    google-analytics-chart h3 {
      font-size: 1.3em;
      font-weight: 300;
      margin: 0;
    }

    /**
     * <google-analytics-view-selector> and
     * <google-analytics-date-selector> styles.
     */
    google-analytics-view-selector,
    google-analytics-date-selector {
      box-shadow: 0 0 .5em rgba(0,0,0,.1);
      background: #fff;
      display: -webkit-flex;
      display: -ms-flexbox;
      display: flex;
      -webkit-flex-direction: column;
      -ms-flex-direction: column;
      flex-direction: column;
      margin: 0 0 2em 0;
      max-width: 600px;
      padding: 2em .5em 1em 2em;
    }

    google-analytics-date-selector {
      max-width: 500px;
    }

    google-analytics-view-selector {
      max-width: 750px;
    }

    @media (min-width: 600px) {
      google-analytics-view-selector,
      google-analytics-date-selector {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
      }
    }

    :root {
      --google-analytics-control: {
        display: block;
        -webkit-flex: 1 1 0%;
        -ms-flex: 1 1 0%;
        flex: 1 1 0%;
        margin: 0 1.5em 1em 0;
        min-width: 0;
      };
      --google-analytics-label: {
        display: block;
        font-weight: bold;
        padding: 0 0 .4em 2px;
      };
      --google-analytics-field: {
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        height: 34px;
        line-height: 20px;
        padding: 6px 12px;
        transition: border-color .2s;
        width: 100%;
      };
      --google-analytics-field-focus: {
        border-color: #4d90fe;
        outline: 0;
      };
    }

    google-analytics-view-selector {
      --google-analytics-view-selector-control:
          var(--google-analytics-control);
      --google-analytics-view-selector-label:
          var(--google-analytics-label);
      --google-analytics-view-selector-select:
          var(--google-analytics-field);
      --google-analytics-view-selector-select-focus:
          var(--google-analytics-field-focus);
    }

    google-analytics-date-selector {
      --google-analytics-date-selector-control:
          var(--google-analytics-control);
      --google-analytics-date-selector-label:
          var(--google-analytics-label);
      --google-analytics-date-selector-input:
          var(--google-analytics-field);
      --google-analytics-date-selector-input-focus:
          var(--google-analytics-field-focus);
    }
  </style>
 <header>
    <google-signin
      client-id="{{$client_id}}"
      access-token="{{$access_token->value}}">
    </google-signin>
  </header>

		  <google-analytics-dashboard>

			<google-analytics-view-selector></google-analytics-view-selector>
			<google-analytics-date-selector></google-analytics-date-selector>

			<google-analytics-chart
			  type="area"
			  metrics="ga:sessions"
			  dimensions="ga:date">
			  <h3>Site Traffic</h3>
			</google-analytics-chart>

			<google-analytics-chart
			  type="column"
			  metrics="ga:avgPageLoadTime"
			  dimensions="ga:date">
			  <h3>Average Page Load Times</h3>
			</google-analytics-chart>

			<google-analytics-chart
			  type="geo"
			  metrics="ga:users"
			  dimensions="ga:country">
			  <h3>Users by Country</h3>
			</google-analytics-chart>

			<google-analytics-chart
			  type="pie"
			  metrics="ga:pageviews"
			  dimensions="ga:browser"
			  sort="-ga:pageviews"
			  max-results="6">
			  <h3>Pageviews by Browser</h3>
			</google-analytics-chart>

		  </google-analytics-dashboard>
		
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
	{!! HTML::script("bower_components/webcomponentsjs/webcomponents-lite.js") !!}

@stop

@section('footer_js')

@stop



