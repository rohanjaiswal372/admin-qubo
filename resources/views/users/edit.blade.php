@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1 class="pull-left">Users</h1>
		<div class="pull-right">
			<a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
		</div>
    </section>
	  <br class="clearfix"/>
<!-- Main content -->
    <section class="content clearfix">
<!-- Nav Tabs -->
	<ul class="nav nav-tabs" role="tablist">
    	<li role="presentation" class="active"><a href="#edituser" aria-controls="edituser" role="tab" data-toggle="tab"><i class="fa fa-pencil-square-o"></i> Edit User</a></li>
    	<!-- ## NEED TO ADD PERM CONTROL- if user has admin rights to edit other users --> 
   	 	<li role="presentation"><a href="#perms" aria-controls="perms" role="tab" data-toggle="tab"><i class="fa fa-lock"></i> Permissions</a></li>
 	</ul>   
 	<div class="tab-content">   
				<div role="tabpanel" class="tab-pane fade in active" id="edituser">
					<div class="panel panel-default">
					  
						<div class="panel-body">
						  {{ HTML::ul($errors->all()) }}

						  {!! Form::open(array('route' => array('users.update',$user->id), 'method' => 'POST')) !!}
	  
						  <div class="row">
						   <div class="form-group col-lg-3">
							  {!! Form::label('type_id', 'User Type: ') !!}
								  {{ $user->type_id }}
							</div>	   
						  </div>
	  
						  <div class="local-user-container">
								<div class="form-group"><i class="fa fa-user"></i>
								  {!! Form::label('username', 'Username: ') !!}
								  {!! Form::text('username', $user->username , ['class' => 'form-control', 'placeholder' => 'Username']) !!}
								</div>	  
		  
								<div class="form-group">
								  {!! Form::label('firstname', 'First Name: ') !!}
								  {!! Form::text('firstname', $user->firstname, ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
								</div>
			
								<div class="form-group">
								  {!! Form::label('lastname', 'Last Name: ') !!}
								  {!! Form::text('lastname', $user->lastname , ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
								</div>
			
								<div class="form-group"><i class="fa fa-envelope-o"></i>
								  {!! Form::label('email', 'Email Address: ') !!}
								  {!! Form::email('email',$user->email, ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
								</div>
								@if($user->type_id == "local")
								<div class="form-group"><i class="fa fa-key"></i>
								  {!! Form::label('password', 'Password: ') !!}
								  {!! Form::password('password','', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
								</div>
								@endif
							</div>
		
							<br clear="all" />

							{!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
							or <a href="{{ route('users.index') }}">Cancel</a>

						  {!! Form::close() !!}
				  		</div><!-- /.panel-body -->
				  		</div><!-- /.panel --> 
				</div><!-- /.edituser tab -->
				
				<div role="tabpanel" class="tab-pane fade in" id="perms">
						<div class="panel panel-default">
						<div class="panel-heading"><i class="fa fa-lock"></i> <strong>Permissions for:</strong> {{$user->username}} </div>
					  		<div class="panel-body">
					  			 {!! Form::open(array('action' => array('UsersController@update_permissions', $user->username),'method' => 'POST')) !!}
					  			 			 @foreach($permission_levels as $permission_level)
					  			 				 	<div class="checkbox">
					  			 				 	 <label title="{{$permission_level->id}}" data-toggle="tooltip">
					  			 				 	   {!! Form::checkbox('permissions[]',$permission_level->id, ( $user->hasPermission($permission_level->id)) ? "1":"" ) !!}
					  			 				 	   <span class="badge">{{$permission_level->name}}</span> {{$permission_level->description}}
					  			 				 	   </label>
													</div>
					  			 			@endforeach
					  			 	<br clear="all" />

									{!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
									or <a href="{{ route('users.index') }}">Cancel</a>

								  {!! Form::close() !!}
					
							</div><!-- /.panel-body -->
				  		</div><!-- /.panel --> 
				</div><!-- /.perms tab -->
	</div><!-- /.tab-content -->
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@endsection


@section("footer_js")
 <script src="{{ asset("/js/users.js") }}" type="text/javascript"></script>
@endsection

