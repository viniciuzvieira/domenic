        <?php echo wpappninja_widget('content-bottom'); ?>

<?php global $wpappninja_popup; echo $wpappninja_popup;?>
    </div>


  </div>



</div>
<?php wp_footer(); ?>
<script type='text/javascript' src='<?php echo get_template_directory_uri();?>/js/framework7.min.js?v=<?php echo WPAPPNINJA_VERSION;?>'></script>

<script>
var isAndroid = Framework7.prototype.device.android === true;
var isIos = Framework7.prototype.device.ios === true;

var $$ = Dom7;
var app = new Framework7({
                                                dialog: {
                         buttonOk: '<?php _e('Ok', 'wpappninja');?>',
                         buttonCancel: '<?php _e('Cancel', 'wpappninja');?>',
                                                },
  root: '#root',
  <?php if(get_wpappninja_option('slidetoopen', '1') == '1') { ?>
  panel: {
    swipe: 'left',
  },
  <?php } ?>
  cache: false,
  statusbar: {
  },
  clicks: {

    externalLinks: 'a[href^="http"],a[href^="/"],a[href^="?"],a[href^="tel"],a[href^="geo"],a[href^="mailto"],a[href^="sms"],a[href^="javascript"]'
  },
  navbar: {
    iosCenterTitle: false
  },
  touch: {
    disableContextMenu: false
  }
});

/** VIBREUR **/
<?php if(get_wpappninja_option('vibrator', '1') == '1') {?>
app.on('popupOpen actionsOpen dialogOpen', function (popup) {
  
       try{window.webkit.messageHandlers.wpmobile.postMessage('vibrateLight');} catch(err) {}
       try{wpmobileapp.vibrateLight();} catch(err) {}
});
<?php } ?>
/*************/

var $ptrContent = $$('.ptr-content');
$ptrContent.on('ptr:refresh', function (e) { wpappninja_load_bar();setTimeout(function(){document.location=document.location}, 300); });

jQuery('a[href$="wppwa=true"]').click(function () {
    app.progressbar.show();
});

function wpappninja_show_loader() {
    app.progressbar.show();
}

jQuery( "form" ).submit(function( event ) {
  app.progressbar.show();
});
jQuery( document ).ajaxSend(function() {
  //app.progressbar.show();
});
jQuery( document ).ajaxSend(function( event, xhr, settings ) {
  var regExp = new RegExp("//" + location.host + "($|/)");

  if( settings.url.substring(0,4) !== "http" || regExp.test(settings.url) ) {
    xhr.setRequestHeader('X-WPAPPNINJA', '1');
    xhr.setRequestHeader('X-WPMOBILEAPP-WEB', '1');
    <?php /*xhr.setRequestHeader('X-WPAPPNINJA-ID', '<?php echo $_SERVER['HTTP_X_WPAPPNINJA_ID'];?>'); */ ?>
  }
});

<?php if (get_wpappninja_option('effect', '1') == '1') { ?>
jQuery(document).ajaxComplete(function() {
  wpmobileIsLoaded();
});
<?php } ?>

<?php if (get_wpappninja_option('pdfdrive', '1') == '1') { ?>
jQuery('a[href$=".pdf"]').each(function(){jQuery(this).attr("href", "https://drive.google.com/viewerng/viewer?embedded=true&url=" + encodeURIComponent(jQuery(this).attr("href")));});
<?php } ?>

</script>

<?php

  $css = "<script>
var mainView = app.views.create('.view-main');

    //$$('.wpappninja_change_color, .wpappninja_change_color_card').on('click', wpappninja_load_bar);
    //$$('form').on('submit', wpappninja_load_bar);
    //$$('.wpappninja_change_color, .wpappninja_change_color_card').on('click', wpappninja_color_scheme);

