<?php
/**
* @package Zedek Framework
* @subpackage User Model
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/
namespace __zf__;

class Dashboard extends Zedek{
	public $userrole;

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}
	
	
	function get_dashboard_min() {
		$db = self::orm()->pdo;
		$report = new Report;
		$taxpayer = new Taxpayer;
		$total_taxpayers_individual = $taxpayer->get_total_taxpayers("Individual");
		$total_taxpayers_corporate = $taxpayer->get_total_taxpayers("Corporate");
		$total_payments = $report->get_total_payments();
		$largest_payment = $report->get_largest_payment();
		$dash["total_payments"] = $total_payments;		
		$dash["largest_payment"] = $largest_payment;
		$dash["total_taxpayers_individual"] = $total_taxpayers_individual;
		$dash["total_taxpayers_corporate"] = $total_taxpayers_corporate;
		$dash["total_payments_daily"] = $report->get_total_payments_daily();
		$dash["dashboard_barchart"] = $report->get_dashboard_barchart();
		return $dash;                                              
	}

	



	

	

}