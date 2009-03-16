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

	public function thumbsUpComment($comment_id) {
		$this->initStorage();
		return $this->storage->thumbsUpComment($comment_id);
	}
	
	public function thumbsDownComment($comment_id) {
		$this->initStorage();
		return $this->storage->thumbsDownComment($comment_id);
	}
		

	public function deleteComment($comment_id) {
		$this->initStorage();
		return $this->storage->deleteComment($comment_id);
	}
	
	public function updateComment($comment) {
		$this->initStorage();
		if (!empty($comment['comment_id'])) {
			return $this->storage->updateComment($comment);
		} else {
			echo "ERROR: No comment id for update\n";
		}
		return false;
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