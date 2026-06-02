<?php

require_once '../includes/admin_check.php';

$name =
trim($_POST['name'] ?? '');

$slug =
trim($_POST['slug'] ?? '');

if(
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

"INSERT INTO categories
(
name,
slug
)
VALUES
(?,?)"

);

$stmt->bind_param(
"ss",
$name,
$slug
);

$stmt->execute();

echo json_encode([
'success'=>true
]);