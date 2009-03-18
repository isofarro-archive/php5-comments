<?php

class CommentManager {
	protected $storage;

	/**
	*	Adds a new comment to storage. Comment is an array.
	*	Returns the comment_id of the new comment
	**/
	public function addComment($comment) {
		$this->initStorage();

		// Replace facets with references, e.g.
		// user structure with user_id
		$comment = $this->dehydrateComment($comment);
		
		// TODO: Add processing logic before saving
		
		return $this->storage->addComment($comment);
				
	}
	
	/**
	*	Gets the comment with the supplied comment_id.
	* 	Returns comment as an array, or NULL
	**/
	public function getComment($comment_id) {
		$this->initStorage();
		$comment = $this->storage->getComment($comment_id);
		return $this->hydrateComment($comment);
	}

	/**
	*	Thumbs up the specified comment
	**/
	public function thumbsUpComment($comment_id) {
		$this->initStorage();

		$comment = $this->getComment($comment_id);
		$comment['thumbsUp']++;
		
		return $this->updateComment($comment);
	}
	
	/**
	*	Thumbs down the specified comment
	**/
	public function thumbsDownComment($comment_id) {
		$this->initStorage();

		$comment = $this->getComment($comment_id);
		$comment['thumbsDown']++;
		
		return $this->updateComment($comment);
	}

	/**
	*	Set the reject flag of the specified comment
	**/
	public function rejectComment($comment_id) {
		$this->initStorage();

		$comment = $this->getComment($comment_id);
		$comment['rejected'] = 1;
		
		return $this->updateComment($comment);
	}	
		
	/**
	*	Delete the specified comment
	**/
	public function deleteComment($comment_id) {
		$this->initStorage();
		return $this->storage->deleteComment($comment_id);
	}
	
	/**
	*	Replace the existing comment with the specified comment data
	*	The replacing comment is an array.
	**/
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

	/**
	*	Get comment by Search-by-example. Parameter is an
	*	array of attributes the comment must match
	**/
	public function getComments($query) {
		$this->initStorage();
		$comments = $this->storage->getComments($query);
		return $this->hydrateComments($comments);
	}
	
	/**
	*	Get all the comments for a particular article
	**/
	public function getArticleComments($article_id) {
		return $this->getComments(array(
			'article_id' => $article_id
		));
	}

	/**
	*	Get all the comments for a particular user
	**/
	public function getUserComments($username) {
		$user = $this->getUserByUsername($username);
		if ($user) {
			return $this->getComments(array(
				'user_id' => $user['user_id']
			));
		} else {
			echo "WARN: No such user found\n";
			return NULL;
		}	
	}

	/**
	*	Add a new user to our storage. User is an array.
	**/
	public function addUser($user) {
		$this->initStorage();
		// TODO: Add processing logic before saving
		// TODO: Check the username doesn't already exist
		
		return $this->storage->addUser($user);
	}

	/**
	*	Get the user details given the user_id
	**/
	public function getUser($user_id) {
		$this->initStorage();
		return $this->storage->getUser($user_id);
	}

	/**
	*	Get the user details of the specified username
	**/
	public function getUserByUsername($username) {
		$this->initStorage();
		return $this->storage->getUsers(array(
			'username' => $username
		));
	}
	
	/**
	*	Delete the specified user
	**/
	public function deleteUser($user_id) {
		$this->initStorage();
		return $this->storage->deleteUser($user_id);
	}
	
	/**
	*	Update the specified user with the specified data
	**/
	public function updateUser($user) {
		$this->initStorage();
		if (!empty($user['user_id'])) {
			return $this->storage->updateUser($user);
		} else {
			echo "ERROR: No user id for update\n";
		}
		return false;
	}
	
	/**
	*	Set the user as flagged
	**/
	public function flagUser($user_id) {
		$this->initStorage();
		$user = $this->getUser($user_id);
		$user['flagged'] = 1;
		return $this->updateUser($user);
	}
	
	/**
	*	Increase the penalty weight for a specified user
	**/
	public function penaliseUser($user_id, $weight=1) {
		$this->initStorage();
		$user = $this->getUser($user_id);
		$user['penalty'] += $weight;
		return $this->updateUser($user);
	}

	/**
	*	Set a specified score specified user
	**/
	public function scoreUser($user_id, $score=1) {
		$this->initStorage();
		$user = $this->getUser($user_id);
		$user['score'] = $score;
		return $this->updateUser($user);
	}



	/**
	*	Replace an id references in a comment with the actual data.
	**/
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
	
	/**
	*	Replace an id references of a list of comments
	*  with the actual data.
	**/
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

	/**
	*	Replaces data structures in a comment with id references.
	**/
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