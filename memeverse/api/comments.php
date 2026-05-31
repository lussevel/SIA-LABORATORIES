<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$method =
$_SERVER['REQUEST_METHOD'];

if($method === 'GET'){

$post_id =
(int)($_GET['post_id'] ?? 0);

$stmt =
$conn->prepare(

"SELECT

c.*,

u.username

FROM comments c

JOIN users u
ON c.user_id=u.id

WHERE c.post_id=?

ORDER BY c.created_at DESC"

);

$stmt->bind_param(
"i",
$post_id
);

$stmt->execute();

$result =
$stmt->get_result();

$comments = [];

while(
$row =
$result->fetch_assoc()
){

$comments[] = [

'id'=>
$row['id'],

'content'=>
$row['content'],

'created_at'=>
$row['created_at'],

'username'=>
$row['username'],

'user_id'=>
$row['user_id']

];

}

jsonResponse([
'comments'=>
$comments
]);

}

if($method === 'POST'){

if(!isLoggedIn()){

jsonResponse([
'error'=>'Login required'
],401);

}

$data =
json_decode(
file_get_contents('php://input'),
true
);

$post_id =
(int)($data['post_id'] ?? 0);

$content =
trim(
$data['content'] ?? ''
);

if(
$post_id<=0 ||
empty($content)
){

jsonResponse([
'error'=>'Invalid comment'
],400);

}

$user_id =
$_SESSION['user_id'];

$stmt =
$conn->prepare(

"INSERT INTO comments
(
user_id,
post_id,
content
)
VALUES
(?,?,?)"

);

$stmt->bind_param(
"iis",
$user_id,
$post_id,
$content
);

$stmt->execute();

jsonResponse([
'success'=>true
]);

}