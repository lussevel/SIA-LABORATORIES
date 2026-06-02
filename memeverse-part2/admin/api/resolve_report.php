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

"UPDATE reports
SET status='resolved'
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