<?php

$notificationCount = 0;

if(isLoggedIn()){

$notificationCount =
$conn->query(

"SELECT COUNT(*) total
FROM notifications
WHERE user_id=" .
(int)$_SESSION['user_id']

)->fetch_assoc()['total'];

}
?>


<div class="sidebar">

<h4 class="mb-4">
MemeVerse
</h4>

<a href="<?= BASE_URL ?>">
🏠 Home
</a>

<a href="<?= BASE_URL ?>/profile.php">
👤 Profile
</a>

<a href="<?= BASE_URL ?>/messages.php">
💬 Messages
</a>

<a href="<?= BASE_URL ?>/notifications.php">

🔔 Notifications

<?php if($notificationCount > 0): ?>

<span
class="notification-badge">

<?= $notificationCount ?>

</span>

<?php endif; ?>

</a>

<hr>

<h6>
Categories
</h6>

<a href="<?= BASE_URL ?>/category.php?slug=funny">
😂 Funny
</a>

<a href="<?= BASE_URL ?>/category.php?slug=animals">
🐶 Animals
</a>

<a href="<?= BASE_URL ?>/category.php?slug=music">
🎵 Music
</a>

<a href="<?= BASE_URL ?>/category.php?slug=games">
🎮 Games
</a>

<a href="<?= BASE_URL ?>/category.php?slug=movie">
🎬 Movie
</a>

<a href="<?= BASE_URL ?>/category.php?slug=tv">
📺 TV
</a>

<a href="<?= BASE_URL ?>/category.php?slug=sport">
⚽ Sport
</a>

<a href="<?= BASE_URL ?>/category.php?slug=foods">
🍔 Foods
</a>

<a href="<?= BASE_URL ?>/category.php?slug=travel">
✈️ Travel
</a>

<hr>

<a href="<?= BASE_URL ?>/upload.php">
📤 Upload Meme
</a>

<a href="<?= BASE_URL ?>/logout.php">
🚪 Logout
</a>

</div>