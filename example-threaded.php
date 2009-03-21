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
	),
	array(
		'article_id' => '1',
		'user'       => array(
			'username' => 'anon 3'
		),
		'body'       => 'post 2',
		'created'    => time()
	),
	array(
		'article_id' => '1',
		'user'       => array(
			'username' => 'anon 4'
		),
		'body'       => 'Another reply to the first comment',
		'created'    => time()
	),
	array(
		'article_id' => '1',
		'user'       => array(
			'username' => 'anon 5'
		),
		'body'       => 'A reply to post 2',
		'created'    => time()
	)
);

$manager = new CommentManager();

/****
echo "1. Adding a new comment\n";
$id0 = $manager->addComment($comments[0]);
echo "   ", $id0, ': ', $comments[0]['body'], ' (', 
	$comments[0]['created'], ")\n";

echo "2. Add a new reply\n";
$comments[1]['replyto_id'] = $id0;
$id1 = $manager->addComment($comments[1]);
echo "   ", $id1, ': ', $comments[1]['body'], ' (', 
	$comments[1]['created'], ")\n";

echo "3. Adding a new comment\n";
$id2 = $manager->addComment($comments[2]);
echo "   ", $id2, ': ', $comments[2]['body'], ' (', 
	$comments[2]['created'], ")\n";

echo "4. Add a new reply\n";
$comments[3]['replyto_id'] = $id0;
$id3 = $manager->addComment($comments[3]);
echo "   ", $id3, ': ', $comments[3]['body'], ' (', 
	$comments[3]['created'], ")\n";

echo "5. Add a new reply\n";
$comments[4]['replyto_id'] = $id2;
$id4 = $manager->addComment($comments[4]);
echo "   ", $id4, ': ', $comments[4]['body'], ' (', 
	$comments[4]['created'], ")\n";
****/


echo "6. get threaded comments for article\n";
$articleComments = $manager->getThreadedArticleComments('1');
//print_r($articleComments);
foreach($articleComments as $cmt) {
	if (!empty($cmt['replyto_id'])) {
		echo "\t({$cmt['replyto_id']}) {$cmt['comment_id']}:{$cmt['body']}\n";
	} else {
		echo "{$cmt['comment_id']}:{$cmt['body']}\n";
	}
}


?>