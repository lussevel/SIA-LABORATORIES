<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$page =
isset($_GET['page'])
? max(1,(int)$_GET['page'])
: 1;

$limit =
isset($_GET['limit'])
? (int)$_GET['limit']
: 10;

$offset =
($page - 1) * $limit;

$userFilter = '';
$categoryFilter = '';

$params = [];
$types = '';

if(isset($_GET['user_id'])){

    $userFilter =
    " AND p.user_id = ? ";

    $params[] =
    (int)$_GET['user_id'];

    $types .= 'i';
}

if(isset($_GET['category_id'])){

    $categoryFilter =
    " AND p.category_id = ? ";

    $params[] =
    (int)$_GET['category_id'];

    $types .= 'i';
}

$sql = "

SELECT

p.*,

u.username,
u.profile_pic,

c.name AS category_name,
c.slug AS category_slug,

(
SELECT COUNT(*)
FROM votes
WHERE post_id = p.id
AND vote = 1
) AS upvotes,

(
SELECT COUNT(*)
FROM votes
WHERE post_id = p.id
AND vote = -1
) AS downvotes,

(
SELECT COUNT(*)
FROM comments
WHERE post_id = p.id
) AS comment_count

FROM posts p

JOIN users u
ON p.user_id = u.id

JOIN categories c
ON p.category_id = c.id

WHERE 1=1

$userFilter
$categoryFilter

ORDER BY p.created_at DESC

LIMIT ?
OFFSET ?

";

$params[] = $limit;
$params[] = $offset;

$types .= 'ii';

$stmt =
$conn->prepare($sql);

$stmt->bind_param(
$types,
...$params
);

$stmt->execute();

$result =
$stmt->get_result();

$posts = [];

while(
$row =
$result->fetch_assoc()
){

$posts[] = [

'id' =>
$row['id'],

'title' =>
$row['title'],

'description' =>
$row['description'],

'image_path' =>
BASE_URL . '/'
. $row['image_path'],

'created_at' =>
$row['created_at'],

'user' => [

'id' =>
$row['user_id'],

'username' =>
$row['username'],

'profile_pic' =>
$row['profile_pic']
? BASE_URL . '/'
. $row['profile_pic']
: null

],

'category' => [

'name' =>
$row['category_name'],

'slug' =>
$row['category_slug']

],

'upvotes' =>
(int)$row['upvotes'],

'downvotes' =>
(int)$row['downvotes'],

'comments' =>
(int)$row['comment_count']

];

}

$count =
$conn->query(
"SELECT COUNT(*) total
FROM posts"
)->fetch_assoc();

$totalPosts =
$count['total'];

$totalPages =
ceil(
$totalPosts / $limit
);

jsonResponse([

'posts' =>
$posts,

'pagination' => [

'current_page' =>
$page,

'total_pages' =>
$totalPages,

'total_posts' =>
$totalPosts,

'has_more' =>
$page < $totalPages

]

]);