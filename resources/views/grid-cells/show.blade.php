@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Grid Cells For {{ $grid->title }}</h1>
      <br clear="all" />
      <p><strong>Layout:</strong> {{ $grid->layout->title }} - <strong>Placements: </strong> 
        @foreach ( $grid->placements as $key => $placement )
          <?php if($key != 0) { echo ','; } ?> {{ $placement->title }}
        @endforeach
      </p>
    </section>
 
    @include('partials.grids.'.$grid->layout->path, ['grid' => $grid, 'cells' => $cells]);
 
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


