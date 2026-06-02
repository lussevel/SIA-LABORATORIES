<?php

require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

require_once 'includes/header.php';

$categories =
$conn->query(
"SELECT *
 FROM categories
 ORDER BY name"
);

?>

<div class="row justify-content-center">

<div class="col-md-8">

<div class="form-card">

<h3>Upload Meme</h3>

<div
id="uploadAlert"
class="alert d-none">
</div>

<form
id="uploadForm"
enctype="multipart/form-data">

<div class="mb-3">

<label class="form-label">
Title
</label>

<input
type="text"
name="title"
class="form-control">

</div>

<div class="mb-3">

<label class="form-label">
Description
</label>

<textarea
name="description"
class="form-control"
rows="3"></textarea>

</div>

<div class="mb-3">

<label class="form-label">
Category
</label>

<select
name="category_id"
class="form-select"
required>

<option value="">
Choose Category
</option>

<?php while(
$cat =
$categories->fetch_assoc()
): ?>

<option
value="<?=
$cat['id']
?>">

<?= htmlspecialchars(
$cat['name']
) ?>

</option>

<?php endwhile; ?>

</select>

</div>

<div class="mb-3">

<label class="form-label">
Image
</label>

<input
type="file"
name="image"
accept="image/*"
class="form-control"
required>

</div>

<div
id="previewContainer"
style="display:none;"
class="text-center mb-3">

<img
id="previewImage"
style="
max-width:100%;
max-height:300px;
border-radius:10px;
">

</div>

<button
class="btn btn-primary w-100">

Upload Meme

</button>

</form>

</div>

</div>

</div>





<script>

const imageInput =
document.querySelector(
'input[name="image"]'
);

imageInput.addEventListener(
'change',
function(){

const file =
this.files[0];

if(!file) return;

const reader =
new FileReader();

reader.onload =
function(e){

document
.getElementById(
'previewImage'
)
.src =
e.target.result;

document
.getElementById(
'previewContainer'
)
.style.display =
'block';

};

reader.readAsDataURL(
file
);

});

document
.getElementById(
'uploadForm'
)
.addEventListener(
'submit',
async function(e){

e.preventDefault();

const formData =
new FormData(this);

const alertBox =
document.getElementById(
'uploadAlert'
);

const response =
await fetch(
'api/upload.php',
{
method:'POST',
body:formData
}
);

const result =
await response.json();

alertBox.classList.remove(
'd-none'
);

if(result.success){

alertBox.className =
'alert alert-success';

alertBox.innerHTML =
'Upload Successful';

setTimeout(()=>{

window.location =
'post.php?id='
+
result.post_id;

},1000);

}else{

alertBox.className =
'alert alert-danger';

alertBox.innerHTML =
result.error;

}

});

</script>

<?php
require_once 'includes/footer.php';
?>