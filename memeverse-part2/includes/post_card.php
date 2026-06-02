<div class="feed-card">

<div class="card-header-custom">

<div class="user-avatar">

<?php if(!empty($post['profile_pic'])): ?>

<img
src="<?= BASE_URL ?>/<?= $post['profile_pic'] ?>">

<?php else: ?>

👤

<?php endif; ?>

</div>

<div>

<strong>

<?= htmlspecialchars(
$post['username']
) ?>

</strong>

</div>

</div>

<div class="feed-image">

<img
src="<?= BASE_URL ?>/<?= $post['image_path'] ?>"
alt="meme">

</div>

<div class="card-description">

<?= htmlspecialchars(
$post['description']
?? ''
) ?>

</div>

</div>