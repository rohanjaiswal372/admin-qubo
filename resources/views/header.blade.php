    <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="/home" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img class="img " width="30px" src="{{asset('images/logo_no_text.png')}}"/></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img class="img " width="30px" src="{{asset('images/logo.png')}}"/></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <li>
                @include('templates.partials.site_navigations')
              </li>
              @if(Auth::user()->hasPermission("activity_logs"))
                <li class="dropdown notifications-menu">
                  <a href="{{ url("activity-logs") }}" title="Activity Logs" >
                    <i class="fa fa-bell-o"></i>
                  </a>
                </li>
              @endif
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar-->
				  <!-- Not needed
                  <img src="{{ asset("/bower_components/admin-lte/dist/img/user2-160x160.jpg") }}" class="user-image" alt="User Image" />
				  -->
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs">
				  @if( Auth::check())
				  {{ Auth::user()->firstname." ".Auth::user()->lastname }}
				  @endif
				  </span>
                </a>

                <ul class="dropdown-menu">
                  <li class="user-body">
                    You are currently logged in
                  </li>
				  
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="/auth/logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
             <!--  <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li> -->
            </ul>
          </div>
        </nav>
        @include('flash::message')
      </header>
     