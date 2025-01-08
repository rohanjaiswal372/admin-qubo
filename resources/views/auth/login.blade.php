<!DOCTYPE html>
<html>
	<head>
		{!! HTML::style("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css") !!}			
		{!! HTML::style("/css/qubo-login.css") !!}
		{!! HTML::script("/js/qubo-login.js") !!}
	</head>
<body>
		<div id="wrapper">
			<div id="header" class="stage">
			
				<div id="bird">
				  <img src="{{ url("images/login/qubo-login-logo.png") }}" />
				</div>
			
				<div style="z-index: -1; opacity: 1;" id="sky" class="stage"></div>
				<div style="opacity: 1; z-index: 0;" id="sky2" class="stage"></div>
				<div style="z-index: -1; opacity: 1;" id="sun" class="stage"></div>
				<div style="background-position: -21304px 0px; opacity: 1; z-index: 0;" id="cloud" class="stage"></div>
				<div style="background-position: -42608px 264px;" id="hill2" class="stage"></div>
				<div style="background-position: -127824px 264px;" id="hill" class="stage"></div>
			</div>

			
			<div class="login-container" style="">

			  <form class="form-signin" method="post">
				{!! csrf_field() !!}	  
				<h2 class="form-signin-heading">Please sign in</h2>
				<label for="inputUsername" class="sr-only">Username</label>
				<input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus>
				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			  </form>

			</div> <!-- /container -->
			
			
		</div>


		


    </body>
</html>