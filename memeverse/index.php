<?php
require_once 'includes/header.php';
?>

<div
class="feed-container"
id="posts-container">
</div>

<div
id="loading-spinner"
class="text-center my-5"
style="display:none;">

<div
class="spinner-border">
</div>

</div>

<div
id="end-message"
class="text-center my-4 text-muted"
style="display:none;">

No more memes

</div>






<script>

let currentPage = 1;

let loading = false;

let hasMore = true;

const postsContainer =
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
`api/posts.php?page=${currentPage}&limit=10`
);

const data =
await response.json();

if(
data.posts.length
){

renderPosts(
data.posts
);

currentPage++;

hasMore =
data.pagination.has_more;

}else{

hasMore=false;

}

}catch(error){

console.error(error);

}

loading=false;

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

`<img src="${post.user.profile_pic}" style="width:100%;height:100%;object-fit:cover;">`

:

`<i class="bi bi-person"></i>`
}

</div>

<div class="user-info">

<a
class="user-name"
href="profile.php?id=${post.user.id}">

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

<button class="action-btn">

👍 ${post.upvotes}

</button>

<button class="action-btn">

👎 ${post.downvotes}

</button>

<a
href="post.php?id=${post.id}"
class="action-btn">

💬 ${post.comments}

</a>

</div>

</div>

`;

postsContainer.appendChild(
card
);

});

}

window.addEventListener(
'scroll',
()=>{

if(

window.innerHeight
+
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