<?php

require_once 'CommentManager.php';
require_once 'CommentStorage.php';

$comment = array(
	'article_id' => '1',
	'user_id'    => '1',
	'body'       => 'Hello World',
	'created'    => time()
);

$comments = new CommentManager();
$comments->addComment($comment);


?>