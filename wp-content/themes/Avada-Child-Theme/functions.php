<?php
define('PROTOCOL', 'https');
define('TARGETDOMAIN', 'web-pro.hu');
define('DOMAIN', $_SERVER['HTTP_HOST']);
define('IFROOT', str_replace(get_option('siteurl'), '//'.DOMAIN, get_stylesheet_directory_uri()));
define('DEVMODE', true);
define('IMG', IFROOT.'/images');
define('GOOGLE_API_KEY', '');
define('LANGKEY','hu');
define('FB_APP_ID', '');
define('DEFAULT_LANGUAGE', 'hu_HU');
define('TD', 'webpro');

// reCaptcha
define('CAPTCHA_SITE_KEY', '6LdlhDIUAAAAANUHFPBFG7GYNIwRs4h_ZivT3Amp');
define('CAPTCHA_SECRET_KEY', '6LdlhDIUAAAAAJd_Ijo8ti8gzPLmK8cKRLl79-cS');

// Includes
require_once "includes/include.php";

//$app_settings = new Setup_General_Settings();

function theme_enqueue_styles() {
    wp_enqueue_style( 'avada-parent-stylesheet', get_template_directory_uri() . '/style.css?' );
    wp_enqueue_script('captcha', 'https://www.google.com/recaptcha/api.js?lang=hu');
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function app_enqueue_styles() {
    wp_enqueue_style( 'app', IFROOT . '/assets/css/style.css?t=' . ( (DEVMODE === true) ? time() : '' ) );
}
add_action( 'wp_enqueue_scripts', 'app_enqueue_styles', 100 );

function add_opengraph_doctype( $output ) {
	return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}
add_filter('language_attributes', 'add_opengraph_doctype');

function app_locale( $locale )
{
  /*
    $lang = explode('/', $_SERVER['REQUEST_URI']);
    if(array_pop($lang) === 'en'){
      $locale = 'en_US';
    }else{
      $locale = 'gr_GR';
    }*/
    //$locale = 'en_US';

    return $locale;
}

add_filter('locale','app_locale', 10);

function facebook_og_meta_header()
{
  global $wp_query;

  $title = get_option('blogname');
  $image = '';
  $desc  = get_option('blogdescription');
  $url   = get_option('site_url');

  echo '<meta property="fb:app_id" content="'.FB_APP_ID.'"/>'."\n";
  echo '<meta property="og:title" content="' . $title . '"/>'."\n";
  echo '<meta property="og:type" content="article"/>'."\n";
  echo '<meta property="og:url" content="' . $url . '/"/>'."\n";
  echo '<meta property="og:description" content="' . $desc . '/"/>'."\n";
  echo '<meta property="og:site_name" content="'.get_option('blogname').'"/>'."\n";
  echo '<meta property="og:image" content="' . $image . '"/>'."\n";

}
add_action( 'wp_head', 'facebook_og_meta_header', 5);

function avada_lang_setup() {
	$lang = get_stylesheet_directory() . '/langs';
	load_child_theme_textdomain( 'rd', $lang );

  $ucid = ucid();

  $ucid = $_COOKIE['uid'];
}
add_action( 'after_setup_theme', 'avada_lang_setup' );

function ucid()
{
  $ucid = $_COOKIE['ucid'];

  if (!isset($ucid)) {
    $ucid = mt_rand();
    setcookie( 'ucid', $ucid, time() + 60*60*24*365*2, "/");
  }

  return $ucid;
}


function rd_init()
{
  date_default_timezone_set('Europe/Budapest');

  $ref = new PostTypeFactory( 'webproref' );
	$ref->set_textdomain( TD );
	$ref->set_icon('tag');
	$ref->set_name( 'Referencia', 'Referenciák' );
	$ref->set_labels( array(
		'add_new' => 'Új %s',
		'not_found_in_trash' => 'Nincsenek %s a lomtárban.',
		'not_found' => 'Nincsenek %s a listában.',
		'add_new_item' => 'Új %s létrehozása',
	) );
	$ref->set_metabox_cb('weproref_metaboxes');
	$ref->create();

}
add_action('init', 'rd_init');

function weproref_metaboxes()
{
  add_meta_box('webpro_ref_mb', 'Referencia beállítások', 'webpro_ref_mb', 'webproref');
}

function webpro_ref_mb()
{
  global $post;

  // Noncename needed to verify where the data originated
	echo '<input type="hidden" name="webproref_noncename" id="webproref_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

  $key = 'websiteurl';
	$val = get_post_meta($post->ID, $key, true);
  echo '<p><label for="webprohuref_'.$key.'" class="post-attributes-label">Weboldal URL</label></p>';
  echo '<input type="text" id="webprohuref_'.$key.'" name="'.$key.'" value="' . $val  . '" class="widefat" />';

  $key = 'colorid';
	$val = get_post_meta($post->ID, $key, true);
  echo '<p><label for="webprohuref_'.$key.'" class="post-attributes-label">Stílus színkód (CSS {color} kód)</label></p>';
  echo '<input type="text" id="webprohuref_'.$key.'" name="'.$key.'" value="' . $val  . '" class="widefat" />';

  $key = 'keywords';
	$val = get_post_meta($post->ID, $key, true);
  echo '<p><label for="webprohuref_'.$key.'" class="post-attributes-label">Kulcsszavak, vesszővel elválasztva</label></p>';
  echo '<textarea id="webprohuref_'.$key.'" class="widefat" name="'.$key.'">' . $val  . '</textarea>';

  $key = 'coverimg';
	$val = get_post_meta($post->ID, $key, true);
  echo '<p><label for="webprohuref_'.$key.'" class="post-attributes-label">Promó kép</label></p>';
  echo '<input type="text" id="webprohuref_'.$key.'" name="'.$key.'" value="' . $val  . '" class="widefat" />';

  $key = 'galleryid';
	$val = get_post_meta($post->ID, $key, true);
  echo '<p><label for="webprohuref_'.$key.'" class="post-attributes-label">Galéria ID</label></p>';
  echo '<input type="number" min="1" id="webprohuref_'.$key.'" name="'.$key.'" value="' . $val  . '" class="widefat" />';

  $key = 'include_customshortcodes';
	$val = get_post_meta($post->ID, $key, true);
  echo '<p><label for="webprohuref_'.$key.'" class="post-attributes-label">"EgyediMegoldasok" shortcode megjelenítési template (vesszővel elválasztva, egymás után)</label></p>';
  echo '<input type="text" id="webprohuref_'.$key.'" name="'.$key.'" value="' . $val  . '" class="widefat" />';


}

function webprohu_save_posttype_meta( $post_id, $post )
{
	if ( !wp_verify_nonce( $_POST['webproref_noncename'], plugin_basename(__FILE__) )) {
	   return $post->ID;
	}

	if ( !current_user_can( 'edit_post', $post->ID )) return $post->ID;

  $events_meta = array();

  $events_meta['websiteurl'] = $_POST['websiteurl'];
  $events_meta['colorid'] = $_POST['colorid'];
  $events_meta['keywords'] = $_POST['keywords'];
  $events_meta['coverimg'] = $_POST['coverimg'];
  $events_meta['galleryid'] = $_POST['galleryid'];
  $events_meta['include_customshortcodes'] = $_POST['include_customshortcodes'];

  foreach ((array)$events_meta as $key => $value) { // Cycle through the $events_meta array!
		if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
			update_post_meta($post->ID, $key, $value);
		} else { // If the custom field doesn't have a value
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}

add_action('save_post', 'webprohu_save_posttype_meta', 1, 2);

function rd_query_vars($aVars) {
  return $aVars;
}
add_filter('query_vars', 'rd_query_vars');

/**
* AJAX REQUESTS
*/
function ajax_requests()
{
  $ajax = new AjaxRequests();
  $ajax->contact_form();
  $ajax->googlemobilcheck();
}
add_action( 'init', 'ajax_requests' );

// AJAX URL
function get_ajax_url( $function )
{
  return admin_url('admin-ajax.php?action='.$function);
}

function after_logo_content()
{

}
add_filter('avada_logo_append', 'after_logo_content');


function jscustomcode () {
  ?>
  <script>
    (function($){
      $('*[data-egyedimegoldas-switch]').click(function(){
        var uid = $(this).data('egyedimegoldas-switch');
        $('.content-switch-holder#egyedimegoldas-switch-id'+uid).stop().slideToggle(400);
      });
    })(jQuery);

  </script>
  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
  var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
  s1.async=true;
  s1.src='https://embed.tawk.to/5a212eb1bb0c3f433d4cc5e3/default';
  s1.charset='UTF-8';
  s1.setAttribute('crossorigin','*');
  s0.parentNode.insertBefore(s1,s0);
  })();
  </script>
  <!--End of Tawk.to Script-->
  <?
}
add_action('wp_footer', 'jscustomcode');

function memory_convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
