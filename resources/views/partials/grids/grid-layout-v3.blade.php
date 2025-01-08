
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
						  @include('partials.grids.pod', ['pod' => $grid['cells'][1], 'size' => 'wide','position'=>1 ])
						@endif
						</div>
						<div class="col-xs-12 col-sm-4">
							@if(isset( $grid['cells'][2]))							
							  @include('partials.grids.pod', ['pod' => $grid['cells'][2], 'size' => 'box','position'=>2])
							@endif
						 </div>
					</div>
					<br/>
					<div class="row">			
						 <div class="col-xs-12 col-sm-4">
							@if(isset( $grid['cells'][3]))											 
							  @include('partials.grids.pod', ['pod' => $grid['cells'][3], 'size' => 'box','position'=>3])
						   @endif
						</div>		
						 <div class="col-xs-12 col-sm-4">
						  @if(isset( $grid['cells'][4]))											 				
							@include('partials.grids.pod', ['pod' => $grid['cells'][4], 'size' => 'box','position'=>4])
						  @endif
						</div>
						 <div class="col-xs-12 col-sm-4">
						  @if(isset( $grid['cells'][5]))											 				
							@include('partials.grids.pod', ['pod' => $grid['cells'][5], 'size' => 'box','position'=>5])
						  @endif
						</div>				
					</div>
				</div>
			</div>
		</div>
	</div>
 


<!--

<div class="content-pad">
  <div class="live-container">
    <div class="row">
      <div class="row">
        <div class="col-xs-12 text-content">
          <h3>{{ (isset($cells['display_title'])) ? $cells['display_title'] : "" }}</h3>
        </div>
      </div>	
        <div class="col-xs-12 text-content">
             <div class="row">
				<div class="col-xs-12 col-sm-8">
				
				@if(isset( $cells['pod1']))
				  @include('partials.grids.pod', ['pod' => $cells['pod1'], 'size' => 'wide'])
			    @endif
				</div>
				
				<div class="col-xs-12 col-sm-4">
					@if(isset( $cells['pod2']))
					  @include('partials.grids.pod', ['pod' => $cells['pod2'], 'size' => 'box'])
				    @endif
				 </div>
				 
			</div>
            <div class="row">	
			
				 <div class="col-xs-12 col-sm-4">
					@if(isset( $cells['pod3']))
					  @include('partials.grids.pod', ['pod' => $cells['pod3'], 'size' => 'box'])
				   @endif
				</div>
				
				 <div class="col-xs-12 col-sm-4">
				  @if(isset( $cells['pod4']))
					@include('partials.grids.pod', ['pod' => $cells['pod4'], 'size' => 'box'])
				  @endif
				</div>
				
				 <div class="col-xs-12 col-sm-4">
				  @if(isset( $cells['pod5']))											 				
					@include('partials.grids.pod', ['pod' => $cells['pod5'], 'size' => 'box'])
				  @endif
				</div>	
				
			</div>
          </div>
        </div>
      </div>
  </div>
</div>

-->