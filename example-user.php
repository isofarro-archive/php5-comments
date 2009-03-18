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
//print_r($u1);

echo "3. Flag a user\n";
$comments->flagUser($id);

echo "4. Penalise a user\n";
$comments->penaliseUser($id, 5);

echo "5. Penalise a user\n";
$comments->scoreUser($id, 1);


echo "6. Getting an existing user by username\n";
$u2 = $comments->getUserByUsername('joebloggs');
echo "   ", $u2['user_id'], ': ', $u2['username'], ' (', $u2['created'], ")\n";
print_r($u2);

echo "7. Deleting an existing user\n";
if ($comments->deleteUser($id)) {
	echo "User deleted\n";
}



?>