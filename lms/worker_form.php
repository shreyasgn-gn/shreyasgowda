<!-- worker_form.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Worker</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #dbe6f6, #c5796d);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background:rgba(251, 251, 251, 0.1);;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 1s ease;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            animation: slideDown 1s ease;
        }

        input[type="text"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        select:focus,
        input[type="file"]:focus {
            border-color: #6c63ff;
            outline: none;
        }

        label {
            display: block;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
            margin-right: 8px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #6c63ff;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #594dd1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    
    <form action="submit_worker.php" method="POST" enctype="multipart/form-data">
    <h2>Add Worker Details</h2>
        <input type="text" name="name" placeholder="Worker Name" required><br><br>
        <select name="work_type" required>
            <option value="">Select Work</option>
            <option value="Plumber">Plumber</option>
            <option value="Electrician">Electrician</option>
            <option value="Carpenter">Carpenter</option>
             <option value="Carpenter">Daily wages</option>
        </select><br><br>
        <input type="text" name="address" placeholder="Address" required><br><br>
        <input type="text" name="mobile" placeholder="Mobile Number" required><br><br>
        <h4>Your Photo</h4>
        <input type="file" name="image" accept="image/*" required><br><br>
        <label><input type="checkbox" name="available"> Available</label><br><br>
        <input type="submit" value="Submit Worker">
    </form>
</body>
</html>
