<?php

require_once 'includes/admin_check.php';

$comments =
$conn->query(

"SELECT
c.id,
c.content,
c.created_at,
u.username,
p.id AS post_id
FROM comments c
JOIN users u
ON c.user_id = u.id
JOIN posts p
ON c.post_id = p.id
ORDER BY c.created_at DESC"

);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
Comment Moderation
</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h1 class="mb-4">

Comment Moderation

</h1>

<table
class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>User</th>
<th>Comment</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php while(
$row =
$comments->fetch_assoc()
): ?>

<tr>

<td>
<?= $row['id'] ?>
</td>

<td>
<?= htmlspecialchars(
$row['username']
) ?>
</td>

<td>
<?= htmlspecialchars(
$row['content']
) ?>
</td>

<td>
<?= $row['created_at'] ?>
</td>

<td>

<button
class="btn btn-danger btn-sm delete-comment"
data-id="<?= $row['id'] ?>">

Delete

</button>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

document
.querySelectorAll(
'.delete-comment'
)
.forEach(btn=>{

btn.addEventListener(
'click',
async ()=>{

if(
!confirm(
'Delete this comment?'
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
'api/delete_comment.php',
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

alert(
'Delete failed'
);

}

});

});

</script>

</body>
</html>