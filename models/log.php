<?php
/**
* @package Zedek Framework
* @subpackage User Model
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/
namespace __zf__;

class Log extends Zedek{
	public $userrole;

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}


	function create_entry($description, $category) {
		$db = self::orm()->pdo;
		$today = date('Y-m-d H:i:s');
		$q = "insert into logs (user, description, logdate, category) values (:user, :description, :logdate, :logcategory)";			
		try {
			$query = $db->prepare($q);
			$query->bindParam(":user", $_SESSION["userid"]);
			$query->bindParam(":description", $description);
			$query->bindParam(":logdate", $today);
			$query->bindParam(":logcategory", $category);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot Create Log Entry".$e->getMessage();
		}                                            
	}
	
	static private function sendWelcomeSMS($email){}

	static private function sendWelcomeEmail($mobile){}


}