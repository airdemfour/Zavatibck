<?php
/**
* @package Zedek Framework
* @subpackage User Model
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/
namespace __zf__;

class Report extends Zedek{
	public $userrole;

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}

	

	function get_total_payments() {
		$db = self::orm()->pdo;
		$q = "select sum(amount) as total from payments";			
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Total Payments".$e->getMessage();
		}
		$results =  $query->fetchObject();
		if (is_object($results)) {
			return $results->total;
		} else {
			return "0";
		}
		//return $results;                                              
	}

	function get_total_payments_monthly() {

	}

	function get_total_payments_daily(){
		$db = self::orm()->pdo;
		$today = date('Y-m-d');
		$q = "select sum(amount) as total from payments where DATE(PaymentDate) = :paymentdate";			
		try {
			$query = $db->prepare($q);
			$query->bindParam(':paymentdate', $today);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Largest Payments".$e->getMessage();
		}
		$results =  $query->fetch();
		$results = $results["total"];
		if ($results == 0) {
			return "0";
		} else {
			//echo "da";
			return $results;
		}  

	}

	function get_largest_payment() {
		$db = self::orm()->pdo;
		$q = "select MAX(amount) as maximum from payments";			
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Largest Payments".$e->getMessage();
		}
		$results =  $query->fetchObject();
		if (is_object($results)) {
			return $results->maximum;
		} else {
			return "0";
		}                                           
	}


	function get_dashboard_barchart() {
		$db = self::orm()->pdo;
		$q = "select  SUM(`Amount`) AS `Total` , DATE_FORMAT(PaymentDate, '%M') AS Month, Month(`PaymentDate`) AS `Mth` from `payments`  group by Month(`PaymentDate`)";
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Largest Payments".$e->getMessage();
		}
		$results = $query->fetchAll();
		$return = "";
		foreach($results as $res){
			$return .= "{";
			$return .= "'year':'".$res["Month"]."',";
			$return .= "'completed':'".$res["Total"]."'";
			$return .= "},";
		}
		$return = rtrim($return,",");
		return $return;
	}

	function get_payment($id) {
		$db = self::orm()->pdo;
		$q = "select PaymentLogId, CustReference, Amount, PaymentMethod, PaymentReference, ChannelName
		PaymentDate, SettlementDate, ItemName, ItemCode, CustomerName, BankName from payments where id = :id";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':id', $id);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Payment".$e->getMessage();
		}
		@$result = $query->fetchObject();
		//print_r($result);
		return $result;
	}

	

}