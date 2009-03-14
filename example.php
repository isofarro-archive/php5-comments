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

echo "Adding a new comment\n";
$comments->addComment($comment);

echo "Getting an existing comment by comment id\n";
$c1 = $comments->getComment('99');
echo "Existing comment published at ", $c1['created'], "\n";

echo "Getting an existing comment by created date\n";
$c2 = $comments->getComments(array(
	'created' => $c1['created']
));
echo "Existing comment published at ", $c2['created'], "\n";

echo "Deleting an existing comment\n";
if ($comments->deleteComment('99')) {
	echo "Comment deleted\n";
}

?>