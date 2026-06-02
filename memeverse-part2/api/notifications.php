<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isLoggedIn()) {

    jsonResponse([
        'error' => 'Login required'
    ], 401);

}

$user_id =
$_SESSION['user_id'];

$stmt =
$conn->prepare(

"SELECT *
FROM notifications
WHERE user_id=?
ORDER BY created_at DESC"

);

$stmt->bind_param(
"i",
$user_id
);

$stmt->execute();

$result =
$stmt->get_result();

$notifications = [];

while(
$row =
$result->fetch_assoc()
){

    $notifications[] = $row;

}

jsonResponse([
    'notifications' =>
    $notifications
]);