<?php

require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$user_id =
isset($_GET['id'])
? (int)$_GET['id']
: ($_SESSION['user_id'] ?? 0);

if ($user_id <= 0) {
    redirect('login.php');
}

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
    redirect('index.php');
}

$user = $result->fetch_assoc();

$user['nickname'] =
$user['nickname'] ?? '';

$user['bio'] =
$user['bio'] ?? '';

$user['profile_pic'] =
$user['profile_pic'] ?? '';

$followerCount =
$conn->query(
"SELECT COUNT(*) total
FROM followers
WHERE following_id=" . (int)$user_id
)->fetch_assoc()['total'];

$followingCount =
$conn->query(
"SELECT COUNT(*) total
FROM followers
WHERE follower_id=" . (int)$user_id
)->fetch_assoc()['total'];

$isFollowing = false;

if (
    isLoggedIn() &&
    $user_id != ($_SESSION['user_id'] ?? 0)
) {

    $stmt =
    $conn->prepare(
    "SELECT id
    FROM followers
    WHERE follower_id=?
    AND following_id=?"
    );

    $stmt->bind_param(
    "ii",
    $_SESSION['user_id'],
    $user_id
    );

    $stmt->execute();

    $isFollowing =
    $stmt->get_result()->num_rows > 0;
}
?>

<div class="container">

<div class="row">

<div class="col-md-12">

<div class="card mb-4">

<div class="card-body">

<div class="row align-items-center">

<div class="col-md-3 text-center">

<?php if($user['profile_pic']): ?>

<img
src="<?= BASE_URL . '/' . $user['profile_pic'] ?>"
class="rounded-circle"
style="width:150px;height:150px;object-fit:cover;">

<?php else: ?>

<div
class="rounded-circle bg-secondary d-flex justify-content-center align-items-center mx-auto"
style="width:150px;height:150px;font-size:60px;">

👤

</div>

<?php endif; ?>

</div>

<div class="col-md-9">

<h2>

<?= htmlspecialchars(
$user['nickname'] ?? ''
?: $user['username']
) ?>

</h2>

<p class="text-muted">

@<?= htmlspecialchars($user['username']) ?>

</p>

<p>

<?= nl2br(
htmlspecialchars(
$user['bio'] ?? ''
)
) ?>

</p>

<div class="mb-3">

<strong>Followers:</strong>
<?= $followerCount ?>

|

<strong>Following:</strong>
<?= $followingCount ?>

</div>

<p>

Joined:

<?= date(
'F Y',
strtotime($user['created_at'])
) ?>

</p>

<?php if(
isLoggedIn() &&
$user['id'] != ($_SESSION['user_id'] ?? 0)
): ?>

<button
id="followBtn"
class="btn btn-success">

<a
href="messages.php?user=<?= $user['id'] ?>"
class="btn btn-primary ms-2">

Message
</a>

<?= $isFollowing
? 'Unfollow'
: 'Follow' ?>

</button>

<?php endif; ?>

<?php if(
$user['id']
==
($_SESSION['user_id'] ?? 0)
): ?>

<button
class="btn btn-primary"
data-bs-toggle="modal"
data-bs-target="#editProfileModal">

Edit Profile

</button>

<?php endif; ?>

</div>

</div>

</div>

</div>

</div>

</div>

<h3 class="mb-3">

User Posts

</h3>

<div
id="user-posts"
class="row">
</div>

</div>

<script>

const userId =
<?= $user_id ?>;

async function loadPosts(){

const response =
await fetch(
`api/posts.php?user_id=${userId}&limit=50`
);

const data =
await response.json();

const container =
document.getElementById(
'user-posts'
);

container.innerHTML='';

data.posts.forEach(post=>{

container.innerHTML +=

`
<div class="col-md-4 mb-4">

<div class="card h-100">

<img
src="${post.image_path}"
class="card-img-top"
style="
height:250px;
object-fit:cover;
">

<div class="card-body">

<h5>
${post.title ?? ''}
</h5>

<p>
${post.description ?? ''}
</p>

<a
href="post.php?id=${post.id}"
class="btn btn-sm btn-primary">

View

</a>

</div>

</div>

</div>
`;

});

}

loadPosts();

document
.getElementById(
'saveProfile'
)
?.addEventListener(
'click',
async ()=>{

const nickname =
document.getElementById(
'nickname'
).value;

const bio =
document.getElementById(
'bio'
).value;

const avatar =
document.getElementById(
'avatar'
).files[0];

const response =
await fetch(
'api/profile.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({
nickname,
bio
})
}
);

const result =
await response.json();

if(!result.success){

alert(
'Failed to update profile'
);

return;

}

if(avatar){

const formData =
new FormData();

formData.append(
'avatar',
avatar
);

await fetch(
'api/upload_avatar.php',
{
method:'POST',
body:formData
}
);

}

location.reload();

});

document
.getElementById(
'followBtn'
)
?.addEventListener(
'click',
async function(){

const response =
await fetch(
'api/follow.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({
user_id:userId
})
}
);

const result =
await response.json();

if(result.success){

location.reload();

}

});

</script>


<!-- EDIT PROFILE MODAL -->

<div
class="modal fade"
id="editProfileModal"
tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title text-dark">
Edit Profile
</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body text-dark">

<div class="mb-3">

<label>
Nickname
</label>

<input
type="text"
id="nickname"
class="form-control"
value="<?= htmlspecialchars($user['nickname'] ?? '') ?>">

</div>

<div class="mb-3">

<label>
Bio
</label>

<textarea
id="bio"
class="form-control"
rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

</div>

<div class="mb-3">

<label>
Avatar
</label>

<input
type="file"
id="avatar"
class="form-control">

</div>

</div>

<div class="modal-footer">

<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Close

</button>

<button
type="button"
class="btn btn-primary"
id="saveProfile">

Save Changes

</button>

</div>

</div>

</div>

</div>

<?php
require_once 'includes/footer.php';
?>