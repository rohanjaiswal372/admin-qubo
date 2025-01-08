<div class="">
  <div class="content-pad">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 text-content">
          <h3>{{ (isset($grid['display_title'])) ? $grid['display_title'] : "" }}</h3>
        </div>
      </div>
      <div class="row">
          <div class="col-xs-12 text-content">
		  
            <div class="row">
				<div class="col-xs-12 col-sm-8">
				@if(isset( $grid['cells'][1]))				
				  @include('partials.grids.pod', ['pod' => $grid['cells'][1], 'size' => 'wide', 'position'=>1])
			    @endif
				</div>
				<div class="col-xs-12 col-sm-4">
					@if(isset( $grid['cells'][2]))							
					  @include('partials.grids.pod', ['pod' => $grid['cells'][2], 'size' => 'box','position'=>2])
				    @endif
				 </div>
			</div>
			<br clear="all" />
            <div class="row">			
				 <div class="col-xs-12 col-sm-4">
					@if(isset( $grid['cells'][3]))											 
					  @include('partials.grids.pod', ['pod' => $grid['cells'][3], 'size' => 'box','position'=>3])
				   @endif
				</div>		
				<div class="col-xs-12 col-sm-8">
				  @if(isset( $grid['cells'][4]))											 				
					@include('partials.grids.pod', ['pod' => $grid['cells'][4], 'size' => 'wide','position'=>4])
				  @endif
				</div>
			</div>
          </div>
        </div>
    </div>
  </div>
</div>