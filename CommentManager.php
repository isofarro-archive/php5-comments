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

		$comment = $this->getComment($comment_id);
		$comment['thumbsUp']++;
		
		return $this->updateComment($comment);
	}
	
	public function thumbsDownComment($comment_id) {
		$this->initStorage();

		$comment = $this->getComment($comment_id);
		$comment['thumbsDown']++;
		
		return $this->updateComment($comment);
	}

	public function rejectComment($comment_id) {
		$this->initStorage();

		$comment = $this->getComment($comment_id);
		$comment['rejected'] = 1;
		
		return $this->updateComment($comment);
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




	public function addUser($user) {
		$this->initStorage();
		// TODO: Add processing logic before saving
		// TODO: Check the username doesn't already exist
		
		return $this->storage->addUser($user);
	}

	public function getUser($user_id) {
		$this->initStorage();
		return $this->storage->getUser($user_id);
	}
	
	public function getUserByUsername($username) {
		$this->initStorage();
		return $this->storage->getUsers(array(
			'username' => $username
		));
	}
	
	public function deleteUser($user_id) {
		$this->initStorage();
		return $this->storage->deleteUser($user_id);
	}
	


	
	
	protected function initStorage() {
		if (!$this->storage) {
			$this->storage = new XmlCommentStorage();
			//$this->storage = new SqliteCommentStorage();
		}
	}
}



?>