<?php

require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

$post_id =
(int)($_GET['id'] ?? 0);

if($post_id <= 0){

jsonResponse([
'error'=>'Invalid ID'
],400);

}

$stmt =
$conn->prepare(

"SELECT

p.*,

u.username,
u.profile_pic,

c.name AS category_name,
c.slug AS category_slug

FROM posts p

JOIN users u
ON p.user_id=u.id

JOIN categories c
ON p.category_id=c.id

WHERE p.id=?"

);

$stmt->bind_param(
"i",
$post_id
);

$stmt->execute();

$result =
$stmt->get_result();

if(
$result->num_rows===0
){

jsonResponse([
'error'=>'Post not found'
],404);

}

$post =
$result->fetch_assoc();

$upvotes =
$conn->query(
"SELECT COUNT(*) total
FROM votes
WHERE post_id=$post_id
AND vote=1"
)->fetch_assoc()['total'];

$downvotes =
$conn->query(
"SELECT COUNT(*) total
FROM votes
WHERE post_id=$post_id
AND vote=-1"
)->fetch_assoc()['total'];

jsonResponse([

'post'=>[

'id'=>$post['id'],

'title'=>$post['title'],

'description'=>$post['description'],

'image_path'=>
BASE_URL .
'/'
.
$post['image_path'],

'created_at'=>
$post['created_at'],

'user'=>[

'id'=>
$post['user_id'],

'username'=>
$post['username'],

'profile_pic'=>
$post['profile_pic']
? BASE_URL . '/'
. $post['profile_pic']
: null

],

'category'=>[

'name'=>
$post['category_name'],

'slug'=>
$post['category_slug']

],

'upvotes'=>
(int)$upvotes,

'downvotes'=>
(int)$downvotes

]

]);