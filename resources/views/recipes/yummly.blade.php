@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Yummly Key Finder</h1>
      
    </section>
    
    <hr class="clearfix" />
    <section class="content">
      
    <p>Please enter a recipe to search for.</p>

    {!! Form::open(array('method' => 'POST', 'files' => true)) !!}
      <div class="form-group">
          {!! Form::label('search', 'Search Term: ') !!}
          {!! Form::text('search', Input::old('search'), ['class' => 'form-control', 'placeholder' => 'Recipe Title']) !!}
      </div>
      {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
    {!! Form::close() !!}

    <?php if( $search ){ echo '<h2>Results for: '.$search.'</h2>'; } ?>
    <br /><br />
    <p>
      <span style="background-color: green;">Highlighted means already added to the system.</span><br />
    </p>
    <table border=0 class="table tablesorter">
      <thead>
        <tr><td style="font-weight: bold;">Recipe ID</td><td style="font-weight: bold;">Name</td><td style="font-weight: bold;">Source</td><td style="font-weight: bold;">Ingredients</td></tr>
      </thead>
      <tbody>
    <?php
      if( count($result) > 0 ){
      foreach( $result->matches as $match ){
        # check if this was already added
        #$different = $wpdb->get_var("SELECT meta_id FROM {$wpdb->postmeta} WHERE meta_value = '".sanitize_text_field(wp_strip_all_tags($match->id))."'");
        if(!empty($different)) {
            #continue;
        }
        
        echo '<tr';
        if( $match->added == 'yes' ){
          echo ' style="background-color: green;"';
        }
        echo '><td>';



        echo $match->id.'</td><td>'.$match->recipeName.'</td><td>'.$match->sourceDisplayName.'</td><td>'.implode(',', $match->ingredients).'</td></tr>';
      }
    }
    ?>
  </tbody>
</table>


    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


