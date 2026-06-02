<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {

    jsonResponse([
        'posts' => []
    ]);

}

$search = "%{$q}%";

$stmt = $conn->prepare(
"SELECT
p.*,
u.username,
c.name AS category_name
FROM posts p
JOIN users u
ON p.user_id = u.id
LEFT JOIN categories c
ON p.category_id = c.id
WHERE
p.title LIKE ?
OR p.description LIKE ?
ORDER BY p.created_at DESC
LIMIT 50"
);

$stmt->bind_param(
"ss",
$search,
$search
);

$stmt->execute();

$result =
$stmt->get_result();

$posts = [];

while(
$row =
$result->fetch_assoc()
){

    $posts[] = $row;

}

jsonResponse([
    'posts' => $posts
]);