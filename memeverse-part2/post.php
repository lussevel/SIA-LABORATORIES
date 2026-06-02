<?php

require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$post_id =
(int)($_GET['id'] ?? 0);

if($post_id <= 0){
    redirect('index.php');
}
?>

<div
id="post-container"
class="feed-container">
</div>

<div
id="comments-section"
class="mt-4">
</div>




<?php if(isLoggedIn()): ?>

<div class="form-card mt-4">

<h5>Add Comment</h5>

<form id="commentForm">

<textarea
class="form-control mb-2"
rows="3"
required>
</textarea>

<button
class="btn btn-primary">

Comment

</button>

</form>

</div>

<?php endif; ?>




<?php if(isLoggedIn()): ?>

<div class="form-card mt-4">

<h5>Add Comment</h5>

<form id="commentForm">

<textarea
class="form-control mb-2"
rows="3"
required>
</textarea>

<button
class="btn btn-primary">

Comment

</button>

</form>

</div>

<?php endif; ?>




<script>

const postId =
<?= $post_id ?>;

let currentVote = 0;

async function loadPost(){

const response =
await fetch(
`api/post.php?id=${postId}`
);

const data =
await response.json();

renderPost(
data.post
);

}

function renderPost(post){

document
.getElementById(
'post-container'
)
.innerHTML =

`
<div class="feed-card">

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

<button
id="upvoteBtn"
class="btn btn-success">

👍
<span id="upvoteCount">

${post.upvotes}

</span>

</button>

<button
id="downvoteBtn"
class="btn btn-danger">

👎
<span id="downvoteCount">

${post.downvotes}

</span>

</button>

</div>

</div>
`;

document
.getElementById(
'upvoteBtn'
)
.addEventListener(
'click',
()=>vote(1)
);

document
.getElementById(
'downvoteBtn'
)
.addEventListener(
'click',
()=>vote(-1)
);

}

async function vote(value){

const response =
await fetch(
'api/vote.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({

post_id:
postId,

vote:value

})
}
);

const result =
await response.json();

if(result.success){

document
.getElementById(
'upvoteCount'
)
.textContent =
result.upvotes;

document
.getElementById(
'downvoteCount'
)
.textContent =
result.downvotes;

}

}



async function loadComments(){

const response =
await fetch(
`api/comments.php?post_id=${postId}`
);

const data =
await response.json();

const container =
document.getElementById(
'comments-section'
);

let html='';

data.comments.forEach(c=>{

html +=

`
<div
class="feed-card p-3 mb-2">

<strong>

@${c.username}

</strong>

<p class="mt-2">

${c.content}

</p>

<small>

${c.created_at}

</small>

</div>
`;

});

container.innerHTML =
html;

}

document
.getElementById(
'commentForm'
)
?.addEventListener(
'submit',
async function(e){

e.preventDefault();

const textarea =
this.querySelector(
'textarea'
);

const content =
textarea.value;

const response =
await fetch(
'api/comments.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({

post_id:
postId,

content:
content

})
}
);

const result =
await response.json();

if(result.success){

textarea.value='';

loadComments();

}

});

loadPost();
loadComments();

</script>

<?php
require_once 'includes/footer.php';
?>