function wpappninja_load_bar(el) {
  //clearInterval(wpmobileinterval);
  wpmobileImLoaded = false;
  //setTimeout(function(){wpmobileImLoaded = true;}, 2000);
  app.panel.close('left', true);
      app.popup.close();

    setTimeout(function() {
      app.progressbar.show();
    }, 1200);";

  if (get_wpappninja_option('effect', '1') == '1') {
    $css .= "/*if (jQuery(el).is('a[href^=\"http\"]')) {*/



    //jQuery('.posts').css('transition', 'opacity 300ms, max-height 300ms');
    //jQuery('.posts').css('width', '100%');
    jQuery('.posts,.title-speed').css('opacity', '0.2');
    //setTimeout(function(){jQuery('.posts,.title-speed').css('opacity', '0');}, 500);
    //jQuery('.posts,.title-speed').css('font-family', 'BLOKK');
    //jQuery('.posts').css('max-height', '100%');
    //jQuery('.wpmobile_preload').delay( 300 ).css('display','block');
    /*}*/";
  }
  $css .= "
}

function wpappninja_color_scheme(el) {
    wpmobileImLoaded = false;
      
    if (jQuery(el).attr('id') != undefined && jQuery(el).attr('id').match('^wpm_')) {
      document.cookie='wpmobile_last_tab=' + jQuery(el).attr('id') + ';path=/';
    }

    if (!jQuery(el).hasClass('wpappninja_change_color_card')) {
    jQuery('i.wpapp_icon_nofill:not(.wpapp_sep i.wpapp_icon_nofill)').css('display', 'block');
    jQuery('i.wpapp_icon_fill:not(.wpapp_sep i.wpapp_icon)').css('display', 'none');
    jQuery('i.wpapp_tabbar').css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    jQuery('span.wpapp_tabbar').css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    jQuery('span.wpapp_tabbar').css('text-shadow', '0 0 0 transparent');
    jQuery('li.item-content').css('background', '".get_wpappninja_option('css_98cbd51ad8789c03f7dd7d6cd3cd9e08', '#fff')."');

  }
    jQuery('.card-content').css('background', '".get_wpappninja_option('css_305cad765b7512c618c0d6174913fb94', '#fff')."');

    

    jQuery('i.wpapp_icon_nofill:not(.wpapp_sep i.wpapp_icon_nofill)', el).css('display', 'none');
    jQuery('i.wpapp_icon_fill:not(.wpapp_sep i.wpapp_icon_fill)', el).css('display', 'block');
    jQuery('i.wpapp_tabbar', el).css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    jQuery('span.wpapp_tabbar', el).css('color', '".get_wpappninja_option('css_d56e17633aad9957d84a39b9db286028')."');
    jQuery('span.wpapp_tabbar', el).css('text-shadow', '0 0 #fff');
    jQuery('li.item-content', el).css('background', '".wpappninja_adjustBrightness(get_wpappninja_option('css_98cbd51ad8789c03f7dd7d6cd3cd9e08', '#f5f5f5'), -20)."');

    jQuery('.card-content', el).css('background', '".wpappninja_adjustBrightness(get_wpappninja_option('css_305cad765b7512c618c0d6174913fb94', '#fff'),  -10)."');

}";




    $css .= "function wpmobile_getCookie(cname) {
    var name = cname + '=';
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return '';
    }

    if (jQuery('.wpappninja_make_it_colorfull').length === 0) {

      if (wpmobile_getCookie('wpmobile_last_tab') != '') {

        jQuery('#' + wpmobile_getCookie('wpmobile_last_tab')).addClass('wpappninja_make_it_colorfull');

      }

    }

    </script>";

  echo $css;

?>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.5.2/lazyload.min.js"></script>-->
<script>var _extends=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};!function(e,t){"object"===("undefined"==typeof exports?"undefined":_typeof(exports))&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.LazyLoad=t()}(this,function(){"use strict";var e=function(e){var t={elements_selector:"img",container:document,threshold:300,data_src:"src",data_srcset:"srcset",class_loading:"loading",class_loaded:"loaded",class_error:"error",callback_load:null,callback_error:null,callback_set:null,callback_enter:null};return _extends({},t,e)},t=function(e,t){return e.getAttribute("data-"+t)},n=function(e,t,n){return e.setAttribute("data-"+t,n)},r=function(e){return e.filter(function(e){return!t(e,"was-processed")})},s=function(e,t){var n,r=new e(t);try{n=new CustomEvent("LazyLoad::Initialized",{detail:{instance:r}})}catch(e){(n=document.createEvent("CustomEvent")).initCustomEvent("LazyLoad::Initialized",!1,!1,{instance:r})}window.dispatchEvent(n)},o=function(e,n){var r=n.data_srcset,s=e.parentNode;if(s&&"PICTURE"===s.tagName)for(var o,a=0;o=s.children[a];a+=1)if("SOURCE"===o.tagName){var i=t(o,r);i&&o.setAttribute("srcset",i)}},a=function(e,n){var r=n.data_src,s=n.data_srcset,a=e.tagName,i=t(e,r);if("IMG"===a){o(e,n);var c=t(e,s);return c&&e.setAttribute("srcset",c),void(i&&e.setAttribute("src",i))}"IFRAME"!==a?i&&(e.style.backgroundImage='url("'+i+'")'):i&&e.setAttribute("src",i)},i="undefined"!=typeof window,c=i&&"IntersectionObserver"in window,l=i&&"classList"in document.createElement("p"),u=function(e,t){l?e.classList.add(t):e.className+=(e.className?" ":"")+t},d=function(e,t){l?e.classList.remove(t):e.className=e.className.replace(new RegExp("(^|\\s+)"+t+"(\\s+|$)")," ").replace(/^\s+/,"").replace(/\s+$/,"")},f=function(e,t){e&&e(t)},_=function(e,t,n){e.removeEventListener("load",t),e.removeEventListener("error",n)},v=function(e,t){var n=function n(s){m(s,!0,t),_(e,n,r)},r=function r(s){m(s,!1,t),_(e,n,r)};e.addEventListener("load",n),e.addEventListener("error",r)},m=function(e,t,n){var r=e.target;d(r,n.class_loading),u(r,t?n.class_loaded:n.class_error),f(t?n.callback_load:n.callback_error,r)},b=function(e,t){f(t.callback_enter,e),["IMG","IFRAME"].indexOf(e.tagName)>-1&&(v(e,t),u(e,t.class_loading)),a(e,t),n(e,"was-processed",!0),f(t.callback_set,e)},p=function(e){return e.isIntersecting||e.intersectionRatio>0},h=function(t,n){this._settings=e(t),this._setObserver(),this.update(n)};h.prototype={_setObserver:function(){var e=this;if(c){var t=this._settings,n={root:t.container===document?null:t.container,rootMargin:t.threshold+"px"};this._observer=new IntersectionObserver(function(t){t.forEach(function(t){if(p(t)){var n=t.target;b(n,e._settings),e._observer.unobserve(n)}}),e._elements=r(e._elements)},n)}},update:function(e){var t=this,n=this._settings,s=e||n.container.querySelectorAll(n.elements_selector);this._elements=r(Array.prototype.slice.call(s)),this._observer?this._elements.forEach(function(e){t._observer.observe(e)}):(this._elements.forEach(function(e){b(e,n)}),this._elements=r(this._elements))},destroy:function(){var e=this;this._observer&&(r(this._elements).forEach(function(t){e._observer.unobserve(t)}),this._observer=null),this._elements=null,this._settings=null}};var y=window.lazyLoadOptions;return i&&y&&function(e,t){if(t.length)for(var n,r=0;n=t[r];r+=1)s(e,n);else s(e,t)}(h,y),h});new LazyLoad();</script>
</div>
</body>
</html>
