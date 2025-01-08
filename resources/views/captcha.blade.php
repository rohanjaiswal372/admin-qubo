<!DOCTYPE html>
<html>
    <head>
        <title>CAPTCHA</title>
    </head>
    <body>
	
		<h3>ION TELEVISION - DEV</h3>
		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
	
		<form method="post" action="test-captcha" >
			{!! Form::token() !!}
            {!! app('captcha')->display(); !!}
			
			<input type="text" name="title" value="" />
			<input type="text" name="description" value="" />
		
			<input type="submit" />
		</form>
  
    </body>
</html>
