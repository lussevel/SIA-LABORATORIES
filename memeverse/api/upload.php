<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

if (!isLoggedIn()) {
    jsonResponse([
        'error' => 'Login required'
    ], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse([
        'error' => 'Method not allowed'
    ], 405);
}

$category_id =
isset($_POST['category_id'])
? (int)$_POST['category_id']
: 0;

$title =
trim($_POST['title'] ?? '');

$description =
trim($_POST['description'] ?? '');

if ($category_id <= 0) {
    jsonResponse([
        'error' => 'Select category'
    ], 400);
}

if (
    !isset($_FILES['image'])
) {
    jsonResponse([
        'error' => 'No image selected'
    ], 400);
}

$file = $_FILES['image'];

if (
    $file['error'] !==
    UPLOAD_ERR_OK
) {
    jsonResponse([
        'error' => 'Upload failed'
    ], 400);
}

if (
    $file['size']
    > 5 * 1024 * 1024
) {
    jsonResponse([
        'error' => 'Max 5MB only'
    ], 400);
}

$finfo =
finfo_open(
FILEINFO_MIME_TYPE
);

$mime =
finfo_file(
$finfo,
$file['tmp_name']
);

finfo_close($finfo);

$allowed = [
'image/jpeg',
'image/png',
'image/gif'
];

if (
    !in_array(
        $mime,
        $allowed
    )
) {
    jsonResponse([
        'error' =>
        'Only JPG PNG GIF'
    ], 400);
}

$uploadDir =
dirname(__DIR__)
. '/assets/uploads/';

if (
    !is_dir($uploadDir)
) {
    mkdir(
        $uploadDir,
        0755,
        true
    );
}

$extension =
pathinfo(
$file['name'],
PATHINFO_EXTENSION
);

$fileName =
uniqid()
. '_'
. time()
. '.'
. $extension;

$destination =
$uploadDir
. $fileName;

if (
    !move_uploaded_file(
        $file['tmp_name'],
        $destination
    )
) {
    jsonResponse([
        'error' =>
        'Failed to save image'
    ], 500);
}

$imagePath =
'assets/uploads/'
. $fileName;

$user_id =
$_SESSION['user_id'];

$stmt =
$conn->prepare(
"INSERT INTO posts
(
user_id,
category_id,
title,
description,
image_path
)
VALUES
(?,?,?,?,?)"
);

$stmt->bind_param(
"iisss",
$user_id,
$category_id,
$title,
$description,
$imagePath
);

if ($stmt->execute()) {

    jsonResponse([
        'success' => true,
        'post_id' =>
        $stmt->insert_id,
        'message' =>
        'Upload successful'
    ]);

}

jsonResponse([
    'error' =>
    'Database error'
],500);