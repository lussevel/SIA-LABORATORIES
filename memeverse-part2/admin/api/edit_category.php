<?php

require_once '../includes/admin_check.php';

$id =
(int)($_POST['id'] ?? 0);

$name =
trim($_POST['name'] ?? '');

$slug =
trim($_POST['slug'] ?? '');

if(
$id <= 0 ||
$name === '' ||
$slug === ''
){

echo json_encode([
'success'=>false
]);

exit;

}

$stmt =
$conn->prepare(

"UPDATE categories
SET
name=?,
slug=?
WHERE id=?"

);

$stmt->bind_param(
"ssi",
$name,
$slug,
$id
);

$stmt->execute();

echo json_encode([
'success'=>true
]);