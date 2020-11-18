jQuery.ajaxPrefilter(function( options ) {
  if ( options.crossDomain ) {
    var scheme = options.url.split(":");
    
    if (scheme[0] != "http" && scheme[0] != "https") {
    	options.url = options.url.replace(scheme[0] + ":", location.protocol);
    }
  }
});