<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isLoggedIn()) {

    jsonResponse([
        'error' => 'Login required'
    ], 401);

}

$data =
json_decode(
file_get_contents('php://input'),
true
);

$following_id =
(int)($data['user_id'] ?? 0);

$follower_id =
$_SESSION['user_id'];

if (
$following_id <= 0 ||
$following_id == $follower_id
) {

jsonResponse([
'error'=>'Invalid user'
],400);

}

$stmt =
$conn->prepare(

"SELECT id
FROM followers
WHERE follower_id=?
AND following_id=?"

);

$stmt->bind_param(
"ii",
$follower_id,
$following_id
);

$stmt->execute();

$result =
$stmt->get_result();

if($result->num_rows > 0){

$stmt =
$conn->prepare(

"DELETE FROM followers
WHERE follower_id=?
AND following_id=?"

);

$stmt->bind_param(
"ii",
$follower_id,
$following_id
);

$stmt->execute();

$status = 'unfollowed';

}else{

$stmt =
$conn->prepare(

"INSERT INTO followers
(
follower_id,
following_id
)
VALUES
(?,?)"

);

$stmt->bind_param(
"ii",
$follower_id,
$following_id
);

$stmt->execute();

$status = 'followed';

$stmt = $conn->prepare(
"INSERT INTO notifications
(
user_id,
type,
message
)
VALUES
(?,?,?)"
);

if(!$stmt){
    die(
        "Prepare Error: "
        . $conn->error
    );
}

$type = 'follow';
$message = 'Someone followed you';

$stmt->bind_param(
"iss",
$following_id,
$type,
$message
);

if(!$stmt->execute()){

    die(
        "Execute Error: "
        . $stmt->error
    );

}

}

jsonResponse([
'success'=>true,
'status'=>$status
]);