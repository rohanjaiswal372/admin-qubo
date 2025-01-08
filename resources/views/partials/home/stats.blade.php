<div class="col-md-2">
    @include('partials.home.small-box',
    [
        'color'=>'bg-red',
        'number'=> $stats->shows,
         'heading' => 'Shows:',
         'icon' => 'ion ion-ios-film-outline',
         'link' =>'/shows'
     ])
</div>
@if(Auth::user()->hasPermission("admin"))
<div class="col-md-2">
    @include('partials.home.small-box',
    [
        'color'=>'bg-aqua',
        'number'=> $stats->users,
        'heading' => 'Users',
        'icon' => 'ion ion-ios-people-outline',
        'link' =>'/users'
    ])
</div>
    @endif

