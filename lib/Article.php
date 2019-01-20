<?php  


/**
 * article
 */
class Article 
{
	//database PDO
	private $_db;
	public function __construct($_db){
		$this->_db = $_db;
	}
	//create new post
	public function create($title,$content,$user_id){
		if(empty($title)){
			throw new Exception('article title is empty', ARTICLE_CANNOT_EMPTY);	
		}
		if(empty($content)){
			throw new Exception('content title is empty', CONTENT_CANNOT_EMPTY);	
		}

		$sql = 'INSERT INTO `article` (`title`,`content`,`user_id`) VALUES (:title,:content,:user_id)'; 
		$stmt = $this->_db->prepare($sql);
	
		$stmt->bindValue(':title', $title);
		$stmt->bindValue(':content', $content);
		$stmt->bindValue(':user_id', $user_id);

		if(!$stmt->execute()){
			throw new Exception('Create new post fail', CREATE_NEW_POST_FAIL);
		}
		return [
			'article_id' => $this->_db->lastInsertId(),
			'title' => $title,
			'content' => $content,
			'user_id' => $user_id
		];
	}
	//edit post
	public function edit($article_id,$title,$content,$user_id){

	}
	// get posts list
	public function getList($user_id,$page=1,$size=10){

	}
	// delete post
	public function delete($article_id,$title,$user_id){

	}
}