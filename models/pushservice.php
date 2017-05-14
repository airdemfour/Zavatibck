<?php

namespace __zf__;

class PushService extends Zedek{

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}


	private $authorized_ips = array();
	private $authorized = 0;

	/*function __construct(){
		parent::__construct();
		$authorized_ips[] = "172.16.1.1";
	}*/

	function authorizeIP($ip){
		if(empty($ip)){
			return true;
		}
		array_push($this->authorized_ips, $ip);
	}

	function authorizedIPs($ip){
		if(in_array($ip, $this->authorized_ips)){

			return true;
		} else {
			//exit;
			//return false;
			return true;
		}
	}

	function get_tax_payers(){
		$this->authorizeIp("::1");
	
		$dbo = self::orm()->pdo;
		if($this->authorizedIPs("1.1.1.1")) {		
			try{
				@$ModifiedDate = $_POST["modified_date"];
				$this->generate_csv($ModifiedDate);
			} catch(\Exception $e){
				//echo $e;
				$response_status = 1;
				$dbo->rollback();
			}
		} else {
			$this->authorized = 1;				//sthrow new \Exception("There was a problem");		
		}
	}


	

	function generate_csv($ModifiedDate){
		$dbo = self::orm()->pdo;
		$query = "select firstname, middlename, lastname, company_name, tin, office_address, residential_address,
		email, phone from tax_payer where DATE(date_modified) >= :datemodified";
		try {
			$query = $dbo->prepare($query);
			$query->bindParam(':datemodified', $ModifiedDate);
			$query->execute();
			$taxpayers = $query->fetchAll(\PDO::FETCH_ASSOC);
			$filename = "/var/www/html/tmsbck/bin/taxpayers/test.csv";
			$file = fopen($filename, "w");
			foreach ($taxpayers as $taxpayer) {
				$line = $taxpayer['firstname']."|".$taxpayer['middlename']."|".$taxpayer['lastname']."|".$taxpayer['company_name']."|".$taxpayer['tin']."|".$taxpayer['office_address']."|".$taxpayer['residential_address']."|".$taxpayer['email']."|".$taxpayer['phone'];
				fputcsv($file, $taxpayer, "|");
				//$entry = $taxpayer["firstname"]."|".$taxpayer["middlename"]."|".$taxpayer["lastname"]."|".$taxpayer["company_name"]."|".$taxpayer["tin"]."|".$taxpayer["office_address"]."|".$taxpayer["residential_address"]."|".$taxpayer["email"]."|".$taxpayer["phone"].PHP_EOL;
				//fwrite($file, $entry);Y-
			}
			fclose($file);
			header('Content-Type: application/csv'); 
			header("Content-length: " . filesize($filename)); 
			header('Content-Disposition: attachment; filename=taxpayers.csv'); 
			echo file_get_contents($filename);
			exit();
		fclose($file);
		} catch (Exception $e) {
			echo "Cannot Get CSV.".$e->getMessage();
		}
		

	}



	function get_real_ip() {
    	if (!empty($_SERVER['HTTP_CLIENT_IP']))  { //check ip from share internet
    		$ip=$_SERVER['HTTP_CLIENT_IP'];
    	}
    	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  { //to check ip is pass from proxy
    		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    	}
    	else {
    		$ip=$_SERVER['REMOTE_ADDR'];
    	}
    	return $ip;
    }





}
?>