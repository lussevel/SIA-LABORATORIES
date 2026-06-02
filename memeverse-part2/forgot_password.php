<?php

require_once 'includes/header.php';

?>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card">

<div class="card-body">

<h3 class="mb-3">

Forgot Password

</h3>

<form id="forgotPasswordForm">

<div class="mb-3">

<label>Email</label>

<input
type="email"
id="email"
class="form-control"
required>

</div>

<button
type="submit"
class="btn btn-primary">

Send Reset Link

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
'forgotPasswordForm'
)
.addEventListener(
'submit',
async function(e){

e.preventDefault();

const email =
document
.getElementById(
'email'
)
.value;

const response =
await fetch(
'api/forgot_password.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({
email
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