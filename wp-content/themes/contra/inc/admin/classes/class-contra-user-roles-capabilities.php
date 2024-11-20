<?php

/*
User roles and capabilites class
Contains everything about user: roles, meta, capabilites

*/

class Contra_User_Roles_Capabilities{


  function __construct(){

    add_action('admin_init', [$this,'add_capabilities'], 20);

  }

  public function add_capabilities(){

	  return;

	  if( get_option( 'contra_user_caps_completed', false ) ) return true;

	  // PLACE HERE YOUR ACTIONS

	  add_option( 'contra_user_caps_completed', true );

  }

}


$contra_user_roles_capabilities = new Contra_User_Roles_Capabilities();