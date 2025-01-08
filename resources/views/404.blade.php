@extends("app")

@section("content")
  <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
          <div class="error-page">
            <h2 class="headline text-yellow"> 404</h2>
            <div class="error-content">
			  <div style="padding-top:30px;" ></div>
              <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
              <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="/">return to the previous page</a>.
              </p>
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
        </section><!-- /.content -->     
    </div><!-- ./wrapper -->
  </div>
@stop
