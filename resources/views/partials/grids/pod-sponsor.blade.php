<?php
$image_size = '';
$classes = '';
switch($size){
  case 'wide':
    $image_size = '620x240';
  break;
  case 'box':
    $image_size = '320x240';
  break;
  case 'tall':
    $image_size = '320x600';
    $classes = '-lg';
  break;
  default:
    $image_size = '320x240';
  break;
}
?>
@if( !isset($pod['title']) )
<div class="content-box{{ $classes }} hover featured">
  <a href="#"><img src="https://placehold.it/{{ $image_size }}" alt="" class="headline" style="width: 100%; height: auto;">
    <div>
      <span><h2>HEADLINE</h2><p>tagline</p></span>
    </div>
  </a>
  <h4>Title</h4>
  <a href="{{ URL::to('/grid-cells/'.$grid->id.'/'.$pod['location']) }}" style="color: #000;">Edit Content Above</a>
</div>
@else
<div class="content-box{{ $classes }} <?php echo ( isset($pod['headline']) ) ? 'hover':''; ?> featured">
  <a href="{{ $pod['hyperlink'] }}" <?php if( $pod['hyperlink_target'] ){ echo 'target="_blank"'; } ?>>
    @if( count($pod['images']) > 0 )
      <img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $pod['images'][0]['url'] }}" class="img-responsive"/>
    @else
      <img src="https://placehold.it/{{ $image_size }}" alt="Psych" class="headline">
    @endif
    <?php if( isset($pod['headline']) ) { ?>
    <div>
      <span><h2>{{ $pod['headline'] }}</h2>
        <p>
          @if ( $pod['pull_next_air'] )
            NEXT AIR DATE
          @else
            {{ $pod['tagline'] }}
          @endif
        </p></span>
    </div>
    <?php } ?>
  </a>
  <h4>{{ $pod['title'] }}</h4>
  <a href="{{ URL::to('/grid-cells/'.$grid->id.'/'.$pod['location']) }}" style="color: #000;">Edit Content Above</a>
</div>
@endif
