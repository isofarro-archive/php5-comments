<?php

require_once 'CommentManager.php';
require_once 'CommentStorage.php';

$user = array(
	'username'    => 'joebloggs',
	'created'    => time()
);

$comments = new CommentManager();

echo "1. Adding a new user\n";
$id = $comments->addUser($user);
echo "   ", $id, ': ', $user['username'], ' (', 
	$user['created'], ")\n";


echo "2. Getting an existing user by user_id\n";
$u1 = $comments->getUser($id);
echo "   ", $u1['user_id'], ': ', $u1['username'], ' (', $u1['created'], ")\n";
print_r($u1);

/****
echo "3. Thumbs up and down a comment\n";
$comments->thumbsUpComment($id);
$comments->thumbsDownComment($id);
$comments->thumbsUpComment($id);
$comments->thumbsDownComment($id);
$comments->thumbsUpComment($id);
****/

echo "4. Getting an existing user by username\n";
$u2 = $comments->getUserByUsername('joebloggs');
echo "   ", $u2['user_id'], ': ', $u2['username'], ' (', $u2['created'], ")\n";


/****
echo "5. Updating an existing comment\n";
$c1['body'] = 'Hello world plus an update';
echo "   ", $c1['comment_id'], ': ', $c1['body'], ' (', $c1['created'], ")\n";
$comments->updateComment($c1);

echo "6. Getting an existing comment by comment id\n";
$c3 = $comments->getComment($id);
echo "   ", $c3['comment_id'], ': ', $c3['body'], ' (', $c3['created'], ")\n";

echo "7. Rejecting a comment\n";
$comments->rejectComment($id);
$c4 = $comments->getComment($id);
print_r($c4);
****/


echo "8. Deleting an existing user\n";
if ($comments->deleteUser($id)) {
	echo "User deleted\n";
}



?>