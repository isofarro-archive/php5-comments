<?php

class CommentManager {
	protected $storage;

	public function addComment($comment) {
		$this->initStorage();
		
		// TODO: Add processing logic before saving
		
		return $this->storage->addComment($comment);
				
	}
	
	public function getComment($comment_id) {
		$this->initStorage();
		return $this->storage->getComment($comment_id);
	}
	
	public function deleteComment($comment_id) {
		$this->initStorage();
		return $this->storage->deleteComment($comment_id);
	}
	
	public function updateComment($comment_id) {
	
	}

	public function getComments($query) {
		$this->initStorage();
		return $this->storage->getComments($query);	
	}

	public function getUser($user_id) {
	
	}
	
	
	protected function initStorage() {
		if (!$this->storage) {
			$this->storage = new XmlCommentStorage();
			//$this->storage = new SqliteCommentStorage();
		}
	}
}



?>