
gapi.analytics.ready(function() {


	  /**
	   * Create a new ViewSelector instance to be rendered inside of an
	   * element with the id "view-selector-container".
	   */
	  var vs1 = new gapi.analytics.ViewSelector({
		container: 'vs1-container'
	  });

	  // Render the view selector to the page.
	  vs1.execute();


	  /**
	   * Create a new DataChart instance with the given query parameters
	   * and Google chart options. It will be rendered inside an element
	   * with the id "chart-container".
	   */
	  var dataChart = new gapi.analytics.googleCharts.DataChart({
		query: {
		  metrics: 'ga:sessions',
		  dimensions: 'ga:date',
		  'start-date': '30daysAgo',
		  'end-date': 'yesterday'
		},
		chart: {
		  container: 'ch1-container',
		  type: 'LINE',
		  options: {
			width: '100%'
		  }
		}
	  });


	  /**
	   * Render the dataChart on the page whenever a new view is selected.
	   */
	  vs1.on('change', function(ids) {
		dataChart.set({query: {ids: ids}}).execute();
	  });
	  
	  
	  
	  
	  
		  
		  
	  /**
	   * Create a ViewSelector for the first view to be rendered inside of an
	   * element with the id "view-selector-1-container".
	   */
	  var vs2 = new gapi.analytics.ViewSelector({
		container: 'vs2-container'
	  });



	  // Render both view selectors to the page.
	  vs2.execute();

	  /**
	   * Create the first DataChart for top countries over the past 30 days.
	   * It will be rendered inside an element with the id "chart-1-container".
	   */
	  var dataChart2 = new gapi.analytics.googleCharts.DataChart({
		query: {
		  metrics: 'ga:sessions',
		  dimensions: 'ga:country',
		  'start-date': '30daysAgo',
		  'end-date': 'yesterday',
		  'max-results': 6,
		  sort: '-ga:sessions'
		},
		chart: {
		  container: 'ch2-container',
		  type: 'PIE',
		  options: {
			width: '100%',
			pieHole: 4/9
		  }
		}
	  });

	  /**
	   * Update the first dataChart when the first view selecter is changed.
	   */
	  vs2.on('change', function(ids) {
		dataChart2.set({query: {ids: ids}}).execute();
	  });


});
