<?php

require_once 'CommentManager.php';
require_once 'CommentStorage.php';

$comment = array(
	'article_id' => '1',
//	'comment_id' => '99',
	'user_id'    => '1',
	'body'       => 'Hello World',
	'created'    => time()
);

$comments = new CommentManager();

echo "Adding a new comment\n";
$id = $comments->addComment($comment);
echo $id, ': ', $comment['body'], ' (', 
	$comment['created'], ")\n";

echo "Thumbs up a comment\n";
$comments->thumbsUpComment($id);

echo "Getting an existing comment by comment id\n";
$c1 = $comments->getComment($id);
echo $c1['comment_id'], ': ', $c1['body'], ' (', $c1['created'], ")\n";
print_r($c1);

echo "Getting an existing comment by created date\n";
$c2 = $comments->getComments(array(
	'created' => $c1['created']
));
echo $c2['comment_id'], ': ', $c2['body'], ' (', $c2['created'], ")\n";

echo "Updating an existing comment\n";
$c1['body'] = 'Hello world plus an update';
echo $c1['comment_id'], ': ', $c1['body'], ' (', $c1['created'], ")\n";
$comments->updateComment($c1);

echo "Getting an existing comment by comment id\n";
$c3 = $comments->getComment($id);
echo $c3['comment_id'], ': ', $c3['body'], ' (', $c3['created'], ")\n";

echo "Deleting an existing comment\n";
if ($comments->deleteComment($id)) {
	echo "Comment deleted\n";
}

?>