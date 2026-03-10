<?php
// register.php
include 'db.php'; // include database connection

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // simple password hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name','$email','$hashed_password')";
    if($conn->query($sql) === TRUE){
        echo "Registration successful!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h2>Register</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" name="register" value="Register">
</form>
