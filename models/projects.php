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

	public function create_new_block() {
		$db = self::orm()->pdo;
		$q ="insert into project_blocks (project, name, description) values (:project, :name, :description)";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':name', $_POST["name"]);
			$query->bindParam(':description', $_POST["description"]);
			$query->bindParam(':project', $_POST["projectid_blocks"]);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot create project block".$e->getMessage();
		}
	}

	public function create_new_unit() {
		$db = self::orm()->pdo;
		$q ="insert into blocks_units (block, unit_type, unit_description) values (:block, :unit_type, :unit_description)";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':block', $_POST["blockid"]);
			$query->bindParam(':unit_type', $_POST["unit_type"]);
			$query->bindParam(':unit_description', $_POST["unit_description"]);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot create project block".$e->getMessage();
		}
	}

	public function get_blocks($project) {
		$db = self::orm()->pdo;
		$q ="select project_blocks.id as bid, name, description, (select count(id) from blocks_units where blocks_units.block = bid) as units
		from project_blocks where project = :project";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':project', $project);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Projec Blocks".$e->getMessage();
		}
		$result = $query->fetchAll();
		return $result;

	}

	public function get_block($id) {
		$db = self::orm()->pdo;
		$q ="select project_blocks.id as bid, name, description, (select count(id) from blocks_units where blocks_units.block = bid) as units
		from project_blocks where id = :id";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':id', $id);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Projec Blocks".$e->getMessage();
		}
		$result = $query->fetchObject();
		return $result;

	}

	public function get_project_units($project) {
		$db = self::orm()->pdo;
		$q ="select blocks_units.id as unitid, blocks_units.unit_description as unitdescription, project_blocks.name as blockname,
		unit_types.name as unittype from blocks_units join project_blocks on blocks_units.block = project_blocks.id
		join unit_types on blocks_units.unit_type = unit_types.id
		where project_blocks.project = :project";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':project', $project);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Project Units".$e->getMessage();
		}
		$result = $query->fetchAll();
		return $result;

	}

	public function get_unit_types() {
		$db = self::orm()->pdo;
		$q ="select id, name from unit_types";
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get unit types.".$e->getMessage();
		}
		$result = $query->fetchAll();
		return $result;
	}

	public function get_pid_from_bid($blockid) {
		$db = self::orm()->pdo;
		$q ="select project from project_blocks where id = :id";
		//echo
		try {
			$query = $db->prepare($q);
			$query->bindParam(':id', $blockid);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot create project block".$e->getMessage();
		}
		$result = $query->fetchObject();
		return $result->project;
	}

	
	

	
	static private function sendWelcomeSMS($email){}

	static private function sendWelcomeEmail($mobile){}


}