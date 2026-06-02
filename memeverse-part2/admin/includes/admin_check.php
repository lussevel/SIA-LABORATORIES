<?php

require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

if (!isLoggedIn()) {

    redirect('../login.php');

}

$stmt = $conn->prepare(
"SELECT role
FROM users
WHERE id=?"
);

$stmt->bind_param(
"i",
$_SESSION['user_id']
);

$stmt->execute();

$user =
$stmt
->get_result()
->fetch_assoc();

if (
!$user ||
$user['role'] !== 'admin'
) {

die('Access Denied');

}