<?php

require_once __DIR__ . '/config.php';

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function sanitize($data)
{
    global $conn;

    return $conn->real_escape_string(
        trim(
            htmlspecialchars($data)
        )
    );
}

function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);

    header('Content-Type: application/json');

    echo json_encode($data);

    exit;
}

function getUserById($id)
{
    global $conn;

    $id = (int)$id;

    $result = $conn->query(
        "SELECT
            id,
            username,
            email,
            nickname,
            bio,
            profile_pic,
            created_at
        FROM users
        WHERE id = $id"
    );

    return $result->fetch_assoc();
}

function isAdmin()
{
    return isset($_SESSION['is_admin'])
        && $_SESSION['is_admin'] == 1;
}

function generateToken($length = 64)
{
    return bin2hex(
        random_bytes(
            $length / 2
        )
    );
}

function formatDate($date)
{
    return date(
        "M d, Y h:i A",
        strtotime($date)
    );
}

function uploadFile(
    $file,
    $directory
)
{
    if (
        !isset($file) ||
        $file['error'] !== 0
    ) {
        return false;
    }

    if (!is_dir($directory)) {

        mkdir(
            $directory,
            0755,
            true
        );

    }

    $extension =
        strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

    $filename =
        uniqid()
        . '.'
        . $extension;

    $destination =
        $directory
        . '/'
        . $filename;

    if (
        move_uploaded_file(
            $file['tmp_name'],
            $destination
        )
    ) {
        return $filename;
    }

    return false;
}