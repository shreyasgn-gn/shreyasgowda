<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
    } else {
        $error = "Invalid login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="loginstyles.css">
</head>

<body style="background-image: url('bglogin.jpg') ; background-size:cover; background-repeat: no-repeat;" class="login-body">
    <div  class="form-border-wrapper">
    <form method="post" class="win" style="background-color: #cbc2c2a8;">
        
        <h2>Login </h2>
        <input type="text" name="username" required placeholder="Username"><br>
        <input type="password" name="password" required placeholder="Password"><br>
        <button type="submit" class="butlogin">Login</button>
        <p class="create">Don't have an account? <a href="register.php">Create one</a></p>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
</div>
</body>
</html>
