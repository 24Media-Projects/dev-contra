<?php 

class Contra_Xml_Api_Video_Controller extends BTW_Xml_Api_Post_Controller{

  protected $post_type = 'video';

  protected $post_type_plural = 'videos';


  public function __construct(){

    parent::__construct();

  }
}

 $btw_xml_api_video_controller = new Contra_Xml_Api_Video_Controller();

