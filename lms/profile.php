<?php
// profile.php
session_start();
include("config.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $email = $_POST['email'];
    $password = $_POST['password'];

    $updateSql = "UPDATE users SET  email='$email', password='$password' WHERE username='$username'";
    if ($conn->query($updateSql)) {
       
        $message = "Profile updated successfully!";
    } else {
        $error = "Failed to update profile.";
    }
}

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="styleprofile.css">
</head>
<body>
    <div class="profile-container">
        <h2>My Profile</h2>
        <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="post">
           

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>

            <label>Password:</label>
            <input type="password" name="password" value="<?php echo $user['password']; ?>" required><br>

            <button type="submit">Update Profile</button>
            
</form>
        <button class="work-button" onclick="location.href='yourworks.php'">Your Works</button>
        <a href="login.php">Logout</a>

    </div>
   

    
</body>
</html>
