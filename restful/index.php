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

	
	// Request for Articles resources
	private function _handleArticle(){
		switch($this->_requestMethod){
			case 'POST':
				return $this->_handleArticleCreate();
			case 'PUT':
				return $this->_handleArticleEdit();
			case 'DELETE':
				return $this->_handleArticleDelete();
			case 'GET':
				if(empty($this->id)){
					return $this->_handleArticleList();
				}else{
					return $this->_handleArticleView();
				}
			default:
			throw new Exception('Request method is not allowed', 405);
		}
	}

	private function _handleArticleCreate(){
		$body = $this->_getBodyParams();
		if(empty($body['title'])){
			throw new Exception('Post title cannot be empty', 400);
		}if(empty($body['content'])){
			throw new Exception('Post content cannot be empty', 400);
		}
		$user = $this->_userLogin($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
		try{
			$article = $this->_article->create($body['title'],$body['content'],$user['user_id']);
			return $article;
		}catch(Exception $e){
			if(!is_array($e->getMessage(),
			[
				ErrorCode::ARTICLE_CANNOT_EMPTY,
				ErrorCode::CONTENT_CANNOT_EMPTY
			])){
				throw new Exception($e->getMessage(), 400);
			}
			throw new Exception($e->getMessage(), 500);
			
		}

	}
	private function _handleArticleEdit(){
		
	}
	private function _handleArticleDelete(){
		
	}
	private function _handleArticleList(){
		
	}
	private function _handleArticleView(){
		
	}
	private function _userLogin($PHP_AUTH_USER, $PHP_AUTH_PW){
		try{
			return $this->_user->login($PHP_AUTH_USER, $PHP_AUTH_PW);
		}catch(Exception $e){
			if(in_array($e->getCode(),
				[
					ErrorCode::USERNAME_CANNOT_EMPTY,
					ErrorCode::PASSWORD_CANNOT_EMPTY,
					ErrorCode::USERNAME_OR_PASSWORD_INVALID
				])) {
					throw new Exception($e->getMessage(), 400);
			}
			throw new Exception($e->getMessage(), 500);
		}
	}
	// get request body
	private function _getBodyParams(){
		$raw = file_get_contents('php://input');
		if(empty($raw)){
			throw new Exception('Request Params Error', 400);
		}
		return json_decode($raw,true);  
	}
}

$user = new User($pdo);
$article = new Article($pdo);

$restful = new Restful($user,$article);
$restful->run();