<?php  namespace App\Libraries\PhantomMagick;


use  \Anam\PhantomMagick\Converter as Converter;

Class PhantomMagick extends Converter {

  //const BINARY = 'C:\Program Files\PhantomJS\2.1.1\bin\phantomjs.exe';

  public function __construct(){
	  $this->setBinary("phantomjs");
  }
   
}


?>