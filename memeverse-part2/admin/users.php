<?php

require_once 'includes/admin_check.php';

$users = $conn->query(
"SELECT
id,
username,
email,
role,
created_at
FROM users
ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Users</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h1 class="mb-4">
Manage Users
</h1>

<table class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Username</th>
<th>Email</th>
<th>Role</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php while($user = $users->fetch_assoc()): ?>

<tr>

<td><?= $user['id'] ?></td>

<td>
<?= htmlspecialchars($user['username']) ?>
</td>

<td>
<?= htmlspecialchars($user['email']) ?>
</td>

<td>
<?= htmlspecialchars($user['role']) ?>
</td>

<td>

<button
class="btn btn-warning btn-sm edit-user"
data-id="<?= $user['id'] ?>"
data-username="<?= htmlspecialchars($user['username']) ?>"
data-email="<?= htmlspecialchars($user['email']) ?>"
data-role="<?= htmlspecialchars($user['role']) ?>">

Edit

</button>

<button
class="btn btn-secondary btn-sm ban-user"
data-id="<?= $user['id'] ?>">

Ban

</button>

<button
class="btn btn-danger btn-sm delete-user"
data-id="<?= $user['id'] ?>">

Delete

</button>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<!-- EDIT USER MODAL -->

<div
class="modal fade"
id="editUserModal"
tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Edit User

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
id="edit-id">

<div class="mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
id="edit-username"
class="form-control">

</div>

<div class="mb-3">

<label class="form-label">

Email

</label>

<input
type="email"
id="edit-email"
class="form-control">

</div>

<div class="mb-3">

<label class="form-label">

Role

</label>

<select
id="edit-role"
class="form-select">

<option value="user">
User
</option>

<option value="admin">
Admin
</option>

</select>

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
id="save-user">

Save Changes

</button>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

// ======================================
// DELETE USER
// ======================================

document
.querySelectorAll('.delete-user')
.forEach(btn => {

btn.addEventListener(
'click',
async () => {

if(!confirm('Delete this user?')){
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
'api/delete_user.php',
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

// ======================================
// BAN USER
// ======================================

document
.querySelectorAll('.ban-user')
.forEach(btn => {

btn.addEventListener(
'click',
async () => {

if(!confirm('Ban this user?')){
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
'api/ban_user.php',
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

alert('Ban failed');

}

});

});

// ======================================
// EDIT USER
// ======================================

const editModalElement =
document.getElementById(
'editUserModal'
);

const editModal =
new bootstrap.Modal(
editModalElement
);

document
.querySelectorAll('.edit-user')
.forEach(btn => {

btn.addEventListener(
'click',
() => {

document
.getElementById('edit-id')
.value =
btn.dataset.id;

document
.getElementById('edit-username')
.value =
btn.dataset.username;

document
.getElementById('edit-email')
.value =
btn.dataset.email;

document
.getElementById('edit-role')
.value =
btn.dataset.role;

editModal.show();

});

});

// ======================================
// SAVE USER
// ======================================

document
.getElementById('save-user')
.addEventListener(
'click',
async () => {

const formData =
new FormData();

formData.append(
'id',
document.getElementById('edit-id').value
);

formData.append(
'username',
document.getElementById('edit-username').value
);

formData.append(
'email',
document.getElementById('edit-email').value
);

formData.append(
'role',
document.getElementById('edit-role').value
);

const response =
await fetch(
'api/edit_user.php',
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

</script>

</body>
</html>