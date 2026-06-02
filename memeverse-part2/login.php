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

<h3>Login</h3>

<div id="loginAlert" class="alert d-none"></div>

<form id="loginForm">

<div class="mb-3">
<label class="form-label">
Username or Email
</label>

<input
type="text"
class="form-control"
name="login"
required>
</div>

<div class="mb-3">
<label class="form-label">
Password
</label>

<input
type="password"
class="form-control"
name="password"
required>
</div>

<button
type="submit"
class="btn btn-primary w-100">
Login
</button>

</form>

</div>
</div>
</div>

<script>

document
.getElementById('loginForm')
.addEventListener(
'submit',
async function(e){

e.preventDefault();

const form = e.target;

const data = {

login:
form.login.value,

password:
form.password.value

};

const alertDiv =
document.getElementById(
'loginAlert'
);

const response =
await fetch(
'api/login.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:
JSON.stringify(data)
}
);

const result =
await response.json();

alertDiv.classList.remove('d-none');

if(result.success){

alertDiv.className =
'alert alert-success';

alertDiv.innerHTML =
'Login Successful';

setTimeout(()=>{
window.location =
'index.php';
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