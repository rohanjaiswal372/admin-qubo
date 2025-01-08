@extends("app")
@section("content")

<style>
.featuredMarker {display:block;}

.green, .green:visited,.green:link, .green:hover, .green:active {
    background-color: transparent;
    color:green;
    text-shadow: none;
}

.red, .red:visited,.red:link, .red:hover, .red:active {
    background-color: transparent;
    color:red;
    text-shadow: none;
}

label {
   clear:both;
   display:block;
}

</style>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Shows
        <small>Still work in progress, dont break me ;)</small>
      </h1>
      <ol class="breadcrumb">
        <li>
          <a href="#"><i class="fa fa-dashboard"></i> Level</a>
        </li>
        <li class="active">Shows</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{$show->name}}</h3>

<form>
<div class="form-group">
<label class="control-label" for="show_form_name">name</label>
<input id="show_form_name" name="name" value="{{ $show->name }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_short_name">short_name</label>
<input id="show_form_short_name" name="short_name" value="{{ $show->short_name }}">
</div>


<div class="form-group">
<label class="control-label" for="show_form_description">description</label>
<textarea id="show_form_description" name="description">{{ $show->description }}</textarea>
</div>

<div class="form-group">
<label class="control-label" for="show_form_broadview_handle">broadview_handle</label>
<input id="show_form_broadview_handle" name="broadview_handle" value="{{ $show->broadview_handle }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_featured">featured</label>
<input id="show_form_featured" name="featured" type="checkbox" value="{{ $show->featured }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_slug">slug</label>
<input id="show_form_slug" name="slug" value="{{ $show->slug }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_active">active</label>
<input id="show_form_active" name="active" type="checkbox" value="{{ $show->active }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_holiday">holiday</label>
<input id="show_form_holiday" name="holiday" value="{{ $show->holiday }}">
</div>


<div class="form-group">
<label class="control-label" for="show_form_holiday">Carousel Headline</label>
<input id="show_form_headline" name="headline" value="{{ $show->headline }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_scope">scope</label>
<input id="show_form_scope" name="scope" value="{{ $show->scope }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_facebook_handle">facebook_handle</label>
<input id="show_form_facebook_handle" name="facebook_handle" value="{{ $show->facebook_handle }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_twitter_handle">twitter_handle</label>
<input id="show_form_twitter_handle" name="twitter_handle" value="{{ $show->twitter_handle }}">
</div>

<div class="form-group">
<label class="control-label" for="show_form_instagram_handle">instagram_handle</label>
<input id="show_form_instagram_handle" name="instagram_handle" value="{{ $show->instagram_handle }}">
</div>
</form>



    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


