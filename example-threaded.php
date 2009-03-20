<?php

require_once 'CommentManager.php';
require_once 'CommentStorage.php';

$comments = array(
	array(
		'article_id' => '1',
		'user'       => array(
			'username' => 'joebloggs'
		),
		'body'       => 'Hello World',
		'created'    => time()
	),
	array(
		'article_id' => '1',
		'user'       => array(
			'username' => 'user2'
		),
		'body'       => 'Greetings to you too',
		'created'    => time()
	)
);

$manager = new CommentManager();

echo "1. Adding a new comment\n";
$id = $manager->addComment($comments[0]);
echo "   ", $id, ': ', $comments[0]['body'], ' (', 
	$comments[0]['created'], ")\n";

echo "2. Add a new reply\n";
$comments[1]['replyto_id'] = $id;
$id1 = $manager->addComment($comments[1]);
echo "   ", $id1, ': ', $comments[1]['body'], ' (', 
	$comments[1]['created'], ")\n";

echo "3. get previous comment\n";
$comment = $manager->getComment($id1);
//print_r($comment);


echo "4. get comments for article\n";
$articleComments = $manager->getArticleComments('1');
print_r($articleComments);

?>