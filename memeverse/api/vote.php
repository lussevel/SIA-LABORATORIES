<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isLoggedIn()) {

    jsonResponse([
        'error' => 'Login required'
    ], 401);

}

$data =
json_decode(
file_get_contents('php://input'),
true
);

$post_id =
(int)($data['post_id'] ?? 0);

$vote =
(int)($data['vote'] ?? 0);

if (
$post_id <= 0 ||
!in_array($vote,[1,-1,0])
) {

jsonResponse([
'error'=>'Invalid vote'
],400);

}

$user_id =
$_SESSION['user_id'];

if($vote === 0){

$stmt =
$conn->prepare(
"DELETE FROM votes
WHERE user_id=?
AND post_id=?"
);

$stmt->bind_param(
"ii",
$user_id,
$post_id
);

$stmt->execute();

}else{

$stmt =
$conn->prepare(

"INSERT INTO votes
(user_id,post_id,vote)

VALUES (?,?,?)

ON DUPLICATE KEY UPDATE
vote=VALUES(vote)"

);

$stmt->bind_param(
"iii",
$user_id,
$post_id,
$vote
);

$stmt->execute();

}

$upvotes =
$conn->query(
"SELECT COUNT(*) total
FROM votes
WHERE post_id=$post_id
AND vote=1"
)->fetch_assoc()['total'];

$downvotes =
$conn->query(
"SELECT COUNT(*) total
FROM votes
WHERE post_id=$post_id
AND vote=-1"
)->fetch_assoc()['total'];

jsonResponse([

'success'=>true,

'upvotes'=>
(int)$upvotes,

'downvotes'=>
(int)$downvotes,

'user_vote'=>
$vote

]);