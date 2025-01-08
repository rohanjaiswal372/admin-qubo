@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Recipes</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{$item->title}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('recipes.update', $item->id), 'method' => 'PUT', 'files' => true)) !!}
        <div class="form-group">
          {!! Form::label('title', 'Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('meal_type_id', 'Meal Type: ') !!}
          {!! Form::select('meal_type_id', $mealtypes, Input::old('placement_id')) !!}
        </div>

        <div class="form-group">
          {!! Form::label('active', 'Active: ') !!}
          {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control', 'placeholder' => '']) !!}
          <p class="help-block">Check to activate this recipe.</p>
        </div>

        <div class="form-group">
          {!! Form::label('featured', 'Feature this recipe: ') !!}
          {!! Form::checkbox('featured', Input::old('featured'), ['class' => 'form-control', 'placeholder' => '']) !!}
          <p class="help-block">Check to feature this recipe.</p>
        </div>

        <div class="form-group">
          {!! Form::label('yield', 'Yield: ') !!}
          {!! Form::text('yield', Input::old('yield'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>
        
        <div class="form-group">
            {!! Form::label('prep_time', 'Prep Time: ') !!}
            {!! Form::text('prep_time', Input::old('prep_time'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>
        
        <div class="form-group">
            {!! Form::label('total_time', 'Total Time: ') !!}
            {!! Form::text('total_time', Input::old('total_time'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <h3>Instructions</h3>
        <p>Add instructions to the recipe. To remove an instruction simply erase it and the system will remove the entry.</p>

        <div id="instructions-saver" style="display:none;">
             <div class="form-group">
              {!! Form::label('instruction[]', 'Instruction: ') !!}
              {!! Form::textarea('instruction[]', '', ['class' => 'form-control', 'placeholder' => '']) !!}
          </div>
        </div>

        @foreach( $item->instructions as $key => $instruction )
          <div class="form-group">
              {!! Form::label('instruction[]', 'Instruction: ') !!}
              {!! Form::textarea('instruction[]', $instruction, ['class' => 'form-control', 'placeholder' => '', 'id' => 'rand_'.$key]) !!}
          </div>
        @endforeach
        <div id="append-instructions"></div>

        <p><input type="button" id="add-instruction-btn" value="Add new instruction"></p>

        <h3>Ingredients</h3>
        <p>Add ingredients to the recipe. To remove an ingredients simply erase it and the system will remove the entry.</p>

        <div id="ingredients-saver" style="display:none;">
             <div class="form-group">
              {!! Form::label('ingredient[]', 'Ingredient: ') !!}
              {!! Form::text('ingredient[]', '', ['class' => 'form-control', 'placeholder' => '']) !!}
          </div>
        </div>

        @foreach( $item->ingredients as $key => $ingredient )
          <div class="form-group">
              {!! Form::label('ingredient[]', 'Ingredient: ') !!}
              {!! Form::text('ingredient[]', $ingredient, ['class' => 'form-control', 'placeholder' => '', 'id' => 'rand_2_'.$key]) !!}
          </div>
        @endforeach
        <div id="append-ingredients"></div>

        <p><input type="button" id="add-ingredients-btn" value="Add new ingredient"></p>
        
        
        <h3>Chef</h3>
        <p>If submitted by a user.</p>
        
        <div class="form-group">
            {!! Form::label('chef_credit', 'Submitted By: ') !!}
            {!! Form::text('chef_credit', Input::old('chef_credit'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('chef_email', 'Submission Email: ') !!}
            {!! Form::text('chef_email', Input::old('chef_email'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <h3>Yummly</h3>
        <p>If a Yummly submission, please enter the yummly key.</p>
        <div class="form-group">
            {!! Form::label('yummly_key', 'Submission Email: ') !!}
            {!! Form::text('yummly_key', Input::old('chef_email'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div> 


        <h2>Images</h2>

        @if (count( $item->images) > 0 )
          <table class="table no-margin">
          <thead><tr><th>Image</th><th>Action</th></tr></thead>
          <tbody>
            @foreach( $item->images as $image )
            <?php $image->toArray(); ?>
              <tr><td>
              <a href="{{ config('filesystems.disks.rackspace.public_url') }}{{ $image['url'] }}" target="_blank"><img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $image['url'] }}" style="max-width: 500px; height: auto;" /> </a>
              </td><td><a href="{{ URL::to('/image/remove/'.$image['id']) }}" onclick="return confirm('Are you sure? This will remove the image from our system.');">Remove</a>
              </tr>
            @endforeach
          </tbody>
          </table>
          <hr />
        @endif

        <div class="form-group">
          {!! Form::label('image', 'Image: ') !!}
          {!! Form::file('image') !!}
        </div>
        

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/recipes/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


