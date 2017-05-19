<?php
/**
* @package Zedek Framework
* @subpackage ZController zedek super controller class
* @version 4.0
* @author djyninus <psilent@gmail.com> Ikakke Ikpe
* @link https://github.com/djynnius/zedek
* @link https://github.com/djynnius/zedek.git
*/

namespace __zf__;

class App extends ZModel{

	function __construct(){
		parent::__construct();
	}

	function tmp(){
		$tmp = array();
		//$notifications = new Notification("da", "da", "da");
		//$nots = $notifications->get_notifications_brief();
		//$count = $notifications->count_notifications();
		//$tmp["nots"] = $nots;
		$tmp["username"] = $_SESSION["username"];
		//$tmp["role"] = $_SESSION["role"];
		$tmp["page_controller"] = ucfirst($this->uri->controller);
		$tmp["page_method"] = ucfirst($this->uri->method);
		return $tmp;
	}

}
