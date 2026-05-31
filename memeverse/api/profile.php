<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

    $user_id =
        isset($_GET['id'])
        ? (int)$_GET['id']
        : ($_SESSION['user_id'] ?? 0);

    $stmt = $conn->prepare(
        "SELECT
            id,
            username,
            nickname,
            bio,
            profile_pic,
            created_at
        FROM users
        WHERE id=?"
    );

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        jsonResponse([
            'error' => 'User not found'
        ], 404);
    }

    $user = $result->fetch_assoc();

    if ($user['profile_pic']) {
        $user['profile_pic'] =
            BASE_URL . '/' . $user['profile_pic'];
    }

    jsonResponse([
        'user' => $user
    ]);
}

if ($method === 'POST') {

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

    $nickname =
        trim($data['nickname'] ?? '');

    $bio =
        trim($data['bio'] ?? '');

    $user_id =
        $_SESSION['user_id'];

    $stmt =
        $conn->prepare(
            "UPDATE users
             SET nickname=?,
                 bio=?
             WHERE id=?"
        );

    $stmt->bind_param(
        "ssi",
        $nickname,
        $bio,
        $user_id
    );

    $stmt->execute();

    jsonResponse([
        'success' => true
    ]);
}