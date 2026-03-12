<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'Member')";

    if ($conn->query($sql) === TRUE) {
        echo "Account created successfully!";
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $conn->error; // <-- this will show why it failed
    }

    $conn->close();
}
?>
