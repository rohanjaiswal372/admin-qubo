<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <section class="sidebar">

        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" id="sidebar-search" name="sidebar-search" class="form-control" placeholder="Search Menu..." />
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>
          </div>
        </form>

        <!-- /.search form -->
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
			<br clear="all" /> 

            @if(Auth::user()->hasPermission("website_settings"))
                <li><a href="/settings"><i class="fa fa-cogs"></i>  <span>Website Settings</span></a></li>
            @endif
            @if(Auth::user()->hasPermission("admin"))
                <li class="treeview">
                    <a href="#"><i class="fa fa-users"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/users"><i class="fa fa-list"></i> <span>All Users</span></a></li>
                        <li><a href="/users/create"><i class="fa fa-user-plus"></i> <span class="text-orange">New User</span></a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->hasPermission("programming"))
                <li class="treeview">
                    <a href="#"><i class="fa fa-television"></i> <span>Shows</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/shows"><i class="fa fa-list"></i> <span>All Shows</span></a></li>
                        <li><a href="/shows/create"><i class="fa fa-plus "></i> <span class="text-orange">New Show</span></a></li>
                    </ul>
                </li>
                {{--<li class="treeview">--}}
                    {{--<a href="#"><i class="fa fa-film"></i> <span>Movies</span> <i--}}
                                {{--class="fa fa-angle-left pull-right"></i></a>--}}
                    {{--<ul class="treeview-menu">--}}
                        {{--<li><a href="/movies"><i class="fa fa-list"></i> <span>All Movies</span></a></li>--}}
                        {{--<li><a href="/movies/create"><i class="fa fa-plus"></i> <span class=text-orange>New Movie</span></a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
            @endif
            @if(Auth::user()->hasPermission("admin"))
                <li class="treeview">
                    <a href="#"><i class="fa fa-list"></i> <span>Schedule</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/schedule/get/"><i class="fa fa-list text-info"></i> <span>Show <strong>All</strong> <small> Schedule</small></span></a></li>
                        <li><a href="/schedule/import/1"><i class="fa fa-plus text-success"></i> <span>Debug <small>Schedule</small> import</span></a></li>
                        <li><a href="/schedule/import/" onclick="return confirm('Are you sure you want to run the import?')"><i class="fa fa-plus text-danger "></i> <span class="text-danger">RUN <small>Schedule</small> import</span></a></li>
                    </ul>
                </li>
            @endif

            @if(Auth::user()->hasPermission("content_management"))
                <li class="treeview">
                    <a href="#"><i class="fa fa-desktop"></i> <span>Content</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="#"><i class="fa fa-file"></i> <span>Pages</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="/pages"><i class="fa fa-file"></i> <span class="text-info">All Pages</span></a></li>
                                <li><a href="/pages/create"><i class="fa fa-plus"></i> <span class="text-orange">New Page</span> </a></li>
                            </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-file"></i> <span>Posts</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="/posts"><i class="fa fa-file"></i> <span class="text-info">All Posts</span></a></li>
                                <li><a href="/posts/create"><i class="fa fa-plus"></i> <span class="text-orange">New Post</span> </a></li>
                            </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-th-large"></i> <span>Grids</span> <i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="/grids"><i class="fa fa-list"></i> <span class="text-info">All Grids</span></a>
                                    <ul>
                                        <li><a href="/grids/create"><i class="fa fa-plus"></i>  <span class="text-orange">New Grid</span></a></li>
                                        <li><a href="/grid-placements"><i class="fa fa-th-large"></i> Grid
                                                Placements</a></li>
                                        <li><a href="/grid-layouts"><i class="fa fa-th"></i> Grid Layouts</a></li>
                                    </ul>
                                </li>
                                <li><a href="/pods"><i class="fa fa-list"></i> All Pods</a>
                                    <ul>
                                        <li><a href="/pods/create"><i class="fa fa-plus"></i> <span class="text-orange">New Pod</span></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#"><i class="fa fa-file-image-o"></i> <span>Carousels</span> <i class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li><a href="/carousels"><i class="fa fa-list"></i> <span class="text-info">All Carousels</span></a></li>
                                <li><a href="/carousel-slides"><i class="fa fa-list"></i> <span class="text-info">All Slides</span></a></li>
                                <li><a href="/carousel-slides/create"><i class="fa fa-plus"></i> <span class="text-orange">New Slide</span></a></li>
                            </ul>
                        </li>

                        <li><a href="/banners"><i class="fa fa-picture-o"></i> <span>Page Banners</span></a></li>
						
                        <li><a href="/backgrounds"><i class="fa fa-picture-o"></i> <span>Page Backgrounds</span></a></li>
                        @if(Auth::user()->hasPermission("games"))
                        <li><a href="#"><i class="fa fa-gamepad"></i> <span>Games</span></a>
                            <ul class="treeview-menu">
                                <li><a href="/games"><i class="fa fa-gamepad"></i> <span>Games</span></a></li>
                                <li><a href="/tags/game"><i class="fa fa-list"></i> <span class="text-info">Game Tags</span></a></li>
                            </ul>
                        </li>
                        @endif

                        {{--<li><a href="#"><i class="fa fa-bar-chart"></i> <span>Nielsen Sources</span> <i--}}
                                        {{--class="fa fa-angle-left pull-right"></i></a>--}}
                            {{--<ul class="treeview-menu">--}}
                                {{--<li><a href="/nielsen"><i class="fa fa-list"></i> <span class="text-info">All Nielsen Sources</span></a>--}}
                                {{--</li>--}}
                                {{--<li><a href="/nielsen/create"><i class="fa fa-plus"></i> <span class="text-orange">New Nielsen Source</span></a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    </ul>
                </li>
                @endif

            @if(Auth::user()->hasPermission("ads"))
            <!--<li><a href="/member"><span>Member</span> </a> </li>-->
                <li class="treeview">
                    <a href="#"><i class="fa fa-bullhorn"></i> <span>Ad Campaigns</span>
                        {{--<small class="label bg-aqua">{{App\Stat::adsCount()}}</small> --}}
                        <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li class="treeview"><a href="/campaigns"><i class="fa fa-list text-info"></i><span>Campaigns</span><i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li class="treeview"><a href="/campaigns"><i class="text-warning fa fa-eye"></i> <span>Show <strong>All</strong> <small>Campaigns</small></span></a></li>
                                <li class="treeview"><a href="/campaigns/create"><i class="text-success fa fa-plus"></i> <span>Create <strong>New</strong> <small>Campaign</small></span></a>
                                <li class="treeview"><a href="/campaigns/weekly-updates" target="_blank" class="tippy" title="Preview the weekly campaigns email."><i class="text-success fa fa-eye"></i> <span>View <strong>Email </strong><i class="fa fa-envelope"></i></span></a>
                                @if(Auth::user()->hasPermission('admin'))
                                    <li class="treeview"><a href="/campaigns/weekly-updates/cron" target="_blank" class="tippy" title="Send out the weekly campaigns email."><i class="text-danger fa fa-paper-plane-o"></i> <span>Send <strong>Email </strong><i class="fa fa-envelope"></i></span></a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="treeview"><a href="/advertisements"><i class="fa fa-list text-info"></i><span>Advertisements</span><i
                                        class="fa fa-angle-left pull-right"></i></a>
                            <ul class="treeview-menu">
                                <li class="treeview"><a href="/advertisements"><i class="text-warning fa fa-eye"></i> <span>Show <strong>All</strong> <small>Ads</small></a></li>
                                <li class="treeview"><a href="/advertisements/create"><i class="text-success  fa fa-plus"></i> <span>Create <strong>New</strong> <small>Ad</small></a></li>
                            </ul>
                        </li>
                        <li class="treeview"><a href="/sponsors"><i class="fa fa-list text-info"></i><span>Sponsors</span><i
                                        class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <li class="treeview"><a href="/sponsors"><i class="text-warning fa fa-eye"></i> <span>Show <strong>All</strong> <small>Sponsors</small></span> </a>
                                <li class="treeview"><a href="/sponsors/create"><i class="text-success  fa fa-plus"></i> <span>Create <strong>New</strong> <small>Sponsor</small></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endif
				
				
				
				
                @if(Auth::user()->hasPermission("rescan_alerts"))
                        <!--<li><a href="/member"><span>Member</span> </a> </li>-->
                <li class="treeview">
                    <a href="#"><i class="fa fa-exclamation-triangle"></i> <span>Rescan Alerts</span>
                        <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/rescan-alerts"><i class="fa fa-list"></i> All Rescan Alerts </a></li>
						 <li> <a href="/rescan-alerts/create"><i class="fa fa-plus"></i> New Rescan Alert</a></li>
								
                        </li>
                    </ul>
                </li>
                @endif

            <li class="treeview">
                <a href="{{ url("google-analytics") }}"><i class="fa fa-line-chart"></i> <span>Google Analytics</span> </a>
            </li>

			@if(Auth::user()->hasPermission("channel_finder"))
					<li class="treeview">
						<a href="#"><i class="fa fa-globe"></i> <span>Channel Finder</span> <i
									class="fa fa-angle-left pull-right"></i></a>
						<ul class="treeview-menu">
							<li><a href="/channel-finder"><i class="fa fa-area-chart"></i><span>Analytics</span> </a></li>
						</ul>
					</li>
			@endif			

            <li class="treeview">
                <a href="#"><i class="fa fa-newspaper-o"></i> <span>Newsletters</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/newsletter"><i class="fa fa-list"></i><span>List Newsletter Signups</span> </a>
                    <li><a href="/newsletter/lists"><i class="fa fa-list-alt"></i><span>Contact Lists</span> </a></li>
                    <li><a href="/newsletter/campaigns"><i class="fa fa-envelope-square"></i><span>Email Campaigns</span> </a></li>
                </ul>
            </li>

            @if(Auth::user()->hasPermission("audience_relations"))
                <li class="treeview">
                    <a href="#"><i class="fa fa-comment"></i> <span>Audience Relations</span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="/audience-relations/feedbacks"><i class="fa fa-list"></i> <span>All User
                      Feedbacks</span></a>
                        <ul>
                        <li><a href="/audience-relations/feedbacks/create"><i class="fa fa-plus"></i> <span>Add New
                      Feeback</span></a></li></ul></li>
                    </ul>
                </li>
            @endif
            <li class="treeview">
            <a href="#" id="bugReport"><i class="fa fa-comment-o"></i> <span>Bug/Feedback</span> </a>

            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>