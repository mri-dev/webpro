<?php
class GoogleMobilfriendlyCheckSC
{
    const SCTAG = 'google-mobilfriendly-check';

    public function __construct()
    {
        add_action( 'init', array( &$this, 'register_shortcode' ) );
    }

    public function register_shortcode() {
        add_shortcode( self::SCTAG, array( &$this, 'do_shortcode' ) );
    }

    public function do_shortcode( $attr, $content = null )
    {
    	  /* Set up the default arguments. */
        $defaults = apply_filters(
            self::SCTAG.'_defaults',
            array(
            )
        );
        /* Parse the arguments. */
        $attr = shortcode_atts( $defaults, $attr );

        $output = '<div class="'.self::SCTAG.'-holder style-'.$attr['key'].'">';

        $output .= '<div class="checker-wrapper"><input placeholder="www.example.com" type="text" id="googlecheckurl"/><button type="button" onclick="checkURLMobilFriendly();">Mehet <i class="fa fa-search"></i></button></div>';

        $referer = $_SERVER['HTTP_REFERER'];
        $curl = $_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];

        $output .= '<script>
          var checkprogress = false;
            function checkURLMobilFriendly(){
              var url = jQuery("#googlecheckurl").val();
              if(!checkprogress) {
                checkprogress = true;
                jQuery("#googlecheckurl").prop("disabled", true);
                jQuery("#googlecheckurl + button").html("Folyamatban <i class=\'fa fa-spin fa-spinner\'></i>");
                jQuery.post("'. get_ajax_url( 'googlemobilcheck' ) .'",{
                  weburl: url,
                  referer: "'.$referer.'",
                  curl: "'.$curl.'"
                }, function(r){
                  checkprogress = false;
                  jQuery("#googlecheckurl").prop("disabled", false);
                  jQuery("#googlecheckurl + button").html("Mehet <i class=\'fa fa-search\'></i>");
                  console.log(r);
                  if(r.error == 1) {
                    alert(r.msg);
                    jQuery("#googlecheckurl").focus();
                  } else if(r.insertid) {
                    window.location.href = "https://search.google.com/test/mobile-friendly?url="+r.web;
                  }
                }, "json");
              }
            }
        </script>';

        $output .= '</div>';

        /* Return the output of the tooltip. */
        return apply_filters( self::SCTAG, $output );
    }

}

new GoogleMobilfriendlyCheckSC();

?>
