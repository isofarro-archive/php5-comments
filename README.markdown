Comments
========

Building blocks for a commenting system

Usage:

	$manager = new CommentManager();

	// Adding a comment
	$comment = array(
		'article_id' => 'http://www.example.org/article/hello-world.html',
		'body'       => 'frist post!!',
		'user'       => array(
			'username' => 'joebloggs'
		)
	);
	$manager->addComment($comment);	
	
	
	// Adding a user
	$user = array(
		'username' => 'joebloggs'
	);
	$user_id = $manager->addUser($user);
	
Additional fields/data can be tacked on to the comment and user arrays
just by adding them as simple name/value pairs in their respective
arrays. (As long as value is just a simple string, it will get stored
automatically).


CommentManager API
------------------

* `int addComment($comment)`
* `Comment getComment($comment_id)`
* `array getComments($query)`
* `array getArticleComments($articleUrl)`
* `array getUserComments($username)`
* `boolean updateComment($comment)`
* `boolean deleteComment($comment)`
* `boolean thumbsUpComment($comment_id)`
* `boolean thumbsDownComment($comment_id)`
* `boolean rejectComment($comment_id)`
* `int addUser($user)`
* `User getUser($user_id)`
* `User getUserByUsername($username)`
* `boolean deleteUser($user_id)`
* `boolean flagUser($user_id)`
* `boolean penaliseUser($user_id, $weight)`
* `boolean scoreUser($user_id, $score)`




