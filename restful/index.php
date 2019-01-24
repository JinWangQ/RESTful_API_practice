<?php 
//print_r($_SERVER);

require __DIR__.'/../lib/User.php';
require __DIR__.'/../lib/Article.php';
$pdo = require __DIR__.'/../lib/db.php';
class Restful {
	private $_user;
	private $_article;
	// post get ...
	private $_requestMethod;
	// request resource name
	private $_resourceName;
	// id of resources
	private $_id;
	// request resources list
	private $_allowResources = ['users','articles'];
	// allowed HTTP methods
	private $_allowRequestMethods = ['GET','POST','PUT','DELETE','OPTIONS'];
	//status code used
	private $_statusCode = [
		200=>'OK',
		204=>'No Content',
		400=>'Bad Request',
		401=>'Unauthorized',
		403=>'Forbidden',
		404=>'Not Found',
		405=>'Method Not Allowed',
		500=>'Server Internal Error'
	];

	public function __construct(User $_user, Article $_article){
		$this->_user = $_user;
		$this->_article = $_article;
	}
	public function run(){
		try {
			$this->_setupRequestMethod();
			$this->_setupResources();
			if($this->_resourceName == 'users'){
				return $this->_json($this->_handleUser());
			}else{
				return $this->_handleArticle();
			}
		} catch (Exception $e) {
			$this->_json(['error'=>$e->getMessage()],$e->getCode());
		}

	}
	// initialize request method
	private function _setupRequestMethod(){
		$this->_requestMethod = $_SERVER['REQUEST_METHOD'];
		if(!in_array($this->_requestMethod, $this->_allowRequestMethods)){
			throw new Exception('Not an allowed request method',405);
			
		}
	}
	// initialize request resources
	private function _setupResources(){
		$path = $_SERVER['PATH_INFO'];
		//echo "path is".$path;
		$params = explode('/',$path);
		//print_r($params);
		//Array
		// (
		//     [0] => 
		//     [1] => articles
		//     [2] => 
		// )
		$this->_resourceName = $params[1]; // name of resources in $params[1]
		// if(!in_array($this->_resourceName, $this->_allowResources)){
		// 	throw new Exception('Request Resouces Not Allowed', 403);
		// }
		if(!empty($params[2])){
			$this->_id = $params[2];
		}

	}
	
	private function _json($array,$code = 0){
		if($code > 0 && $code != 200 && $code != 204){
			header("HTTP/1.1 ".$code." ".$this->_statusCode[$code]);
		}
		header('Content-Type:application/json;charset=utf-8');
		echo json_encode($array, JSON_UNESCAPED_UNICODE);
		exit(); 
	}
	// Request for user resources
	private function _handleUser(){
		if($this->_requestMethod != 'POST'){
			throw new Exception('Request method is not allowed', 405);
		}
		$body = $this->_getBodyParams();

		//var_dump($body);
		if(empty($body['username'])){
			throw new Exception('Username Cannot be Empty', 400);	
		}
		if(empty($body['password'])){
			throw new Exception('Password Cannot be Empty', 400);
		}
		return  $this->_user->signup($body['username'],$body['password']);
		
	}
	// get request body
	private function _getBodyParams(){
		$raw = file_get_contents('php://input');
		if(empty($raw)){
			throw new Exception('Request Params Error', 400);
		}
		return json_decode($raw,true);  
	}
	// Request for Articles resources
	private function _handleArticle(){

	}
}

$user = new User($pdo);
$article = new Article($pdo);

$restful = new Restful($user,$article);
$restful->run();