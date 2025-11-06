<!DOCTYPE html>
<html>
<head>
    <title>My Listings</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #83a4d4, #b6fbff);
        }

        .button-container {
            text-align: center;
        }

        .login, .cell-button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 14px 28px;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .login:hover, .cell-button:hover {
            background-color: #0056b3;
            transform: scale(1.07);
        }
    </style>
</head>

<body>
    <div class="button-container">
        <button class="login" onclick="location.href='worker_form.php'">Single Worker</button>
        <button class="cell-button" onclick="location.href='groupedworkers.php'">Grouped Workers</button>
    </div>
</body>
</html>
