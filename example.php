<?php

require_once 'CommentManager.php';
require_once 'CommentStorage.php';

$manager = new CommentManager();
//setUp($manager);


//$article1Comments = $manager->getArticleComments('1');
//print_r($article1Comments);

$user1Comments = $manager->getUserComments('user1');
print_r($user1Comments);




function setUp($manager) {
	$comments = array(
		array(
			'article_id'	=> '1',
			'user'			=> array(
				'username'	=> 'user1'
			),
			'body'			=> "frist post!!"
		),
		array(
			'article_id'	=> '1',
			'user'			=> array(
				'username'	=> 'user2'
			),
			'body'			=> "That was an awesome post"
		),
		array(
			'article_id'	=> '1',
			'user'			=> array(
				'username'	=> 'user1'
			),
			'body'			=> "frist reply!!"
		),
		array(
			'article_id'	=> '2',
			'user'			=> array(
				'username'	=> 'user2'
			),
			'body'			=> "Best article ever"
		)
	);
	
	foreach ($comments as $comment){
		$id = $manager->addComment($comment);
		echo "Adding comment: $id\n";
	}
}



?>