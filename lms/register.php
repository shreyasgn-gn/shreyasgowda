<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $sql="INSERT INTO users(username, password, email) VALUES ('$username', '$password', '$email')";
    if($conn->query($sql)) {
        header("Location: login.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
        <style>
        body {
    font-family: 'Segoe UI', sans-serif;
    background: url("bglogin.jpg") no-repeat center center fixed;
    background-size: cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
}
form {
        `    
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
    animation: fadeIn 1s ease-in-out;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 2px solid transparent;
    border-radius: 10px;
    box-sizing: border-box;
    background: rgba(255, 255, 255, 0.8);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
input:focus {
    border-color: rgb(171, 75, 209);
    box-shadow: 0 4px 8px rgba(171, 75, 209, 0.3);
    outline: none;
}
        h2 {
    color: rgb(71, 125, 161);
    font-size: 1.5rem;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}


button {
    background: linear-gradient(135deg, rgba(171, 75, 209, 1), rgba(71, 125, 161, 1));
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 1rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(71, 125, 161, 0.5);
}
        .error {
            color: red;
            font-size: 10px;
            font-weight: bold;
            opacity:5;
            margin-top: 10px;
            animation: shake 0.5s ease-in-out 1s forwards;

        }
    </style>
</head>
<body>
    <form id="registerForm" method="post">
        <h2>Create an Account</h2>
        <input type="text" name="username" required placeholder="Username"><br>
        <input type="email" name="email" required placeholder="Email"><br>
        <input type="password" id="password" name="password" required placeholder="Password"><br>
        <input type="password" id="confirmPassword" required placeholder="Confirm Password"><br>
        <span id="errorMessage" class="error"></span><br>
        <button type="submit">Register</button>
    </form>

    <script>
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const errorMessage = document.getElementById('errorMessage');

        // Regular Expression to check for at least one special character, one number, and one letter
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

        if (!password.match(passwordRegex)) {
            event.preventDefault();
            errorMessage.textContent = "Password must be at least 6 characters long, include a letter, a number, and a special character (@$!%*?&)";
        } else if (password !== confirmPassword) {
            event.preventDefault();
            errorMessage.textContent = "Passwords do not match!";
        } else {
            errorMessage.textContent = "";
        }
    });
</script>
<body>
</html>