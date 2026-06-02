<?php

require_once __DIR__ . '/error_handlers.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Memeverse</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link
rel="stylesheet"
href="<?= BASE_URL ?>/assets/css/style.css">

</head>

<body>

<?php if(isLoggedIn()): ?>
    <?php require_once __DIR__ . '/sidebar.php'; ?>
<?php endif; ?>

<?php if(!isLoggedIn()): ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

<div class="container">

<a class="navbar-brand"
href="<?= BASE_URL ?>/index.php">
Memeverse
</a>


<form
action="<?= BASE_URL ?>/search.php"
method="GET"
class="d-flex me-3">

<input
type="search"
name="q"
class="form-control me-2"
placeholder="Search memes...">

<button
class="btn btn-outline-light">

Search

</button>

</form>


<div class="ms-auto">

<!-- Always Visible -->
<a
href="<?= BASE_URL ?>/index.php"
class="btn btn-outline-light me-2">
Home
</a>

<a
href="<?= BASE_URL ?>/category.php?slug=funny"
class="btn btn-outline-light me-2">
Funny
</a>

<a
href="<?= BASE_URL ?>/category.php?slug=animals"
class="btn btn-outline-light me-2">
Animals
</a>

<a
href="<?= BASE_URL ?>/category.php?slug=music"
class="btn btn-outline-light me-2">
Music
</a>

<?php if(isLoggedIn()): ?>

<a
href="<?= BASE_URL ?>/upload.php"
class="btn btn-outline-light me-2">
Upload
</a>

<a
href="<?= BASE_URL ?>/profile.php"
class="btn btn-outline-light me-2">
Profile
</a>

<a
href="<?= BASE_URL ?>/logout.php"
class="btn btn-danger">
Logout
</a>

<?php else: ?>

<a
href="<?= BASE_URL ?>/login.php"
class="btn btn-outline-light me-2">
Login
</a>

<a
href="<?= BASE_URL ?>/register.php"
class="btn btn-primary">
Register
</a>

<?php endif; ?>

</div>

</div>

</nav>

</nav>

<?php endif; ?>

<div class="container mt-4 main-content">