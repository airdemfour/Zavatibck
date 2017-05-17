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
		$tmp = $this->app->tmp();
		$users = new User;
		$admin = new Admin;
		$tmp["page_title"] = "Administration";
		$tmp["page_subtitle"] = "Dashboard";
		$tmp["roles"] = $users->get_roles();
		$tmp["users"] = $users->get_users();
		$tmp["unit_types"] = $admin->get_unit_types();
		self::render("index", $tmp);
	}

	function new_role(){
		if(isset($_POST["name"])) {
			$user = new User;
			$user->create_new_role();
			$this->redirect("admin", "index");
		}
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "User Admin";
		$tmp["page_subtitle"] = "New Role";
		self::display("new_role", $tmp);
	}

	function new_user(){
		$user = new User;
		if(isset($_POST["first_name"])) {		
			$user->create_new_user();
			$this->redirect("admin", "index");
		}
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "User Admin";
		$tmp["page_subtitle"] = "Enter New User";
		$tmp["roles_list"] = $user->get_roles_list();
		self::display("new_user", $tmp);
	}

	function new_unit_type(){
		if(isset($_POST["name"])) {
			$admin = new Admin;
			$admin->create_new_unit_type();
			$this->redirect("admin", "index");
		}
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "General Admin";
		$tmp["page_subtitle"] = "New Unit Type";
		self::display("new_unit_type", $tmp);
	}

	function user_edit(){
		
		$tmp = $this->app->tmp();
		$id = $this->uri->id;
		$users = new User;
		$user = $users->get_user($id);
		$tmp["role"] = $user->role;
		$tmp["page_title"] = "Administration";
		$tmp["page_subtitle"] = "Dashboard";
		$tmp["roles"] = $users->get_roles();
		self::render("user_edit", $tmp);
	}

	

/*function upload() {
	$taxpayer = new Taxpayer;
	$taxpayer->upload();
}*/


}
