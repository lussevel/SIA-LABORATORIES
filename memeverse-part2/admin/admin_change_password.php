<?php

require_once 'includes/admin_check.php';

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>
Admin Change Password
</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2 class="mb-4">

Change Admin Password

</h2>

<div class="card">

<div class="card-body">

<div class="mb-3">

<label>
Current Password
</label>

<input
type="password"
id="current_password"
class="form-control">

</div>

<div class="mb-3">

<label>
New Password
</label>

<input
type="password"
id="new_password"
class="form-control">

</div>

<div class="mb-3">

<label>
Confirm Password
</label>

<input
type="password"
id="confirm_password"
class="form-control">

</div>

<button
class="btn btn-primary"
id="change-password">

Update Password

</button>

</div>

</div>

</div>

<script>

document
.getElementById(
'change-password'
)
.addEventListener(
'click',
async ()=>{

const response =
await fetch(
'api/change_admin_password.php',
{
method:'POST',
headers:{
'Content-Type':
'application/json'
},
body:JSON.stringify({

current_password:
document
.getElementById(
'current_password'
)
.value,

new_password:
document
.getElementById(
'new_password'
)
.value,

confirm_password:
document
.getElementById(
'confirm_password'
)
.value

})
}
);

const result =
await response.json();

if(result.success){

alert(
'Password updated successfully'
);

location.reload();

}else{

alert(
result.error ||
'Update failed'
);

}

});

</script>

</body>
</html>