<?php
/**
* @package Zedek Framework
* @subpackage ZConfig zedek configuration class
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/

namespace __zf__;
class CController extends ZController{
	public $app;


	function __construct() {
		parent::__construct();
		//$this->model = new user;
		$c = new ZConfig;
		$this->app = new app;
	}

	function index(){
		if(!isset($_SESSION["userid"])) {
			$this->login();
		} else {
			$tmp = $this->app->tmp();		
			$d = new Dashboard;
			$today = date('d/m/Y');
			$tmp["page_title"] = "Dashboard";
			$tmp["page_subtitle"] = "dashboard & statistics";
			$tmp["page_controller"] = ucfirst($this->uri->controller);
			$tmp["page_method"] = ucfirst($this->uri->method);

			$tmp["today"] = $today;
			self::render($tmp,"index");
		}	
		
		
	}

	public function login(){
		if(isset($_POST["username"])) {
			$user = new User;
			$status = $user->login();
			if ($status != 0) {
				$_SESSION["userid"] = $status;
				//echo $_SESSION["userid"];
				$this->redirect("default", "index");
			} else {
				self::display("login");
			}
		} else {
			//echo md5("test");
			self::display("login");
		}

	}

	public function logout() {
		unset($_SESSION["userid"]);
		$this->login();
	}
}
