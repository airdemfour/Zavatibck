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

	function notify() {
		$paydirect = new PayDirect;
		@$xml = file_get_contents("php://input");
		$realip = $paydirect->get_real_ip();
		if ($xml != "") {
			$request = simplexml_load_string($xml);
			if ((isset($request->Payments->Payment->PaymentLogId)) && !empty($request->Payments->Payment->PaymentLogId)) {
				$paydirect->paymentNotification($xml, $realip);
			} else {
				$paydirect->customerValidation($xml, $realip);
			}
		} else {
			//error_log("File not attached");
			echo "No XML Received";
		}
		
	}

	function request() {
		$pushservice = new PushService;
		@$xml = file_get_contents("php://input");
		if ($xml != "") {
			$request = simplexml_load_string($xml);
			if ((isset($request->ModifiedDate)) && !empty($request->ModifiedDate)) {
				$pushservice->get_tax_payers($xml);
			} else {
				echo "Invalid XML Request";
			}
		} else {
			//error_log("File not attached");
			echo "No XML Received";
		}
	}


}
