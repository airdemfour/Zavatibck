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
		$project = new Project;
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "Projects";
		$tmp["page_subtitle"] = "Index";
		$tmp["projects"] = $project->get_projects();
		self::render("index", $tmp);
	}


	function view() {
		$projects = new Project;
		if(isset($_POST["projectid_blocks"])) {
			$projects->create_new_block();
			$this->redirect("projects", "view", $_POST["projectid_blocks"]);
		}	
		$id = $this->uri->id;
		$tmp = $this->app->tmp();
		$project = $projects->get_project($id);
		$tmp["page_title"] = "Project";
		$tmp["page_subtitle"] = "Details";
		$tmp["project_name"] = $project->name;
		$tmp["description"] = $project->description;
		$tmp["location"] = $project->site_loc;
		$tmp["projectid"] = $project->id;
		$tmp["projectblocks"] = $projects->get_blocks($id);
		$tmp["projectunits"] = $projects->get_project_units($id);
		self::render("view", $tmp);
	}


	function new_unit(){
		$projects = new Project;
		$blockid = $this->uri->id;
		if(isset($_POST["unit_description"])) {		
			$projects->create_new_unit();
			$this->redirect("projects", "view", $_POST["projectid"]);
		}
		$projectid = $projects->get_pid_from_bid($blockid);
		$tmp = $this->app->tmp();
		$tmp["unittypes"] = $projects->get_unit_types();
		$tmp["page_title"] = "New Unit";
		$tmp["page_subtitle"] = "Enter New User";
		$tmp["blockid"] = $blockid;
		$tmp["projectid"] = $projectid;
		self::display("new_user", $tmp);
	}

	function edit_block(){
		$projects = new Project;
		$blockid = $this->uri->id;
		if(isset($_POST["unit_description"])) {		
			$projects->create_new_unit();
			$this->redirect("projects", "view", $_POST["projectid"]);
		}
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "Edit Block";
		$tmp["page_subtitle"] = "Enter Existing Block";
		self::display("edit_block", $tmp);
	}


	

/*function upload() {
	$taxpayer = new Taxpayer;
	$taxpayer->upload();
}*/


}
