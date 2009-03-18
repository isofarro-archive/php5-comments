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
	$comment_id = $manager->addComment($comment);
	
	// Adding a user
	$user = array(
		'username' => 'joebloggs'
	);
	$user_id = $manager->addUser($user);
	
Additional fields/data can be tacked on to the comment and user arrays
just by adding them as simple name/value pairs in their respective
arrays. (As long as value is just a simple string, it will get stored
automatically).

	$manage = new CommentManager();

	// Get all the comments for a specific article
	$comments = $manager->getArticleComments(
		'http://www.example.org/article/hello-world.html'
	);
	
	// Get all the comments for a particular user
	$comments = $manager->getUserComments('joebloggs');

	// Thumbs up a comment
	$comment_id = $comments[0]['comment_id'];
	$manager->thumbsUpComment($comment_id);

	// Flag the user
	$user_id = $comments[0]['user']['userid'];
	$manager->flagUser($user_id);



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




