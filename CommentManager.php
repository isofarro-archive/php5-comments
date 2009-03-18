<?php

class CommentManager {
	protected $storage;

	public function addComment($comment) {
		$this->initStorage();

		// Replace facets with references, e.g.
		// user structure with user_id
		$comment = $this->dehydrateComment($comment);
		
		// TODO: Add processing logic before saving
		
		return $this->storage->addComment($comment);
				
	}
	
	public function getComment($comment_id) {
		$this->initStorage();
		$comment = $this->storage->getComment($comment_id);
		return $this->hydrateComment($comment);
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
			$comment = $this->dehydrateComment($comment);
			return $this->storage->updateComment($comment);
		} else {
			echo "ERROR: No comment id for update\n";
		}
		return false;
	}

	public function getComments($query) {
		$this->initStorage();
		$comments = $this->storage->getComments($query);
		return $this->hydrateComments($comments);
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
	

	protected function hydrateComment($comment) {
		// Replace user_id with user details
		if (!empty($comment['user_id'])) {
			$user = $this->getUser($comment['user_id']);
			if (!empty($user)) {
				$comment['user'] = $user;
				unset($comment['user_id']);
			}
		}
	
		return $comment;
	}
	
	protected function hydrateComments($comments) {
		if (!empty($comments['comment_id'])) {
			// Single comment
			$comments = $this->hydrateComment($comments); 
		} else {
			// Multiple comments
			$userComments = array();
			foreach($comments as $comment) {
				$userComments[] = $this->hydrateComment($comment);
			}
			$comments = $userComments;
		} 
		return $comments;
	}

	protected function dehydrateComment($comment) {
		// Check whether there's user data attached
		if (!empty($comment['user'])) {
			//echo "INFO: User details found\n";
			if (!empty($comment['user']['username'])) {
				$user = $this->getUserByUsername($comment['user']['username']);
				if(!is_null($user)) {
					//echo "INFO: User "; print_r($user);
					unset($comment['user']);
					$comment['user_id'] = $user['user_id'];
				} else {
					// This is a new user
					$user_id = $this->addUser($comment['user']);
					if ($user_id) {
						//echo "INFO: Added a new user $user_id\n";
						unset($comment['user']);
						$comment['user_id'] = $user_id;
					} else {
						echo "ERROR: Can't add user\n";
					}
				}
			} else {
				echo "ERROR: no username\n";
				return NULL;
			}
		}
		return $comment;	
	}
	
	
	protected function initStorage() {
		if (!$this->storage) {
			$this->storage = new XmlCommentStorage();
			//$this->storage = new SqliteCommentStorage();
		}
	}
}



?>