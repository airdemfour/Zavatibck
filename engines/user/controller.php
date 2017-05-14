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
		$user = new User;
		if(isset($_POST["firstname"])) {
			$user->create_user();
		}
		//$taxpayers = $taxpayer->get_taxpayers();
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "Users";
		$tmp["page_subtitle"] = "view & manage";
		self::render("index", $tmp);
	}

	function servertable(){
		//echo "Da";
		// DB table to use
		$table = 'users';
		$primaryKey = 'id';
		$columns = array(
			array( 'db' => 'firstname',  'dt' => 0),
			array( 'db' => 'lastname',   'dt' => 1 ),
			array( 'db' => 'username',     'dt' => 2 ),
			array( 'db' => 'id',     'dt' => 3 ),
			);

		$sql_details = array(
			'user' => 'root',
			'pass' => 'Mkpanama1',
			'db'   => 'tax',
			'host' => 'localhost'
			);


		//require( 'ssp.class.php' );

		echo json_encode(
			\SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
			);
	}

	function edit() {
		$id = $this->uri->id;
		$taxpayer = new Taxpayer;
		if(isset($_POST["payer_type"])) {
			$taxpayer->update();
			$this->redirect("taxpayer", "index");
		}
		$tax_payer = $taxpayer->get_tax_payer($id);
		$tax_payer_type = $taxpayer->get_tax_payer_type($id);
		if ($tax_payer_type == 0) {
			$tmp = $this->app->tmp();
			$tmp["id"] = $tax_payer->id;
			$tmp["lastname"] = $tax_payer->lastname;
			$tmp["firstname"] = $tax_payer->firstname;
			$tmp["lastname"] = $tax_payer->lastname;
			$tmp["tin"] = $tax_payer->tin;
			$tmp["residential_address"] = $tax_payer->residential_address;
			$tmp["email"] = $tax_payer->email;
			$tmp["phone"] = $tax_payer->phone;
			self::display($tmp, "edit_individual");
		} else if ($tax_payer_type == 1) {
			$tmp = $this->app->tmp();
			$tmp["id"] = $tax_payer->id;
			$tmp["company_name"] = $tax_payer->company_name;
			$tmp["tin"] = $tax_payer->tin;
			$tmp["office_address"] = $tax_payer->office_address;
			$tmp["email"] = $tax_payer->email;
			$tmp["phone"] = $tax_payer->phone;
			self::display($tmp, "edit_corporate");
		}
	}


function delete() {
	$id = $this->uri->id;
	$user = new User;
	$user->delete_user($id);
	$this->redirect("user", "index");
}


}
