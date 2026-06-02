<?php

require_once 'includes/admin_check.php';

$categories = $conn->query(
"SELECT *
FROM categories
ORDER BY id ASC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Categories</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h1 class="mb-4">
Manage Categories
</h1>

<button
class="btn btn-success mb-3"
data-bs-toggle="modal"
data-bs-target="#addCategoryModal">

Add Category

</button>

<table class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Name</th>
<th>Slug</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php while($category = $categories->fetch_assoc()): ?>

<tr>

<td><?= $category['id'] ?></td>

<td>
<?= htmlspecialchars($category['name']) ?>
</td>

<td>
<?= htmlspecialchars($category['slug']) ?>
</td>

<td>

<button
class="btn btn-warning btn-sm edit-category"
data-id="<?= $category['id'] ?>"
data-name="<?= htmlspecialchars($category['name']) ?>"
data-slug="<?= htmlspecialchars($category['slug']) ?>">

Edit

</button>

<button
class="btn btn-danger btn-sm delete-category"
data-id="<?= $category['id'] ?>">

Delete

</button>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<!-- ADD CATEGORY MODAL -->

<div
class="modal fade"
id="addCategoryModal"
tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Add Category

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<div class="mb-3">

<label class="form-label">

Name

</label>

<input
type="text"
id="category-name"
class="form-control">

</div>

<div class="mb-3">

<label class="form-label">

Slug

</label>

<input
type="text"
id="category-slug"
class="form-control">

</div>

</div>

<div class="modal-footer">

<button
type="button"
class="btn btn-success"
id="save-category">

Save

</button>

</div>

</div>

</div>

</div>

<!-- EDIT CATEGORY MODAL -->

<div
class="modal fade"
id="editCategoryModal"
tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Edit Category

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<input
type="hidden"
id="edit-category-id">

<div class="mb-3">

<label class="form-label">

Name

</label>

<input
type="text"
id="edit-category-name"
class="form-control">

</div>

<div class="mb-3">

<label class="form-label">

Slug

</label>

<input
type="text"
id="edit-category-slug"
class="form-control">

</div>

</div>

<div class="modal-footer">

<button
type="button"
class="btn btn-primary"
id="update-category">

Update

</button>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// =====================================
// ADD CATEGORY
// =====================================

document
.getElementById('save-category')
.addEventListener(
'click',
async ()=>{

const formData =
new FormData();

formData.append(
'name',
document.getElementById(
'category-name'
).value
);

formData.append(
'slug',
document.getElementById(
'category-slug'
).value
);

const response =
await fetch(
'api/add_category.php',
{
method:'POST',
body:formData
}
);

const result =
await response.json();

if(result.success){

location.reload();

}else{

alert('Add failed');

}

});

// =====================================
// EDIT CATEGORY
// =====================================

const editCategoryModal =
new bootstrap.Modal(
document.getElementById(
'editCategoryModal'
)
);

document
.querySelectorAll('.edit-category')
.forEach(btn=>{

btn.addEventListener(
'click',
()=>{

document.getElementById(
'edit-category-id'
).value =
btn.dataset.id;

document.getElementById(
'edit-category-name'
).value =
btn.dataset.name;

document.getElementById(
'edit-category-slug'
).value =
btn.dataset.slug;

editCategoryModal.show();

});

});

document
.getElementById('update-category')
.addEventListener(
'click',
async ()=>{

const formData =
new FormData();

formData.append(
'id',
document.getElementById(
'edit-category-id'
).value
);

formData.append(
'name',
document.getElementById(
'edit-category-name'
).value
);

formData.append(
'slug',
document.getElementById(
'edit-category-slug'
).value
);

const response =
await fetch(
'api/edit_category.php',
{
method:'POST',
body:formData
}
);

const result =
await response.json();

if(result.success){

location.reload();

}else{

alert('Update failed');

}

});

// =====================================
// DELETE CATEGORY
// =====================================

document
.querySelectorAll('.delete-category')
.forEach(btn=>{

btn.addEventListener(
'click',
async ()=>{

if(
!confirm(
'Delete this category?'
)
){
return;
}

const formData =
new FormData();

formData.append(
'id',
btn.dataset.id
);

const response =
await fetch(
'api/delete_category.php',
{
method:'POST',
body:formData
}
);

const result =
await response.json();

if(result.success){

location.reload();

}else{

alert('Delete failed');

}

});

});

</script>

</body>
</html>