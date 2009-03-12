<?php

class CommentManager {
	protected $storage;

	public function addComment($comment) {
		$this->initStorage();
				
	}
	
	public function getComment($comment_id) {
	
	}
	
	public function deleteComment($comment_id) {
	
	}
	
	public function updateComment($comment_id) {
	
	}

	public function getComments($query) {
	
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