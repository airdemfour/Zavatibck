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
		$tmp["page_title"] = "Inventory";
		$tmp["page_subtitle"] = "Index";
		self::render("index", $tmp);
	}

	function switch_payer() {
		$id = $this->uri->id;
		$taxpayer = new Taxpayer;
		$taxpayer->switch_payer($id);
		self::redirect("taxpayer", "index");
	}

	function get_csv() {
		if(isset($_POST["checker"])) {
			$pushservice = new PushService;
			$pushservice->get_tax_payers();
		}
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "Tax Payer";
		$tmp["page_subtitle"] = "bulk download";
		$tmp["today"] = date('Y-m-d');
		self::render("get_csv", $tmp);
	}

	function view() {
		$id = $this->uri->id;
		$taxpayer = new Taxpayer;
		$tax_payer = $taxpayer->get_tax_payer($id);
		$tmp = $this->app->tmp();
		$renderview = "";
		if(is_object($tax_payer)) {
			$tax_payer_type = $taxpayer->get_tax_payer_type($id);
			if ($tax_payer_type == "Individual") {
				$tmp["id"] = $tax_payer->id;
				$tmp["lastname"] = $tax_payer->lastname;
				$tmp["firstname"] = $tax_payer->firstname;
				$tmp["middlename"] = $tax_payer->middlename;
				$tmp["dob"] = $tax_payer->dob;
				$tmp["nature_of_business"] = $tax_payer->nature_of_business;
				$tmp["bvn"] = $tax_payer->bvn;
				$tmp["lastname"] = $tax_payer->lastname;
				$tmp["tin"] = $tax_payer->tin;
				$tmp["residential_address"] = $tax_payer->residential_address; 
				$tmp["email"] = $tax_payer->email;
				$tmp["phone"] = $tax_payer->phone;
				$renderview = "view_individual";
			} else {
				$tmp = $this->app->tmp();
				$tmp["id"] = $tax_payer->id;
				$tmp["firstname"] = $tax_payer->firstname;
				$tmp["middlename"] = $tax_payer->middlename;
				$tmp["lastname"] = $tax_payer->lastname;
				$tmp["date_of_incorporation"] = $tax_payer->date_of_incorporation;
				$tmp["nature_of_business"] = $tax_payer->nature_of_business;
				$tmp["rc_no"] = $tax_payer->rc_no;
				$tmp["website"] = $tax_payer->website;
				$tmp["company_name"] = $tax_payer->company_name;
				$tmp["tin"] = $tax_payer->tin;
				$tmp["bvn"] = $tax_payer->bvn;
				$tmp["office_address"] = $tax_payer->office_address;
				$tmp["email"] = $tax_payer->email;
				$tmp["phone"] = $tax_payer->phone;
				$renderview = "view_corporate";
			}			
			$tmp["page_title"] = "TIN";
			$tmp["page_subtitle"] = "Certificate";
			self::render($renderview, $tmp);
		} else {
			echo "Invalid Tax Payer";
		}
		
	}

	function servertable(){
		// DB table to use
		$table = 'tax_payer';

		$primaryKey = 'id';


		$columns = array(
			array( 'db' => 'tin', 'dt' => 0 ),
			array( 'db' => 'firstname',  'dt' => 1),
			array( 'db' => 'lastname',   'dt' => 2 ),
			array( 'db' => 'company_name',     'dt' => 3 ),
			array( 'db' => 'type',     'dt' => 4 ),
			array( 'db' => 'date_modified',     'dt' => 5 ),
			array( 'db' => 'id',     'dt' => 6 )
			);

		$sql_details = array(
			'user' => 'root',
			'pass' => 'Mkpanama1',
			'db'   => 'tax',
			'host' => 'localhost'
			);


		//require( 'ssp.class.php' );

		// Alias Editor classes so they are easy to use



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
		$nature_of_business = $taxpayer->get_nature_of_business();
		$tax_offices = $taxpayer->get_tax_offices();
		$tax_payer_type = $taxpayer->get_tax_payer_type($id);
		if ($tax_payer_type == "Individual") {
			$tmp = $this->app->tmp();
			$tmp["id"] = $tax_payer->id;
			$tmp["lastname"] = $tax_payer->lastname;
			$tmp["firstname"] = $tax_payer->firstname;
			$tmp["middlename"] = $tax_payer->middlename;
			$tmp["dob"] = $tax_payer->dob;
			$tmp["nature_of_business"] = $tax_payer->nature_of_business;
			$tmp["natureofbusiness"] = $nature_of_business;
			$tmp["tax_office"] = $tax_payer->tax_office;
			$tmp["taxoffices"] = $tax_offices;
			$tmp["bvn"] = $tax_payer->bvn;
			$tmp["lastname"] = $tax_payer->lastname;
			$tmp["tin"] = $tax_payer->tin;
			if($tax_payer->residential_address) {
				$tmp["residential_address"] = $tax_payer->residential_address;
			} else {
				$tmp["residential_address"] = "";
			}
			$tmp["email"] = $tax_payer->email;
			$tmp["phone"] = $tax_payer->phone;
			self::display($tmp, "edit_individual");
		} else if ($tax_payer_type == "Corporate") {
			$tmp = $this->app->tmp();
			$tmp["id"] = $tax_payer->id;
			$tmp["firstname"] = $tax_payer->firstname;
			$tmp["middlename"] = $tax_payer->middlename;
			$tmp["lastname"] = $tax_payer->lastname;
			$tmp["date_of_incorporation"] = $tax_payer->date_of_incorporation;
			$tmp["nature_of_business"] = $tax_payer->nature_of_business;
			$tmp["natureofbusiness"] = $nature_of_business;
			$tmp["tax_office"] = $tax_payer->tax_office;
			$tmp["taxoffices"] = $tax_offices;
			$tmp["rc_no"] = $tax_payer->rc_no;
			$tmp["website"] = $tax_payer->website;
			$tmp["company_name"] = $tax_payer->company_name;
			$tmp["tin"] = $tax_payer->tin;
			$tmp["bvn"] = $tax_payer->bvn;
			$tmp["office_address"] = $tax_payer->office_address;
			$tmp["email"] = $tax_payer->email;
			$tmp["phone"] = $tax_payer->phone;
			self::display($tmp, "edit_corporate");
		}
	}


function delete() {
	$id = $this->uri->id;
	$mda = new Mda;
	$wanted->delete_wanted($id);
	$this->redirect("mda", "index");
}

/*function upload() {
	$taxpayer = new Taxpayer;
	$taxpayer->upload();
}*/


}
