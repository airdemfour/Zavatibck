<?php
/**
* @package Zedek Framework
* @subpackage User Model
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/
namespace __zf__;

class Project extends Zedek{
	public $userrole;

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}

	public function get_projects() {
		$db = self::orm()->pdo;
		$q ="select id, name, site_loc, start_date, end_date, description from project";
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Projects".$e->getMessage();
		}
		$result = $query->fetchAll();
		return $result;
	}

	public function get_project($id) {
		$db = self::orm()->pdo;
		$q ="select id, name, site_loc, start_date, end_date, description from project where id = :id";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':id', $id);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Project".$e->getMessage();
		}
		$result = $query->fetchObject();
		return $result;
	}

	
	

	
	static private function sendWelcomeSMS($email){}

	static private function sendWelcomeEmail($mobile){}


}