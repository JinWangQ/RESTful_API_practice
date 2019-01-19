<?php 
class User{
	private $_db;

	public function __construct($_db){
		
		$this->_db = $_db;
	}

	//user login
	public function login($username, $password){

	}
	//user sign up
	public function signup($username, $password){
		
		//test if _isUsernameExists() is well-worked
		return $this->_isUsernameExists($username); 
		
		
	}

	//check if username had been used 
	private function _isUsernameExists($username){
		
		$sql = 'SELECT * FROM `user` WHERE `username`=:username';
		
		$stmt = $this->_db->prepare($sql);
			
		$stmt->bindParam(':username', $username);
		
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		// test if _isUsernameExists() is well-worked
		// no: user_name is not being used
		// yes: user_name is being used
		// 
		$res = 'No...';
		if(!empty($result))
			$res = 'Yes!';
		return $res;
		
		
	}
}