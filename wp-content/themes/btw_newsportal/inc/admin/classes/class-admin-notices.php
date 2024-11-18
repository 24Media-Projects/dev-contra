<?php

/*
Admin notices class
Session key: btw_admin
Currently is used only for error messages
Stores the error messages to session, prints them and clear them from session
*/

class BTW_ADMIN_NOTICES {

  /**
   * @var mixed
   */
  public $session;
  function __construct(){
    // WP action to print notices, if any
    add_action('admin_notices',array($this,'print_notices'));
  }

  public function session_init(){
    if(!session_id()) session_start();
    $_SESSION['btw_admin'] = isset($_SESSION['btw_admin']) ? $_SESSION['btw_admin'] : array('notices' => array());
    self::get_session();
  }

  private function get_session(){
    $this->session = $_SESSION['btw_admin'];
  }

  // Add message to session
  public function add($type = 'error', $msg = ''){
    if( !$msg ) return;
    
    $_SESSION['btw_admin']['notices'][] = $msg;
    $_SESSION['btw_admin']['type'] = $type;
    self::get_session();
  }

  // The message type. Currently is used only for error messages
  public function has_errors(){
    return !empty($this->session['type']) &&  $this->session['type'] == 'error' ? true : false;
  }


  // Print messages and clear them from session
  public function print_notices(){

    if(!empty($this->session['notices']) && !empty($_GET['post'])){
      if(self::has_errors()){
        $class = 'notice notice-error';
        $message = '<p>'.implode('<p></p>',$this->session['notices']).'</p>';
        $hide_success_msg = '<style>.notice-success{display:none !important;}</style>';
        printf( '<div class="notice notice-error">%1$s</div>%2$s', $message,$hide_success_msg );
      }
    }
    unset($_SESSION['btw_admin']['notices']);
    unset($_SESSION['btw_admin']['type']);
    $_SESSION['btw_admin']['notices'] = array();
    self::get_session();


  }
}

$notices = new BTW_ADMIN_NOTICES();
$notices->session_init();

?>
