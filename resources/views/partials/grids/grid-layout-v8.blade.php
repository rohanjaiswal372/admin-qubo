<div class="row-fluid">
    <div class="row">
        <div class="col-xs-12 text-content">
            <h3>{{ (isset($grid['display_title'])) ? $grid['display_title'] : "" }}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">

            @if(isset( $grid['cells'][1]))
                @include('partials.grids.pod', ['pod' => $grid['cells'][1], 'size' => 'wide','position'=>1 ])
            @endif
        </div>
        <div class="col-xs-12 col-sm-6">
            @if(isset( $grid['cells'][2]))
                @include('partials.grids.pod', ['pod' => $grid['cells'][2], 'size' => 'wide','position'=>2 ])
            @endif
        </div>

    </div>
</div>