<?php

class AjaxRequests
{
  public function __construct()
  {
    return $this;
  }

  public function contact_form()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'ContactFormRequest'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'ContactFormRequest'));
  }

  public function googlemobilcheck()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'GoogleMobilCheckLog'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'GoogleMobilCheckLog'));
  }

  public function GoogleMobilCheckLog()
  {
    global $wpdb;
    extract($_POST);
    $return = array(
      'error' => 0,
      'msg'   => ''
    );

    $ip = $_SERVER['REMOTE_ADDR'];
    $browser = $_SERVER['HTTP_USER_AGENT'];

    if(empty($weburl)) {
      $return['error']  = 1;
      $return['msg']    =  __('Kérjük, hogy adja meeg a weboldal elérhetőségét, amit ellenőrizni szeretne.',  'Avada');
      $this->returnJSON($return);
    }

    $wpdb->insert(
      'google_mobil_checks',
      array(
        'referer_url' => $referer,
        'current_url' => $curl,
        'ip' => $ip,
        'browser' => $browser,
        'web' => $weburl
      ),
      array(
        '%s','%s','%s','%s','%s'
      )
    );

    $return['web'] = $weburl;
    $return['insertid'] = $wpdb->insert_id;

    echo json_encode($return);
    die();
  }

 public function ContactFormRequest()
  {
    extract($_POST);
    $return = array(
      'error' => 0,
      'msg'   => '',
      'missing_elements' => [],
      'error_elements' => [],
      'missing' => 0,
      'passed_params' => false
    );

    $err_elements_text = '';

    $return['passed_params'] = $_POST;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $temakor = $_POST['temakor'];
    $targy = $_POST['targy'];
    $uzenet = $_POST['uzenet'];
    $adatk = ($_POST['adatvedelem'] == 'on') ? true : false;

    if(empty($name)) $return['missing_elements'][] = 'name';
    if(empty($email)) $return['missing_elements'][] = 'email';
    if(empty($temakor)) $return['missing_elements'][] = 'temakor';
    if(empty($targy)) $return['missing_elements'][] = 'targy';
    if(empty($uzenet)) $return['missing_elements'][] = 'uzenet';

    if(!empty($return['missing_elements'])) {
      $return['error']  = 1;
      $return['msg']    =  __('Kérjük, hogy töltse ki az összes mezőt az üzenet küldéséhez.',  'Avada');
      $return['missing']= count($return['missing_elements']);
      $this->returnJSON($return);
    }

    if(!empty($return['error_elements'])) {
      $return['error']  = 1;
      $return['msg']    =  __('A következő mezők hibásan vannak kitöltve',  'Avada').":\n". $err_elements_text;
      $return['missing']= count($return['missing_elements']);
      $this->returnJSON($return);
    }


    if( !$adatk ){
      $return['missing_elements'][] = 'adatvedelem';
      $return['error']  = 1;
      $return['msg']    =  __('Az adatkezelési tájékoztató elfogadása kötelező!',  'Avada');
      $return['missing']= 1;
      $this->returnJSON($return);
    }

    // captcha
    $captcha_code = $_POST['g-recaptcha-response'];
    $recapdata = array(
        'secret' => CAPTCHA_SECRET_KEY,
        'response' => $captcha_code
    );
    $return['recaptcha']['secret'] = CAPTCHA_SECRET_KEY;
    $return['recaptcha']['response'] = $captcha_code;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($recapdata));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $recap_result = json_decode(curl_exec($ch), true);
    curl_close($ch);
    $return['recaptcha']['result'] = $recap_result;

    if(isset($recap_result['success']) && $recap_result['success'] === false) {
      $return['error']  = 1;
      $return['msg']    =  __('Kérjük, hogy azonosítsa magát. Ha Ön nem spam robot, jelölje be a fenti jelölő négyzetben, hogy nem robot.',  'Avada');
      $this->returnJSON($return);
    }


    $to       = get_option('admin_email');
    $subject  = sprintf(__('Üzenet érkezett: %s (%s: %s)'), $name, $temakor, $targy);

    ob_start();
  	  include(locate_template('templates/mails/contactform.php'));
      $message = ob_get_contents();
		ob_end_clean();

    add_filter( 'wp_mail_from', array($this, 'getMailSender') );
    add_filter( 'wp_mail_from_name', array($this, 'getMailSenderName') );
    add_filter( 'wp_mail_content_type', array($this, 'getMailFormat') );

    $headers    = array();
    $headers[]  = 'Reply-To: '.$name.' <'.$email.'>';

    /* */
    $alert = wp_mail( $to, $subject, $message, $headers );

    $headers    = array();
    $headers[]  = 'Reply-To: '.get_option('blogname').' <no-reply@'.TARGETDOMAIN.'>';
    $alerttext = true;
    ob_start();
  	  include(locate_template('templates/mails/contactform-receiveuser.php'));
      $message = ob_get_contents();
		ob_end_clean();
    $ualert = wp_mail( $email, 'Értesítő: üzenetét megkaptuk.', $message, $headers );

    if(!$alert) {
      $return['error']  = 1;
      $return['msg']    = __('Az ajánlatkérést jelenleg nem tudtuk elküldeni. Próbálja meg később.',  'Avada');
      $this->returnJSON($return);
    }
    /* */

    echo json_encode($return);
    die();
  }

  public function test()
  {
    add_action( 'wp_ajax_'.__FUNCTION__, array( $this, 'testcls'));
    add_action( 'wp_ajax_nopriv_'.__FUNCTION__, array( $this, 'testcls'));
  }

  public function testcls()
  {

    echo json_encode($return);
    die();
  }

  public function getMailFormat(){
      return "text/html";
  }

  public function getMailSender($default)
  {
    return get_option('admin_email');
  }

  public function getMailSenderName($default)
  {
    return get_option('blogname', 'Wordpress');
  }

  private function returnJSON($array)
  {
    echo json_encode($array);
    die();
  }

}
?>
