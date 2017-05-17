<?php
/**
* @package Zedek Framework
* @subpackage User Model
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/
namespace __zf__;

class Admin extends Zedek{
	public $userrole;

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}

	public function get_unit_types() {
		$db = self::orm()->pdo;
		$q ="select id, name from unit_types";
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get user types.".$e->getMessage();
		}
		$result = $query->fetchAll();
		$final = "";
		foreach ($result as $res) {
			$final.= "<tr><td>{$res["name"]}</td><td><a href='#'><i class='fa fa-edit'></i> Edit</a></td></tr>";
		}
		return $final;
	}

	function create_new_unit_type (){
		$db = self::orm()->pdo;
		$q1 = "insert into unit_types (name) values (:name)";
		try {
			$db->begintransaction();
			$query = $db->prepare($q1);
			$query->bindParam(':name', $_POST["name"]);
			$query->execute();
			$db->commit();
		} catch (Exception $e) {
			echo "Cannot create Unit type ".$e->getMessage();
			$db->rollback();
		}

	}
	


	
	

	
	static private function sendWelcomeSMS($email){}

	static private function sendWelcomeEmail($mobile){}


}