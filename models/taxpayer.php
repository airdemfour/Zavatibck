<?php
/**
* @package Zedek Framework
* @subpackage User Model
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/
namespace __zf__;

class Taxpayer extends Zedek{
	public $userrole;

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}

	

	function create(){
		$db = self::orm()->pdo;
		$type = $_POST["payer_type"];
		$datetime = date("ymdHis");
		$log = new Log;
		$date_modified = date("Y-m-d H:i:s");
		$tin = "";
		$collission = 0;
		$q = "";
		try {
			if ($type == "Individual") {
				$q = "insert into tax_payer (firstname, middlename, lastname, dob, tin, type, 
					residential_address, email, phone, nature_of_business, bvn, date_modified, tax_office, modified_by) values (:firstname, :middlename, :lastname, :dob, :tin, :type,
					:residential_address, :email, :phone, :nature_of_business, :bvn, :date_modified, :tax_office, :modified_by)";
				$query = $db->prepare($q);
				$tin = $this->generate_tin(0);
				while ($this->check_tin_collission($tin)) {
					$tin = $this->generate_tin(0);
				}
				$query->bindParam(':firstname', $_POST["firstname"]);
				$query->bindParam(':middlename', $_POST["middlename"]);
				$query->bindParam(':lastname', $_POST["lastname"]);
				$query->bindParam(':dob', $_POST["dob"]);
				$query->bindParam(':tin', $tin);
				$query->bindParam(':type', $_POST["payer_type"]);
				$query->bindParam(':residential_address', $_POST["residential_address"]);
				$query->bindParam(':email', $_POST["email"]);
				$query->bindParam(':phone', $_POST["phone"]);
				$query->bindParam(':nature_of_business', $_POST["nature_of_business"]);
				$query->bindParam(':tax_office', $_POST["tax_office"]);
				$query->bindParam(':bvn', $_POST["bvn"]);
				$query->bindParam(':date_modified', $date_modified);
				$query->bindParam(':modified_by', $_SESSION['userid']);
			} else if ($type == "Individual Existing") {
				$q = "insert ignore into tax_payer (firstname, middlename, lastname, dob, tin, type, 
					residential_address, email, phone, nature_of_business, bvn, date_modified, tax_office, modified_by) values (:firstname, :middlename, :lastname, :dob, :tin, :type,
					:residential_address, :email, :phone, :nature_of_business, :bvn, :date_modified, :tax_office, :modified_by)";
				$query = $db->prepare($q);
				$tin = $_POST["tin"];
				$type = "Individual";
				$query->bindParam(':firstname', $_POST["firstname"]);
				$query->bindParam(':middlename', $_POST["middlename"]);
				$query->bindParam(':lastname', $_POST["lastname"]);
				$query->bindParam(':dob', $_POST["dob"]);
				$query->bindParam(':tin', $tin);
				$query->bindParam(':type', $type);
				$query->bindParam(':residential_address', $_POST["residential_address"]);
				$query->bindParam(':email', $_POST["email"]);
				$query->bindParam(':phone', $_POST["phone"]);
				$query->bindParam(':nature_of_business', $_POST["nature_of_business"]);
				$query->bindParam(':tax_office', $_POST["tax_office"]);
				$query->bindParam(':bvn', $_POST["bvn"]);
				$query->bindParam(':date_modified', $date_modified);
				$query->bindParam(':modified_by', $_SESSION['userid']);
			} else if ($type == "Corporate") {
				$q = "insert into tax_payer (firstname, middlename, lastname, date_of_incorporation, 
					nature_of_business, rc_no, bvn, website, company_name, tin, office_address, email, phone, type, date_modified, tax_office, modified_by)
					values (:firstname, :middlename, :lastname, :date_of_incorporation, 
					:nature_of_business, :rc_no, :bvn, :website, :company_name, :tin, :office_address, :email, :phone, :type, :date_modified, :tax_office, :modified_by)";
				$query = $db->prepare($q);
				$tin = $this->generate_tin(1);
				while ($this->check_tin_collission($tin)) {
					$tin = $this->generate_tin(1);
				}
				$query->bindParam(':firstname', $_POST["firstname"]);
				$query->bindParam(':middlename', $_POST["middlename"]);
				$query->bindParam(':lastname', $_POST["lastname"]);
				$query->bindParam(':date_of_incorporation', $_POST["date_of_incorporation"]);
				$query->bindParam(':nature_of_business', $_POST["nature_of_business"]);
				$query->bindParam(':tax_office', $_POST["tax_office"]);
				$query->bindParam(':rc_no', $_POST["rc_no"]);
				$query->bindParam(':bvn', $_POST["bvn"]);
				$query->bindParam(':website', $_POST["website"]);
				$query->bindParam(':company_name', $_POST["company_name"]);
				$query->bindParam(':tin', $tin);
				$query->bindParam(':office_address', $_POST["office_address"]);
				$query->bindParam(':email', $_POST["email"]);
				$query->bindParam(':type', $_POST["payer_type"]);
				$query->bindParam(':phone', $_POST["phone"]);
				$query->bindParam(':date_modified', $date_modified);
				$query->bindParam(':modified_by', $_SESSION['userid']);
			} else if ($type == "Corporate Existing") {
				$q = "insert ignore into tax_payer (firstname, middlename, lastname, date_of_incorporation, 
					nature_of_business, rc_no, bvn, website, company_name, tin, office_address, email, phone, type, date_modified, tax_office, modified_by)
					values (:firstname, :middlename, :lastname, :date_of_incorporation, 
					:nature_of_business, :rc_no, :bvn, :website, :company_name, :tin, :office_address, :email, :phone, :type, :date_modified, :tax_office, :modified_by)";
				$query = $db->prepare($q);
				$tin = $_POST["tin"];
				$type = "Corporate";
				$query->bindParam(':firstname', $_POST["firstname"]);
				$query->bindParam(':middlename', $_POST["middlename"]);
				$query->bindParam(':lastname', $_POST["lastname"]);
				$query->bindParam(':date_of_incorporation', $_POST["date_of_incorporation"]);
				$query->bindParam(':nature_of_business', $_POST["nature_of_business"]);
				$query->bindParam(':tax_office', $_POST["tax_office"]);
				$query->bindParam(':rc_no', $_POST["rc_no"]);
				$query->bindParam(':bvn', $_POST["bvn"]);
				$query->bindParam(':website', $_POST["website"]);
				$query->bindParam(':company_name', $_POST["company_name"]);
				$query->bindParam(':tin', $tin);
				$query->bindParam(':office_address', $_POST["office_address"]);
				$query->bindParam(':email', $_POST["email"]);
				$query->bindParam(':type', $type);
				$query->bindParam(':phone', $_POST["phone"]);
				$query->bindParam(':date_modified', $date_modified);
				$query->bindParam(':modified_by', $_SESSION['userid']);
			}
				$query->execute();
				$logcategory = "NEW TAXPAYER";
				$logentry = "New Tax Payer Created: ".$_POST["firstname"]." ".$_POST["lastname"]. " Tin: ".$tin;
				$log->create_entry($logentry, $logcategory);
		} catch (Exception $e) {
			echo "Cannot create Tax Payer".$e->getMessage();
		}			
		return $tin;

	}

	function update(){
		$db = self::orm()->pdo;
		$type = $_POST["payer_type"];
		$datetime = date("YmdHis");
		$date_modified = date("Y-m-d H:i:s");
		$log = new Log;
		$tin = "";
		$q = "";
		try {
			if ($type == "Individual") {
				$q = "update tax_payer set firstname = :firstname, middlename = :middlename, lastname = :lastname, dob = :dob, residential_address = :residential_address,
				email = :email, phone = :phone, nature_of_business = :nature_of_business, bvn = :bvn, date_modified = :date_modified, tax_office = :tax_office, modified_by = :modified_by where id = :id";
				$query = $db->prepare($q);
				$tin = $datetime."-01";
				$query->bindParam(':firstname', $_POST["firstname"]);
				$query->bindParam(':middlename', $_POST["middlename"]);
				$query->bindParam(':dob', $_POST["dob"]);
				$query->bindParam(':nature_of_business', $_POST["nature_of_business"]);
				$query->bindParam(':tax_office', $_POST["tax_office"]);
				$query->bindParam(':bvn', $_POST["bvn"]);
				$query->bindParam(':lastname', $_POST["lastname"]);
				$query->bindParam(':residential_address', $_POST["residential_address"]);
				$query->bindParam(':email', $_POST["email"]);
				$query->bindParam(':phone', $_POST["phone"]);
				$query->bindParam(':id', $_POST["id"]);
				$query->bindParam(':date_modified', $date_modified);
				$query->bindParam(':modified_by', $_SESSION['userid']);
			} else {
				$q = "update tax_payer set firstname = :firstname, middlename = :middlename, 
				lastname = :lastname, date_of_incorporation = :date_of_incorporation, 
				nature_of_business = :nature_of_business, bvn = :bvn,
				rc_no = :rc_no, website = :website, company_name = :company_name, office_address = :office_address, email = :email, phone = :phone, tax_office = :tax_office, date_modified = :date_modified,
				modified_by = :modified_by
				where id = :id";
				$query = $db->prepare($q);
				$tin = $datetime."-02";
				$query->bindParam(':firstname', $_POST["firstname"]);
				$query->bindParam(':middlename', $_POST["middlename"]);
				$query->bindParam(':lastname', $_POST["lastname"]);
				$query->bindParam(':date_of_incorporation', $_POST["date_of_incorporation"]);
				$query->bindParam(':nature_of_business', $_POST["nature_of_business"]);
				$query->bindParam(':tax_office', $_POST["tax_office"]);
				$query->bindParam(':rc_no', $_POST["rc_no"]);
				$query->bindParam(':bvn', $_POST["bvn"]);
				$query->bindParam(':website', $_POST["website"]);
				$query->bindParam(':company_name', $_POST["company_name"]);
				$query->bindParam(':office_address', $_POST["office_address"]);
				$query->bindParam(':email', $_POST["email"]);
				$query->bindParam(':phone', $_POST["phone"]);
				$query->bindParam(':id', $_POST["id"]);
				$query->bindParam(':date_modified', $date_modified);
				$query->bindParam(':modified_by', $_SESSION['userid']);
			}
				$query->execute();
				$logentry = "Tax Payer Updated: ".$_POST["firstname"]." ".$_POST["lastname"]. " Tin: ".$tin;
				$logcategory = "UPDATE TAXPAYER";
				$log->create_entry($logentry, $logcategory);
		} catch (Exception $e) {
			echo "Cannot Update Tax Payer".$e->getMessage();
		}			

	}

	function get_tax_payer($id) {
		$db = self::orm()->pdo;
		$taxpayer_type = $this->get_tax_payer_type($id);
		$q = "";
		if ($taxpayer_type == "Individual") {
			$q = "select id, firstname, middlename, lastname, dob, nature_of_business, bvn, tin, residential_address, email, phone, tax_office from tax_payer where id = :id";	
		} else {
			$q = "select id, firstname, middlename, lastname, date_of_incorporation,
			rc_no, nature_of_business, bvn, website, company_name, tin, office_address, email, phone, tax_office from tax_payer where id = :id";	
		}			
		try {
			$query = $db->prepare($q);
			$query->bindParam(":id", $id);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Tax Payer".$e->getMessage();
		}
		$results =  $query->fetchObject();
		return $results;                                              
	}

	function get_nature_of_business() {
		$db = self::orm()->pdo;
		$q = "select id, nature from nature_of_business";		
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Nature of Business".$e->getMessage();
		}
		@$results =  $query->fetchAll();
		return $results;  
	}

	function get_tax_offices() {
		$db = self::orm()->pdo;
		$q = "select id, office from tax_offices";		
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Tax Offices".$e->getMessage();
		}
		@$results =  $query->fetchAll();
		return $results;  
	}

	function get_tax_payer_type($id) {
		$db = self::orm()->pdo;
		$q = "select type from tax_payer where id = :id";		
		try {
			$query = $db->prepare($q);
			$query->bindParam(":id", $id);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get TaxPayer Type".$e->getMessage();
		}
		@$results =  $query->fetchObject()->type;
		return $results;                                              
	}

	function get_id_from_tin($tin) {
		$db = self::orm()->pdo;
		$q = "select id from tax_payer where tin = :tin";		
		try {
			$query = $db->prepare($q);
			$query->bindParam(":tin", $tin);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get TaxPayer Type".$e->getMessage();
		}
		@$results =  $query->fetchObject()->id;
		return $results;                                              
	}

	function get_total_taxpayers($type){
		$db = self::orm()->pdo;
		$q = "select count(id) as total from tax_payer where type = :type";		
		try {
			$query = $db->prepare($q);
			$query->bindParam(":type", $type);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get total taxpayers".$e->getMessage();
		}
		$results =  $query->fetchObject()->total;
		return $results;    

	}

	function check_tin_collission($tin) {
		$db = self::orm()->pdo;
		$q = "select count(id) as count from tax_payer where tin = :tin";		
		try {
			$query = $db->prepare($q);
			$query->bindParam(":tin", $tin);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot check TIN collission ".$e->getMessage();
		}
		$results =  $query->fetchObject()->count;
		if ($results == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	function generate_tin($type) {
		$digits = 7;
		$returnString = mt_rand(1, 9);
		$tin = "";
  		while (strlen($returnString) < $digits) {
    		$returnString .= mt_rand(0, 9);
  		}
  		if ($type == 0) {
  			$tin = "190".$returnString."-0001";
  		} else {
  			$tin = "190".$returnString."-0002";
  		}
  		return $tin;
	}

	function switch_payer($id) {
		$db = self::orm()->pdo;
		$taxpayer_type = $this->get_tax_payer_type($id);
		$q = "";
		if ($taxpayer_type == "Individual") {
			$q = "update tax_payer set type = 'Corporate' where id = :id";	
		} else {
			$q = "update tax_payer set type = 'Individual' where id = :id";	
		}			
		try {
			$query = $db->prepare($q);
			$query->bindParam(":id", $id);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot Switch Tax Payer".$e->getMessage();
		}                      
	}

	function upload() {
		$db = self::orm()->pdo;
		$fileData=fopen('/var/www/html/tools/d3/tin.csv','r');
		$cnt = 0;
		while($row=fgets($fileData)){  
			$csv = explode(',',$row);
			if ((isset($csv[1])) && (isset($csv[0]))) {
				try {
					$q = "CALL update_tax_payer(:tin,:payername, @resulta)";
					$query = $db->prepare($q);
					$query->bindParam(":tin", $csv[1]);
					$query->bindParam(":payername", $csv[0]);
					$query->execute();
					//$row = $query->fetch(\PDO::FETCH_ASSOC);
					//$cnt = $cnt + $row["resulta"];
					
				} catch (Exception $e) {
					echo "Cannot get total taxpayers".$e->getMessage();
				}
			}
					
			
		}
		echo $cnt;
		fclose($fileData);
	}



	
	

	
	static private function sendWelcomeSMS($email){}

	static private function sendWelcomeEmail($mobile){}


}