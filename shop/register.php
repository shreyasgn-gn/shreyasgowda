<?php
session_start();
include 'db.php';

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim(htmlspecialchars($_POST['name']));
    $email    = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Basic password strength check
    if (strlen($password) < 8 || 
        !preg_match("/[A-Z]/", $password) || 
        !preg_match("/[a-z]/", $password) || 
        !preg_match("/[0-9]/", $password)) {
        $error = "Password must be at least 8 characters long and include uppercase, lowercase, and a number.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $checkName = $conn->prepare("SELECT id FROM users WHERE name = ?");
        $checkName->bind_param("s", $name);
        $checkName->execute();
        $checkName->store_result();

        if ($checkName->num_rows > 0) {
            $error = "Username already taken.";
        } else {
            // Check if email already exists
            $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $checkEmail->store_result();

            if ($checkEmail->num_rows > 0) {
                $error = "Email already registered.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashed);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "🎉 Registration successful! You can now log in.";
                    header("Location: login.php");
                    exit;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
   body {
    font-family: 'Segoe UI', sans-serif;
    background: radial-gradient(circle at top left, #b397aeff, #ffffff);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    animation: fadeIn 0.8s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
}

.container {
    background: linear-gradient(145deg, #ffffff, #f0f4f8);
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 12px 32px rgba(0,0,0,0.12);
    width: 380px;
    transition: transform 0.3s ease;
}

.container:hover {
    transform: translateY(-4px);
}

h2 {
    text-align: center;
    margin-bottom: 24px;
    color: #2c3e50;
    font-size: 26px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

input {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 16px;
    border-radius: 10px;
    border: 1px solid #dfb4b4ff;
    font-size: 15px;
    background-color: #f9fbfc;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input:focus {
    border-color: #3498db;
    box-shadow: 0 0 6px rgba(52, 152, 219, 0.3);
    outline: none;
}

button {
    background: linear-gradient(to right, #8ebedfff, #2980b9);
    color: white;
    font-weight: bold;
    border: none;
    cursor: pointer;
    padding: 12px;
    border-radius: 10px;
    font-size: 16px;
    transition: background 0.3s ease, transform 0.2s ease;
}

button:hover {
    background: linear-gradient(to right, #2980b9, #2471a3);
    transform: scale(1.02);
}

.error-box {
    background-color: #c5b5b5ff;
    color: #86352cff;
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 16px;
    font-weight: bold;
    animation: shake 0.3s ease;
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
}

a {
    color: #3498db;
    text-decoration: none;
    display: block;
    text-align: center;
    font-size: 14px;
    margin-top: 12px;
    transition: color 0.3s ease;
}

a:hover {
    color: #a2c7e0ff;
    text-decoration: underline;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>📝 Register</h2>
        <?php if (!empty($error)): ?>
            <div class="error-box"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="text" name="name" placeholder="Full Name" required autocomplete="name" aria-label="Full Name">
            <input type="email" name="email" placeholder="Email" required autocomplete="email" aria-label="Email">
            <input type="password" name="password" placeholder="Password" required autocomplete="new-password" aria-label="Password">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required autocomplete="new-password" aria-label="Confirm Password">
            <button type="submit">Register</button>
        </form>
        <p><b>Already have an account?</b> <a href="login.php"><b>Login here</b></a></p>
    </div>
</body>
</html>