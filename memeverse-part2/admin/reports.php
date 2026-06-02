<?php

require_once 'includes/admin_check.php';

$reports =
$conn->query(

"SELECT
r.id,
r.reason,
r.status,
r.created_at,
u.username,
p.id AS post_id
FROM reports r
JOIN users u
ON r.reporter_id = u.id
JOIN posts p
ON r.post_id = p.id
ORDER BY r.created_at DESC"

);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
Reports Management
</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h1 class="mb-4">

Reports Management

</h1>

<table
class="table table-bordered table-striped">

<thead>

<tr>

<th>ID</th>
<th>Reporter</th>
<th>Post ID</th>
<th>Reason</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php while(
$report =
$reports->fetch_assoc()
): ?>

<tr>

<td>
<?= $report['id'] ?>
</td>

<td>
<?= htmlspecialchars(
$report['username']
) ?>
</td>

<td>
<?= $report['post_id'] ?>
</td>

<td>
<?= htmlspecialchars(
$report['reason']
) ?>
</td>

<td>

<?php if(
$report['status']
=== 'resolved'
): ?>

<span class="badge bg-success">

Resolved

</span>

<?php else: ?>

<span class="badge bg-warning text-dark">

Pending

</span>

<?php endif; ?>

</td>

<td>
<?= $report['created_at'] ?>
</td>

<td>

<?php if(
$report['status']
=== 'pending'
): ?>

<button
class="btn btn-primary btn-sm resolve-report"
data-id="<?= $report['id'] ?>">

Resolve

</button>

<?php endif; ?>

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
'.resolve-report'
)
.forEach(btn=>{

btn.addEventListener(
'click',
async ()=>{

const formData =
new FormData();

formData.append(
'id',
btn.dataset.id
);

const response =
await fetch(
'api/resolve_report.php',
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
'Resolve failed'
);

}

});

});

</script>

</body>
</html>