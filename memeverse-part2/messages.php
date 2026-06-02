<?php

require_once 'includes/header.php';

?>

<h2 class="mb-4">

Inbox

</h2>

<div id="messages"></div>

<script>

async function loadMessages(){

const response =
await fetch(
'api/messages.php'
);

const data =
await response.json();

const container =
document.getElementById(
'messages'
);

let html='';

data.messages.forEach(m=>{

html +=

`
<div class="card mb-3">

<div class="card-body">

<strong>

${m.username}

</strong>

<br>

${m.message}

<br>

<small>

${m.created_at}

</small>

</div>

</div>
`;

});

container.innerHTML =
html;

}

loadMessages();

</script>

<?php
require_once 'includes/footer.php';
?>