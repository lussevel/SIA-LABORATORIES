<?php

require_once '../includes/admin_check.php';

$id =
(int)($_POST['id'] ?? 0);

$username =
trim($_POST['username'] ?? '');

$email =
trim($_POST['email'] ?? '');

$role =
trim($_POST['role'] ?? 'user');

if(
$id <= 0 ||
$username === '' ||
$email === ''
){

echo json_encode([
'success'=>false
]);

exit;

}

$stmt =
$conn->prepare(

"UPDATE users
SET
username=?,
email=?,
role=?
WHERE id=?"

);

$stmt->bind_param(
"sssi",
$username,
$email,
$role,
$id
);

$stmt->execute();

echo json_encode([
'success'=>true
]);