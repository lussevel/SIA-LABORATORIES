<?php

require_once '../includes/admin_check.php';

$data =
json_decode(
file_get_contents('php://input'),
true
);

$current =
$data['current_password'] ?? '';

$new =
$data['new_password'] ?? '';

$confirm =
$data['confirm_password'] ?? '';

if(
empty($current) ||
empty($new) ||
empty($confirm)
){

echo json_encode([
'success'=>false,
'error'=>'All fields are required'
]);

exit;

}

if($new !== $confirm){

echo json_encode([
'success'=>false,
'error'=>'Passwords do not match'
]);

exit;

}

$userId =
$_SESSION['user_id'];

$stmt =
$conn->prepare(

"SELECT password
FROM users
WHERE id=?"

);

$stmt->bind_param(
"i",
$userId
);

$stmt->execute();

$user =
$stmt
->get_result()
->fetch_assoc();

if(
!password_verify(
$current,
$user['password']
)
){

echo json_encode([
'success'=>false,
'error'=>'Current password is incorrect'
]);

exit;

}

$newHash =
password_hash(
$new,
PASSWORD_DEFAULT
);

$stmt =
$conn->prepare(

"UPDATE users
SET password=?
WHERE id=?"

);

$stmt->bind_param(
"si",
$newHash,
$userId
);

$stmt->execute();

echo json_encode([
'success'=>true
]);