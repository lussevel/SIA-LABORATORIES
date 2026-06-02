<?php

require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

require_once 'includes/header.php';
?>

<div class="row justify-content-center">
<div class="col-md-6">

<div class="form-card">

<h3>Create Account</h3>

<div id="registerAlert" class="alert d-none"></div>

<form id="registerForm">

<div class="mb-3">
<label class="form-label">Username</label>
<input
type="text"
class="form-control"
name="username"
required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input
type="email"
class="form-control"
name="email"
required>
</div>

<div class="mb-3">
<label class="form-label">Password</label>
<input
type="password"
class="form-control"
name="password"
required>
</div>

<div class="mb-3">
<label class="form-label">
Confirm Password
</label>

<input
type="password"
class="form-control"
name="confirm_password"
required>
</div>

<button
type="submit"
class="btn btn-primary w-100">
Register
</button>

</form>

</div>
</div>
</div>

<script>

document
.getElementById('registerForm')
.addEventListener(
'submit',
async function(e){

e.preventDefault();

const form = e.target;

const data = {

username: form.username.value,
email: form.email.value,
password: form.password.value,
confirm_password:
form.confirm_password.value

};

const alertDiv =
document.getElementById(
'registerAlert'
);

const response =
await fetch(
'api/register.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify(data)
}
);

const result =
await response.json();

alertDiv.classList.remove('d-none');

if(result.success){

alertDiv.className =
'alert alert-success';

alertDiv.innerHTML =
'Registration Successful';

setTimeout(()=>{
window.location =
'login.php';
},1000);

}else{

alertDiv.className =
'alert alert-danger';

alertDiv.innerHTML =
result.error;

}

});

</script>

<?php
require_once 'includes/footer.php';
?>