<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if(!isLoggedIn()){

jsonResponse([
'error'=>'Login required'
],401);

}

$user_id =
$_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD']==='GET'){

$stmt =
$conn->prepare(

"SELECT
m.*,
u.username
FROM messages m
JOIN users u
ON u.id = m.sender_id
WHERE receiver_id=?
ORDER BY created_at DESC"

);

$stmt->bind_param(
"i",
$user_id
);

$stmt->execute();

$result =
$stmt->get_result();

$messages = [];

while(
$row =
$result->fetch_assoc()
){

$messages[] = $row;

}

jsonResponse([
'messages'=>$messages
]);

}

$data =
json_decode(
file_get_contents(
'php://input'
),
true
);

$receiver_id =
(int)($data['receiver_id'] ?? 0);

$message =
trim(
$data['message'] ?? ''
);

if(
$receiver_id <= 0 ||
$message === ''
){

jsonResponse([
'error'=>'Invalid data'
],400);

}

$stmt =
$conn->prepare(

"INSERT INTO messages
(
sender_id,
receiver_id,
message
)
VALUES
(?,?,?)"

);

$stmt->bind_param(
"iis",
$user_id,
$receiver_id,
$message
);

$stmt->execute();

jsonResponse([
'success'=>true
]);