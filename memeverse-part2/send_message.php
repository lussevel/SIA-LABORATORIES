<?php

require_once 'includes/header.php';

$receiver_id =
(int)($_GET['id'] ?? 0);

?>

<h2>

Send Message

</h2>

<textarea
id="message"
class="form-control mb-3">
</textarea>

<button
id="sendBtn"
class="btn btn-primary">

Send

</button>

<script>

document
.getElementById(
'sendBtn'
)
.addEventListener(
'click',
async ()=>{

const message =
document
.getElementById(
'message'
)
.value;

const response =
await fetch(
'api/messages.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({

receiver_id:
<?= $receiver_id ?>,

message

})
}
);

const result =
await response.json();

if(result.success){

alert(
'Message Sent'
);

}

});
</script>

<?php
require_once 'includes/footer.php';
?>