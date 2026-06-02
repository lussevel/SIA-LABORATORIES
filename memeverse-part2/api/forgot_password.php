<?php

require_once dirname(__DIR__) .
'/includes/config.php';

require_once dirname(__DIR__) .
'/includes/functions.php';

require_once dirname(__DIR__) .
'/includes/email_helper.php';

$data =
json_decode(
file_get_contents(
'php://input'
),
true
);

$email =
trim(
$data['email']
?? ''
);

$stmt =
$conn->prepare(
"SELECT id
FROM users
WHERE email=?"
);

$stmt->bind_param(
"s",
$email
);

$stmt->execute();

$result =
$stmt->get_result();

if(
$result->num_rows===0
){

jsonResponse([

'message'=>

'If the email exists, a reset link has been sent.'

]);

}

$user =
$result->fetch_assoc();

$token =
generateToken(
64
);

$expires =
date(
'Y-m-d H:i:s',
strtotime('+1 hour')
);

$stmt =
$conn->prepare(

"INSERT INTO password_resets
(
user_id,
token,
expires_at
)
VALUES
(?,?,?)"

);

$stmt->bind_param(
"iss",
$user['id'],
$token,
$expires
);

$stmt->execute();

$link =
BASE_URL .
'/reset_password.php?token=' .
$token;

sendEmail(
$email,
'Password Reset',
$link
);

jsonResponse([

'message'=>

'Reset link generated successfully.'

]);