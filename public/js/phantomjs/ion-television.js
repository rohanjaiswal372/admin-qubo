var page = require('webpage').create();
page.open('http://iondev:Positively1@dev.iontelevision.com/shows/saving-hope', function() {

  page.viewportSize  = {width: 1280, height: 1024}; 
  page.render('ion-television-preview.png');
  phantom.exit();
  
});

