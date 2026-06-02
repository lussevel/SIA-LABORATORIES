<?php

require_once 'includes/admin_check.php';

$totalUsers =
$conn->query(
"SELECT COUNT(*) total FROM users"
)->fetch_assoc()['total'];

$totalPosts =
$conn->query(
"SELECT COUNT(*) total FROM posts"
)->fetch_assoc()['total'];

$totalComments =
$conn->query(
"SELECT COUNT(*) total FROM comments"
)->fetch_assoc()['total'];

$totalReports =
$conn->query(
"SELECT COUNT(*) total FROM reports"
)->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html>
<head>

<title>
Admin Dashboard
</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h1 class="mb-4">
Admin Dashboard
</h1>

<div class="row">

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h3>
<?= $totalUsers ?>
</h3>

<p>
Users
</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h3>
<?= $totalPosts ?>
</h3>

<p>
Posts
</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h3>
<?= $totalComments ?>
</h3>

<p>
Comments
</p>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card">

<div class="card-body">

<h3>
<?= $totalReports ?>
</h3>

<p>
Reports
</p>

</div>

</div>

</div>

</div>

<hr>

<div class="mt-4">

<a
href="users.php"
class="btn btn-primary">

Users

</a>

<a
href="admin_change_password.php"
class="btn btn-dark">

Change Password

</a>

<a
href="comments.php"
class="btn btn-warning">

Comments

</a>

<a
href="categories.php"
class="btn btn-success">

Categories

</a>

<a
href="reports.php"
class="btn btn-danger">

Reports

</a>



</div>

</div>

</body>
</html>