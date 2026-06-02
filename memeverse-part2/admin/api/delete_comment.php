<?php

require_once '../includes/admin_check.php';

$id =
(int)($_POST['id'] ?? 0);

if($id <= 0){

echo json_encode([
'success'=>false
]);

exit;

}

$stmt =
$conn->prepare(

"DELETE FROM comments
WHERE id=?"

);

$stmt->bind_param(
"i",
$id
);

$stmt->execute();

echo json_encode([
'success'=>true
]);

