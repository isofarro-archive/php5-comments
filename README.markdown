Comments
========

Building blocks for a commenting system


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




