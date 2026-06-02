<?php

require_once 'includes/header.php';

$query =
trim($_GET['q'] ?? '');

?>

<div class="container">

<h2 class="mb-4">

Search Memes

</h2>

<form
method="GET"
action="search.php">

<div class="input-group mb-4">

<input
type="text"
name="q"
class="form-control"
placeholder="Search memes..."
value="<?= htmlspecialchars($query) ?>">

<button
class="btn btn-primary">

Search

</button>

</div>

</form>

<div
id="search-results"
class="row">

</div>

</div>

<script>

const query =
<?= json_encode($query) ?>;

async function loadSearch(){

if(!query){

return;

}

const response =
await fetch(
`api/search.php?q=${encodeURIComponent(query)}`
);

const data =
await response.json();

const container =
document.getElementById(
'search-results'
);

container.innerHTML = '';

if(data.posts.length === 0){

container.innerHTML =

`
<div class="col-12">

<div class="alert alert-warning">

No results found.

</div>

</div>
`;

return;

}

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

<small>

@${post.username}

</small>

<br><br>

<a
href="post.php?id=${post.id}"
class="btn btn-primary btn-sm">

View

</a>

</div>

</div>

</div>
`;

});

}

loadSearch();

</script>

<?php
require_once 'includes/footer.php';
?>