<?php 
require __DIR__."/ErrorCode.php";
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
		//return $this->_isUsernameExists($username); 
		
		// check if username and/or password is empty
		if(empty($username)){
			//test
			//return ErrorCode::USERNAME_CANNOT_EMPTY;
			throw new Exception('Username cannot be empty', ErrorCode::USERNAME_CANNOT_EMPTY);
		}
		if(empty($password)){
			//test
			//return ErrorCode::PASSWORD_CANNOT_EMPTY;
			throw new Exception('Password cannot be empty', ErrorCode::PASSWORD_CANNOT_EMPTY);
		}
		// test
		// if(!empty(username) && !empty(password)){
		// 	return "OK".$username.$password;
		// }
		
		// when both username & password are not empty
		// check is username exists
		if($this->_isUsernameExists($username)){
			//test
			//return ErrorCode::USERNAME_EXISTS;
			throw new Exception('Username exists', ErrorCode::USERNAME_EXISTS);
		}
		
		
		//sql part
		$password = $this->_md5($password);
		$created_at = date('Y-m-d H:i:s');
		$sql = 'INSERT INTO `user` (`username`, `password`, `created_at`) VALUES (:username,:password,:created_at)';
		$stmt= $this->_db->prepare($sql);
	
		$stmt->bindValue(':username', $username);
		$stmt->bindValue(':password', $password);
		$stmt->bindValue(':created_at', $created_at);
		//echo $username.$password."---".$created_at;
		
		if($stmt->execute() === false){
            print_r($this->_db->errorInfo());
			throw new Exception('Signing up fail', ErrorCode::SIGN_UP_FAILED);	
		}
		//return "fine";
		return [
			'userId' => $this->_db->lastInsertId(),
			'username' => $username,
			'created_at' => $created_at
		];
	}

	// MD5 Message-Digest Algorithm 
	private function _md5($string, $key = 'imooc'){
		return md5($string . $key);
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
		// $res = 'No...';
		// if(!empty($result))
		// 	$res = 'Yes!';
		// return $res;
		
		//return 1 when user_name exists
		return !empty($result);
			
	}
}