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
		$id = $this->uri->id;
		$tmp = $this->app->tmp();
		$project = $projects->get_project($id);
		$tmp["page_title"] = "Project";
		$tmp["page_subtitle"] = "Details";
		$tmp["project_name"] = $project->name;
		$tmp["description"] = $project->description;
		$tmp["location"] = $project->site_loc;
		self::render("view", $tmp);
	}

	

/*function upload() {
	$taxpayer = new Taxpayer;
	$taxpayer->upload();
}*/


}
