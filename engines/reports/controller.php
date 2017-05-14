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

	function payments(){
		$user = new User;
		if(isset($_POST["firstname"])) {
			$user->create_user();
		}
		//$taxpayers = $taxpayer->get_taxpayers();
		$tmp = $this->app->tmp();
		$tmp["page_title"] = "Reports";
		$tmp["page_subtitle"] = "payments";
		self::render("payments", $tmp);
	}

	function payments_table(){
		//echo "Da";
		// DB table to use
		$table = 'payments';
		$primaryKey = 'id';
		$columns = array(
			array( 'db' => 'PaymentReference',  'dt' => 0),
			array( 'db' => 'CustomerName',   'dt' => 1 ),
			array( 'db' => 'Amount',     'dt' => 2 ),
			array( 'db' => 'BankName',     'dt' => 3 ),
			array( 'db' => 'PaymentDate',     'dt' => 4 ),
			array( 'db' => 'id',     'dt' => 5 ),
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

	function view_payment() {
		$id = $this->uri->id;
		$report = new Report;
		$payment = $report->get_payment($id);
		$tmp = $this->app->tmp();
		if (is_object($payment)) {
			$tmp["PaymentLogId"] = $payment->PaymentLogId;
			$tmp["CustReference"] = $payment->CustReference;
			$tmp["Amount"] = $payment->Amount;
			$tmp["PaymentReference"] = $payment->PaymentReference;
			$tmp["PaymentDate"] = $payment->PaymentDate;
			$tmp["SettlementDate"] = $payment->SettlementDate;
			$tmp["CustomerName"] = $payment->CustomerName;
			$tmp["ItemName"] = $payment->ItemName;
			$tmp["ItemCode"] = $payment->ItemCode;
			$tmp["BankName"] = $payment->BankName;
			$renderview = "view";
			$tmp["page_title"] = "TIN";
			$tmp["page_subtitle"] = "Certificate";
			self::render($renderview, $tmp);
		} else {
			echo "Payment Not Found";
		}
		
	}


function delete() {
	$id = $this->uri->id;
	$user = new User;
	$user->delete_user($id);
	$this->redirect("user", "index");
}


}
