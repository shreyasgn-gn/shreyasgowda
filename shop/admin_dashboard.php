<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Vegetable</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #c79eb1ff, #f1f8e9);
            margin: 0;
            padding: 0;
        }

        .top-buttons {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 12px;
            z-index: 1000;
        }

        .view-btn {
            background-color: #218fd8ff;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #2980b9;
        }

        .container {
            max-width: 500px;
            margin: 100px auto;
            background-color: #ffffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
            color: #2c3e50;
            text-align: center;
        }

        form input, form textarea, form button {
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        form input:focus, form textarea:focus {
            border-color: #0bebd4ff;
            outline: none;
        }

        form button {
            background-color: #26a69a;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        form button:hover {
            background-color: #1e8e86;
        }

        .logout {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
        }

        .logout:hover {
            text-decoration: underline;
        }

        .success {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #842029;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 500px) {
            .top-buttons {
                flex-direction: column;
                align-items: flex-end;
            }
        }
              .back-btn {
    position: absolute;
    top: 20px;
    left: 30px;
    background-color: #333837ff;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.back-btn:hover {
    background-color: #92c9c2ff;
    transform: scale(1.05);
}
    </style>
</head>
<body>

    <div class="top-buttons">
        <a href="uploaded_vegetable.php" class="view-btn">📦 View Uploaded</a>
        <a href="purchase_requests.php" class="view-btn">📝 View Purchase Requests</a>
    </div>

    <div class="container">
        <h2>🌿 Upload New Vegetable</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="success">✅ Vegetable uploaded successfully!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="error">
                <?php
                    if ($_GET['error'] === 'not_image') {
                        echo "❌ The uploaded file is not a valid image.";
                    } elseif ($_GET['error'] === 'upload_failed') {
                        echo "❌ Failed to upload image. Please try again.";
                    } elseif ($_GET['error'] === 'upload_error') {
                        echo "❌ Image upload error.";
                    }
                ?>
            </div>
        <?php endif; ?>

        <form action="upload_vegetable.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Vegetable Name" required>
            <input type="number" step="0.01" name="price" placeholder="Price per kg" required>
            <textarea name="description" placeholder="Description (e.g. Fresh, Organic)" rows="3" required></textarea>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>

        <a href="admin_login.php" class="logout">Logout</a>
    </div>
<a href="admin_login.php" class="back-btn">🔙 Back</a>
</body>
</html>