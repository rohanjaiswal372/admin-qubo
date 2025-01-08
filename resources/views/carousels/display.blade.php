@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Carousels</h1>
      <div class="pull-right">
        <a href="{{ route('carousels.edit', $carousel['id']) }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Edit Slides</a>
      </div>
    </section>
     <hr class="clearfix" />
  
     @if (Session::has('alert_success'))
        <div class="alert alert-success">{{ Session::get('alert_success') }}</div>
    @endif


   <section class="content">
    <h2>Schedule new slides</h2>
	
	
	<div id="new-slide" style="display:none;">
		<table>
			<tbody>
			   <tr class="new-slide-row">
				<td>{!! Form::select('sort_order[]', array_combine(range(1,7),range(1,7)), Input::old('sort_order')) !!}</td>
				<td>{!! Form::select('slide_id[]', [""=>"Please select a slide"]+ $all_slides->pluck("title","id")->toArray(), Input::old('slide')) !!}</td>
				<td>{!! Form::text('starts_at[]', '', ['class' => 'form-control datepicker start-date-picker', 'placeholder' => 'Click to set the start date']) !!}</td>
				@if($carousel->id == 1)
				<td>{!! Form::text('ends_at[]', '', ['class' => 'form-control datepicker end-date-picker', 'placeholder' => 'Click to set the end date']) !!}</td>
				@endif
				<td valign="center" >
					<a class="remove-new-slide" href="javascript:void(0)"><i class="fa fa-times-circle-o fa-2x" style="font-size:25px;" ></i></a>	
				</td>
			   </tr>
		   </tbody>
		</table>
	</div>
	
    {!! Form::model($carousel, array('route' => array('carousels.add-slides', $carousel->id) , 'method' => 'PUT',"id"=>"slide-schedule-form")) !!}
      <div class="form-group" style="width: 700px">
		<a id="add-more-slides" href="javascript:void(0)" style="float:right;margin-bottom:5px;">Add more</a>
		<br clear="all"/>
        <table  id="new-slides" class="table table-striped">
          <tr>
            <th>Slot</th>
            <th>Slide</th>
            <th>Starts At</th>
           	@if($carousel->id == 1)
			<th>Ends At</th>
			@endif
            <th>&nbsp;</th>
          </tr>
           <tr class="new-slide-row">
            <td>{!! Form::select('sort_order[]', array_combine(range(1,7),range(1,7)), Input::old('sort_order')) !!}</td>
            <td>{!! Form::select('slide_id[]', ["Please select a slide"]+ $all_slides->pluck("title","id")->toArray(), Input::old('slide'), ["class"=>"slide-selector"]) !!}</td>
            <td>{!! Form::text('starts_at[]', '', ['class' => 'form-control datepicker start-date-picker', 'placeholder' => 'Click to set the start date']) !!}</td>
			@if($carousel->id == 1)			
            <td>{!! Form::text('ends_at[]', '', ['class' => 'form-control datepicker end-date-picker', 'placeholder' => 'Click to set the end date']) !!}</td>
			@endif
			<td valign="center" >
				<a class="remove-new-slide" href="javascript:void(0)"><i class="fa fa-times-circle-o fa-2x" style="font-size:25px;" ></i></a>	
			</td>
		   </tr>
        </table>
        {!! Form::button('Save', ['class' => 'btn btn-primary submit-slides-btn']) !!}
      </div>
      {!! Form::close() !!}
      <hr />
      <h2>Current Schedule</h2>

          @foreach ( $carousel_slides as $carousel_key => $carousel )
		  		  
              <h3>{{ $carousel["date"]->format('M jS, Y') }} at {{ $carousel["date"]->format('h:i a') }}</h3>
			  			  
              <table class="table table-striped table-hover">
                <thead>
                <tr>
                    @foreach ( $carousel["slides"] as $key => $slide )
                    <th>Slot {{ $key }}</th>
                    @endforeach
                </tr>
              </thead>
              <tbody>
                <tr>
                  @foreach (  $carousel["slides"] as $slide )
                    <td @if( $slide->archived ) style="border:2px solid #ff0000"@endif>
                      <p>
                        {{ $slide->title}}  @if(!$slide->dynamic)<i class="fa fa-star" style="color:#FE9A2E" ></i>@endif
                        @if( $slide->archived ) THIS SLIDE IS ARCHIVED.@endif
                      </p>
						@if($slide->image)
						<img src="{{ image($slide->image->url) }}" alt="{{ $slide->title }}" style="width: 200px;height: auto;"><br />
						@else
						NO IMAGE FOUND
						@endif
					  <p>
                       <a href="{{ URL::to('/carousel-slides/edit/'.$slide->id) }}"><em class="fa fa-pencil"></em></a> &nbsp;
                        @if(!$slide->dynamic)
						<a href="{{ URL::to('/carousel-schedule/remove/'.$slide->schedule_id) }}" class="remove-link"><em class="fa fa-times"></em></a> &nbsp;&nbsp;
						@endif
                        <!-- Schedule #{{ $slide->schedule_id}} -->
                      </p>
                    </td>

                    @endforeach
                  </tr>
                </tbody>
                </table>
            @endforeach
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop

  @section("footer_js")
  <script>
	$(document).ready(function(){
		$("#add-more-slides").click(function(){
			$('#new-slides tbody').append($('#new-slide').find("table tbody").html());
			$('.datepicker').datepicker();
		});		
		$("body").on("click",".remove-new-slide",function(){
			$(this).parents(".new-slide-row").remove();
		});
		

		$(".submit-slides-btn").click(function(){
			var dates_valid = true;
			var slides_valid = true;
			$("#slide-schedule-form .datepicker").each(function(){
				if($(this).val() == ""  ){
					dates_valid = false;
				}
			});			
			$("#slide-schedule-form .slide-selector").each(function(){
				if($(this).val() == ""  || $(this).val() == "0"){
					slides_valid = false;
				}
			});
			
			if(!dates_valid){
				 alert("Each slide needs dates");
			}			
			if(!slides_valid){
				 alert("Please select all the slides");
			}
			
			if(dates_valid && slides_valid){
				 $(this).parents("form").submit();
			}
		});

	});
  </script>
  @stop