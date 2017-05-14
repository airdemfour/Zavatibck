<?php
/**
* @package Zedek Framework
* @subpackage User Model
* @version 4
* @author defestdude <defestdude@gmail.com> Donald Mkpanam
*/
namespace __zf__;

class User extends Zedek{
	public $userrole;

	static function orm(){
		$orm =  new ZORM;
		$orm =  $orm::cxn();
		$orm->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		//$orm->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);		
		return $orm;
	}

	public function login() {
		$username = $_POST["username"];
		$password = $_POST["password"];
		$password = md5($password);
		$db = self::orm()->pdo;
		$return = 0;
		$q ="select usr_id, user_role from user where user_name = :username and password = :password";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':username', $username);
			$query->bindParam(':password', $password);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot Login.".$e->getMessage();
		}
		$res = $query->fetchObject();

		if (is_object($res)) {
			$return = $res->usr_id;
			$_SESSION["username"] = $username;
			$_SESSION["role"] = $res->user_role;
			//$_SESSION["role"] = $this->get_user_role($return);
			//echo $_SESSION["role"];
		}
		return $return;
	}

	public function get_user_role($user) {
		$db = self::orm()->pdo;
		$q ="select role from users where id = :user";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':user', $user);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot User Role.".$e->getMessage();
		}
		return $query->fetchObject()->role;
	}
	
	public function get_name_from_username($id) {
		$db = self::orm()->pdo;
		$q ="select name from users where id = :id";
		try {
			$query = $db->prepare($q);
			$query->bindParam(':id', $id);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot Get User Name.".$e->getMessage();
		}
		return $query->fetchObject()->name;
	}

	function create_user(){
		$db = self::orm()->pdo;
		$q1 = "insert into users (firstname, lastname, username, password, role) values (:firstname, :lastname, :username, :password, :role)";
		$datecreated = date("Y-m-d H:i:s");

		$password = $_POST["password"];
		$password = md5($password);

		try {
			$db->begintransaction();
			$query = $db->prepare($q1);
			$query->bindParam(':firstname', $_POST["firstname"]);
			$query->bindParam(':lastname', $_POST["lastname"]);
			$query->bindParam(':username', $_POST["username"]);
			$query->bindParam(':password', $password);
			$query->bindParam(':role', $_POST["role"]);
			$query->execute();
			$db->commit();
		} catch (Exception $e) {
			echo "Cannot create user ".$e->getMessage();
			$db->rollback();
		}

	}

	function get_user_types() {
		$db = self::orm()->pdo;
		$q = "select id, name from roles";		
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get User Types".$e->getMessage();
		}
		$results =  $query->fetchAll();
		return $results;                                              
	}



	function get_users() {
		$db = self::orm()->pdo;
		$q = "select firstname, lastname, username, roles.role from roles join users on users.role = roles.id";		
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Users".$e->getMessage();
		}
		$users = $query->fetchAll();
		return $users;
	}

	function get_users_list() {
		$db = self::orm()->pdo;
		$q = "select users.id, users.name from users";
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Users".$e->getMessage();
		}
		$display = "";
		$results =  $query->fetchAll();
		/*for($i = 0; $i < sizeof($results); $i++) {
			if ($i == 0) {
				$display .= "<option value='".$results[$i]["id"]."' selected='selected'>".$results[$i]["name"]."</option>";
			} else {
				$display .= "<option value='".$results[$i]["id"]."'>".$results[$i]["name"]."</option>";
			}
			
		}*/
		return $results;
	}

	function get_user_category($userid) {
		$db = self::orm()->pdo;
		$q = "select role from staff_roles where staff = :id";		
		try {
			$query = $db->prepare($q);
			$query->bindParam(':id', $userid);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get archives count".$e->getMessage();
		}
		return $query->fetchObject()->role;
	}

	function delete_user($userid) {
		$db = self::orm()->pdo;
		$q = "delete from users where id = :id";		
		try {
			$query = $db->prepare($q);
			$query->bindParam(':id', $userid);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot Delete User".$e->getMessage();
		}
		//return $query->fetchObject()->name;
	}





	static private function sendWelcomeSMS($email){}

	static private function sendWelcomeEmail($mobile){}


}