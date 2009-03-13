<?php

require_once 'CommentManager.php';
require_once 'CommentStorage.php';

$comment = array(
	'article_id' => '1',
	'comment_id' => '99',
	'user_id'    => '1',
	'body'       => 'Hello World',
	'created'    => time()
);

$comments = new CommentManager();
//$comments->addComment($comment);

$c1 = $comments->getComment('99');
echo "c1 created: ", $c1['created'], "\n";

$c2 = $comments->getComments(array(
	'created' => $c1['created']
));

?>