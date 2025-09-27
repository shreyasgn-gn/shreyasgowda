<?php
ob_start();
session_start();
include 'db.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['user_login'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid request. Please refresh and try again.";
    } else {
        $name = trim(htmlspecialchars($_POST['name']));
        $pass = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $actual_name, $hashed);
            $stmt->fetch();
            if (password_verify($pass, $hashed)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $actual_name;
                $success = "✅ Login successful! Redirecting to dashboard...";
            } else {
                $error = "❌ Incorrect password.";
            }
        } else {
            $error = "❌ User not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <?php if (!empty($success)): ?>
        <meta http-equiv="refresh" content="2;url=user_dashboard.php">
    <?php endif; ?>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('https://wallpaperaccess.com/full/1537315.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            backdrop-filter: none(4px);
        }

        .container {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            width: 340px;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: scale(1.02);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 24px;
        }

        input, button {
            width: 100%;
            padding: 12px;
            margin-bottom: 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: box-shadow 0.2s ease;
        }

        input:focus {
            box-shadow: 0 0 6px rgba(52, 152, 219, 0.6);
            outline: none;
        }

        button {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #219150;
        }

        .error-box {
            background-color: #ffe6e6;
            color: #c0392b;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 12px;
            font-weight: bold;
        }

        .success-box {
            background-color: #e8f8f5;
            color: #2ecc71;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 12px;
            font-weight: bold;
        }

        a {
            color: #3498db;
            text-decoration: none;
            display: block;
            text-align: center;
            font-size: 14px;
            margin-top: 8px;
        }

        a:hover {
            text-decoration: underline;
        }

        .admin-btn {
            background-color: #34495e;
            color: white;
            font-weight: bold;
            border: none;
            padding: 10px;
            border-radius: 6px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .admin-btn:hover {
            background-color: #2c3e50;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>👤 User Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success-box"><?= htmlspecialchars($success) ?></div>
            <script>
                setTimeout(() => {
                    window.location.href = 'user_dashboard.php';
                }, 2000);
            </script>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="text" name="name" placeholder="Your Name" required autocomplete="username" aria-label="Username">
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password" aria-label="Password">
            <button type="submit" name="user_login">Login</button>
        </form>

        <a href="register.php">Don't have an account? Register here</a>
        <hr>
        <form action="admin_login.php" method="GET">
            <button type="submit" class="admin-btn">Login as Admin</button>
        </form>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>