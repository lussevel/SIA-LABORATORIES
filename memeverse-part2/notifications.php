<?php

require_once 'includes/header.php';

?>

<h2 class="mb-4">

Notifications

</h2>

<div id="notifications"></div>

<script>

async function loadNotifications(){

const response =
await fetch(
'api/notifications.php'
);

const data =
await response.json();

const container =
document.getElementById(
'notifications'
);

let html = '';

data.notifications.forEach(n=>{

html +=

`
<div class="card mb-3">

<div class="card-body">

<strong>
${n.type}
</strong>

<br>

${n.message}

<br>

<small>

${n.created_at}

</small>

</div>

</div>
`;

});

container.innerHTML =
html;

}

loadNotifications();

</script>

<?php
require_once 'includes/footer.php';
?>