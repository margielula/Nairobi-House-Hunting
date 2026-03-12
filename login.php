<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            if ($user['role'] == 'Admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "User not found!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - House Hunting System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-body">

<div class="login-container">

    <h2>Login</h2>
    
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="Enter Username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter Password" required>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.html">Register here</a></p>
    <p><a href="dashboard.php" style="font-size: 12px;">Go to Dashboard</a></p>

</div>

</body>
</html>
