<?php 
print_r($_SERVER);

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
			$this->_setupId();
		} catch (Exception $e) {
			$this->_json(['error'=>$e->getMessage(), 'code'=>$e->getCode()]);
		}

	}
	// initialize request method
	private function _setupRequestMethod(){
		$this->_requestMethod = $_SERVER['REQUEST_METHOD'];
		if(!in_array($this->_requestMethod, $this->_allowRequestMethods)){
			throw new Exception('Not an allowed request method', 405);
			
		}
	}
	// initialize request resources
	private function _setupResources(){

	}
	// initialize id of resources
	private function _setupId(){

	}
	private function _json($array){
		header('Content-Type:application/json;charset=utf-8');
		echo json_encode($array, JSON_UNESCAPED_UNICODE);
		exit(); 
	}
}

$user = new User($pdo);
$article = new Article($pdo);

$restful = new Restful($user,$article);
$restful->run();