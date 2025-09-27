<?php
session_start();

$admin_username = "shreyas";
$admin_password = "gowda";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_name = $_POST['admin_name'];
    $input_pass = $_POST['admin_password'];

    if ($input_name === $admin_username && $input_pass === $admin_password) {
        $_SESSION['admin'] = true;
        $_SESSION['admin_name'] = $admin_username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
   body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #a47aaa, #90c5d1);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background: white;
    padding: 2em;
    border-radius: 10px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    text-align: center;
    width: 320px;
}

h2 {
    margin-bottom: 1em;
    color: #333;
}

form input {
    width: 100%;
    padding: 0.7em;
    margin: 0.5em 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

form button {
    width: 100%;
    padding: 0.7em;
    background-color: #2196F3;
    color: white;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}

form button:hover {
    background-color: #1976D2;
}

.admin-btn {
    background-color: #4CAF50;
}

.admin-btn:hover {
    background-color: #388E3C;
}

.error {
    color: red;
    margin-bottom: 1em;
}
.error-box {
    background-color: #ffe0e0;
    color: #b30000;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    text-align: center;
    animation: fadeIn 0.5s ease-in;
}

.success-box {
    background-color: #e0ffe0;
    color: #006600;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    text-align: center;
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
</style>
</head>
<body>
    <div class="container">
        <h2>🔐 Admin Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="admin_name" placeholder="Admin Username" required>
            <input type="password" name="admin_password" placeholder="Password" required>
            <button type="submit">Login as Admin</button>
        </form>
    </div>
</body>
</html>