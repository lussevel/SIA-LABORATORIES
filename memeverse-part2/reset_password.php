<?php

require_once 'includes/header.php';

$token =
$_GET['token']
?? '';

?>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card">

<div class="card-body">

<h3>

Reset Password

</h3>

<form id="resetForm">

<input
type="hidden"
id="token"
value="<?= htmlspecialchars($token) ?>">

<div class="mb-3">

<label>
New Password
</label>

<input
type="password"
id="password"
class="form-control"
required>

</div>

<button
class="btn btn-success">

Reset Password

</button>

</form>

<div
id="message"
class="mt-3">
</div>

</div>

</div>

</div>

</div>

</div>

<script>

document
.getElementById(
'resetForm'
)
.addEventListener(
'submit',
async function(e){

e.preventDefault();

const token =
document
.getElementById(
'token'
)
.value;

const password =
document
.getElementById(
'password'
)
.value;

const response =
await fetch(
'api/reset_password.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({

token,
password

})
}
);

const result =
await response.json();

document
.getElementById(
'message'
)
.innerHTML =
result.message;

});

</script>

<?php
require_once 'includes/footer.php';
?>