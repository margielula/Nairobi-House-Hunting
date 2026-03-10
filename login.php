<?php
include 'db.php';

if(isset($_POST['login'])){

$email=$_POST['email'];
$password=$_POST['password'];

$query="SELECT * FROM users WHERE email='$email'";
$result=mysqli_query($conn,$query);

$user=mysqli_fetch_assoc($result);

if($user && password_verify($password,$user['password'])){

if($user['role']=="agent"){
header("Location: agent/dashboard.php");
}

if($user['role']=="seeker"){
header("Location: seeker/dashboard.php");
}

}else{
echo "Invalid login";
}

}
?>

<form method="POST">

<input type="email" name="email" placeholder="Email"><br>
<input type="password" name="password" placeholder="Password"><br>

<button name="login">Login</button>

</form>
