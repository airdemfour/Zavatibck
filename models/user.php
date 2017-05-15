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
		$q ="select id from user where user_name = :username and password = :password";
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
			$return = $res->id;
			$_SESSION["username"] = $username;
			//$_SESSION["role"] = $res->user_role;
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

	function create_new_user(){
		$db = self::orm()->pdo;
		$q1 = "insert into user (first_name, last_name, user_name, email, password) values (:firstname, :lastname, :username, :email, :password)";
		$q2 = "insert into user_role (user, role) values (:user, :role)";
		$datecreated = date("Y-m-d H:i:s");

		$password = $_POST["password"];
		$password = md5($password);

		try {
			$db->begintransaction();
			$query = $db->prepare($q1);
			$query->bindParam(':firstname', $_POST["first_name"]);
			$query->bindParam(':lastname', $_POST["last_name"]);
			$query->bindParam(':username', $_POST["user_name"]);
			$query->bindParam(':email', $_POST["email"]);
			$query->bindParam(':password', $password);
			$query->execute();
			$userid = $db->lastInsertId(); 
			$query2 = $db->prepare($q2);
			$query2->bindParam(':user', $userid);
			$query2->bindParam(':role', $_POST["role"]);
			$query2->execute();
			$db->commit();
		} catch (Exception $e) {
			echo "Cannot create user ".$e->getMessage();
			$db->rollback();
		}

	}
	function create_new_role(){
		$db = self::orm()->pdo;
		$q1 = "insert into roles (name) values (:role)";
		try {
			$db->begintransaction();
			$query = $db->prepare($q1);
			$query->bindParam(':role', $_POST["name"]);
			$query->execute();
			$db->commit();
		} catch (Exception $e) {
			echo "Cannot create role ".$e->getMessage();
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
		$q = "select first_name, last_name, email, user_name, roles.name as role from user join user_role on user.id = user_role.user join roles on roles.id = user_role.role";		
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Users".$e->getMessage();
		}
		$users = $query->fetchAll();
		$output = "";
		foreach ($users as $user) {
			$output.= "<tr>
			<td>{$user["first_name"]}</td>
			<td>{$user["last_name"]}</td>
			<td>{$user["user_name"]}</td>
			<td>{$user["role"]}</td>
			<td>{$user["email"]}</td>
			<td>edit</td>
			</tr>";
		}
		return $output;
	}


	function get_roles() {
		$db = self::orm()->pdo;
		$q = "select id, name as role from roles";		
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Roles".$e->getMessage();
		}
		$roles = $query->fetchAll();
		$output = "";
		foreach ($roles as $role) {
			$output.= "<tr><td>{$role["role"]}</td><td><a href='#'><i class='fa fa-edit'></i> Edit</a></td></tr>";
		}
		return $output;
	}


	function get_roles_list() {
		$db = self::orm()->pdo;
		$q = "select id, name as role from roles";		
		try {
			$query = $db->prepare($q);
			$query->execute();
		} catch (Exception $e) {
			echo "Cannot get Roles".$e->getMessage();
		}
		$roles = $query->fetchAll();
		$output = "";
		foreach ($roles as $role) {
			$output.= "<option value='{$role["id"]}'>{$role["role"]}</option>";
		}
		return $output;
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