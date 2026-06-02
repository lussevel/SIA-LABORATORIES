<?php

require_once dirname(__DIR__) .
'/includes/config.php';

require_once dirname(__DIR__) .
'/includes/functions.php';

$data =
json_decode(
file_get_contents(
'php://input'
),
true
);

$token =
$data['token']
?? '';

$password =
$data['password']
?? '';

$stmt =
$conn->prepare(

"SELECT *
FROM password_resets
WHERE token=?
AND expires_at > NOW()"

);

$stmt->bind_param(
"s",
$token
);

$stmt->execute();

$result =
$stmt->get_result();

if(
$result->num_rows===0
){

jsonResponse([
'message'=>
'Invalid token'
],400);

}

$reset =
$result->fetch_assoc();

$hashed =
password_hash(
$password,
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
$hashed,
$reset['user_id']
);

$stmt->execute();

$conn->query(
"DELETE FROM password_resets
WHERE id=" .
(int)$reset['id']
);

jsonResponse([

'message'=>

'Password changed successfully.'

]);