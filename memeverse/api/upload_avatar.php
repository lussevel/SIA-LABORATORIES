<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isLoggedIn()) {

    jsonResponse([
        'error' => 'Login required'
    ], 401);

}

if (!isset($_FILES['avatar'])) {

    jsonResponse([
        'error' => 'No avatar selected'
    ], 400);

}

$file = $_FILES['avatar'];

$avatarDir =
dirname(__DIR__)
. '/assets/uploads/avatars/';

if (!is_dir($avatarDir)) {

    mkdir(
        $avatarDir,
        0755,
        true
    );

}

$extension =
pathinfo(
    $file['name'],
    PATHINFO_EXTENSION
);

$filename =
'avatar_'
. $_SESSION['user_id']
. '_'
. time()
. '.'
. $extension;

$destination =
$avatarDir
. $filename;

move_uploaded_file(
    $file['tmp_name'],
    $destination
);

$path =
'assets/uploads/avatars/'
. $filename;

$stmt =
$conn->prepare(
"UPDATE users
 SET profile_pic=?
 WHERE id=?"
);

$stmt->bind_param(
"si",
$path,
$_SESSION['user_id']
);

$stmt->execute();

jsonResponse([
'success'=>true,
'avatar_url'=>
BASE_URL.'/'.$path
]);