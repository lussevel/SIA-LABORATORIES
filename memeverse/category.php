<?php

require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$slug =
isset($_GET['slug'])
? sanitize($_GET['slug'])
: '';

if(empty($slug)){
    redirect('index.php');
}

$stmt =
$conn->prepare(
"SELECT
id,
name
FROM categories
WHERE slug=?"
);

$stmt->bind_param(
"s",
$slug
);

$stmt->execute();

$result =
$stmt->get_result();

if($result->num_rows===0){
    redirect('index.php');
}

$category =
$result->fetch_assoc();

$categoryId =
$category['id'];

$categoryName =
$category['name'];

?>

<h2 class="mb-4">

Category:
<?= htmlspecialchars($categoryName) ?>

</h2>

<div
id="posts-container"
class="feed-container">
</div>

<div
id="loading-spinner"
class="text-center my-5"
style="display:none;">

<div class="spinner-border">
</div>

</div>

<div
id="end-message"
class="text-center my-4 text-muted"
style="display:none;">

No more posts

</div>





<script>

const categoryId =
<?= $categoryId ?>;

let page = 1;
let loading = false;
let hasMore = true;

const container =
document.getElementById(
'posts-container'
);

async function loadPosts(){

if(
loading ||
!hasMore
){
return;
}

loading = true;

try{

const response =
await fetch(
`api/posts.php?page=${page}&limit=10&category_id=${categoryId}`
);

const data =
await response.json();

if(
data.posts.length > 0
){

renderPosts(
data.posts
);

page++;

hasMore =
data.pagination.has_more;

}else{

hasMore = false;

document
.getElementById(
'end-message'
)
.style.display =
'block';

}

}catch(error){

console.error(
error
);

}

loading = false;

}

function renderPosts(posts){

posts.forEach(post=>{

const card =
document.createElement(
'div'
);

card.className =
'feed-card';

card.innerHTML =

`
<div class="card-header-custom">

<div class="user-avatar">

${
post.user.profile_pic

?

`<img src="${post.user.profile_pic}"
style="
width:100%;
height:100%;
object-fit:cover;">`

:

`<i class="bi bi-person"></i>`
}

</div>

<div class="user-info">

<a
href="profile.php?id=${post.user.id}"
class="user-name">

@${post.user.username}

</a>

</div>

<span class="category-badge">

${post.category.name}

</span>

</div>

<div class="feed-image">

<img
src="${post.image_path}"
alt="Meme">

</div>

<div class="card-description">

${post.description ?? ''}

</div>

<div class="card-actions">

<div class="action-buttons">

<span>

👍 ${post.upvotes}

</span>

<span>

👎 ${post.downvotes}

</span>

<a
href="post.php?id=${post.id}">

💬 ${post.comments}

</a>

</div>

</div>
`;

container.appendChild(
card
);

});

}

window.addEventListener(
'scroll',
()=>{

if(

window.innerHeight +
window.scrollY

>=

document.body.offsetHeight
-500

){

loadPosts();

}

}
);

loadPosts();

</script>

<?php
require_once 'includes/footer.php';
?>