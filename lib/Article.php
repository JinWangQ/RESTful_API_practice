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
			throw new Exception('article title is empty', ErrorCode::ARTICLE_CANNOT_EMPTY);	
		}
		if(empty($content)){
			throw new Exception('content title is empty', ErrorCode::CONTENT_CANNOT_EMPTY);	
		}

		$sql = 'INSERT INTO `article` (`title`,`content`,`user_id`) VALUES (:title,:content,:user_id)'; 
		$stmt = $this->_db->prepare($sql);
	
		$stmt->bindValue(':title', $title);
		$stmt->bindValue(':content', $content);
		$stmt->bindValue(':user_id', $user_id);

		if(!$stmt->execute()){
			throw new Exception('Create new post fail', ErrorCode::CREATE_NEW_POST_FAIL);
		}
		return [
			'article_id' => $this->_db->lastInsertId(),
			'title' => $title,
			'content' => $content,
			'user_id' => $user_id
		];
	}
	// view post
	public function view($article_id){
		if(empty($article_id)){
			throw new Exception('article id cannot be empty', ErrorCode::ARTICLE_ID_CANNOT_EMPTY);	
		}
		$sql = 'SELECT * FROM `article` WHERE `article_id`=:id';
		$stmt = $this->_db->prepare($sql);
		$stmt->bindValue(':id',$article_id);
		$stmt->execute();
		$article = $stmt->fetch(PDO::FETCH_ASSOC);
		if(empty($article)){
			throw new Exception('article does not exist', ErrorCode::ARTICLE_NOT_EXIST);
		}
		return $article;
	}
	//edit post
	public function edit($article_id,$title,$content,$user_id){
		$article = $this->view($article_id);
		//var_dump($article['user_id'],$user_id);exit();
		if($article['user_id'] !== $user_id){
			throw new Exception('You cannot edit this post', ErrorCode::PERMISSION_DENIED);	
		}
		$title = empty($title) ? $article['title'] : $title;
		$content = empty($content) ? $article['content'] : $content;
		if($title === $article['title'] && $content === $article['content']){
			return $article;
		}
		$sql = 'UPDATE `article` SET `title`=:title,`content`=:content WHERE `article_id`=:id';
		$stmt = $this->_db->prepare($sql);
		$stmt->bindValue(':title',$title);
		$stmt->bindValue(':content',$content);
		$stmt->bindValue(':id',$article_id);
		if(!$stmt->execute()){
			throw new Exception('Edit post fail', ErrorCode::EDIT_POST_FAIL);
		}
		return [
			'article_id'=>$article_id,
			'title' => $title,
			'content' => $content
		];
	}
	// get posts list
	public function getList($user_id,$page=1,$size=10){
		if($size > 100) {
			throw new Exception('Page limited 100', ErrorCode::PAGE_LIMIT_EXCEED);
		}
		$sql = 'SELECT * FROM `article` WHERE `user_id`=:user_id LIMIT :limit, :offset';
		$limit = ($page-1)*$size;
		$limit = $limit < 0 ? 0 : $limit;
		$stmt = $this->_db->prepare($sql);
		$stmt->bindValue(':limit', $limit);
		$stmt->bindValue(':offset', $size);
		$stmt->bindValue('user_id', $user_id);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	// delete post
	public function delete($article_id,$user_id){
		$article = $this->view($article_id);
		if($article['user_id'] !== $user_id){
			throw new Exception('You cannot delete', ErrorCode::PERMISSION_DENIED);	
		}
		$sql = 'DELETE FROM `article` WHERE `article_id`=:article_id AND `user_id`=:user_id';
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':article_id',$article_id);
		$stmt->bindParam(':user_id',$user_id);

		if(!$stmt->execute()){
			throw new Exception('Delete failed', ErrorCode::DELETE_POST_FAILED);
		}
		return true;
	}
}