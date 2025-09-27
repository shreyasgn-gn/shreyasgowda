<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM vegetables WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$veg = $result->fetch_assoc();
$stmt->close();

if (!$veg) {
    echo "Vegetable not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Vegetable</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form input, form textarea, form button {
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        form button {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #219150;
        }

        .preview {
            text-align: center;
            margin-bottom: 20px;
        }

        .preview img {
            max-width: 200px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Edit Vegetable</h2>

    <div class="preview">
        <img src="<?= htmlspecialchars($veg['image_path']) ?>" alt="Current Image">
    </div>

    <form action="update_vegetable.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $veg['id'] ?>">
        <input type="text" name="name" value="<?= htmlspecialchars($veg['name']) ?>" required>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($veg['price']) ?>" required>
        <textarea name="description" rows="3" required><?= htmlspecialchars($veg['description']) ?></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit">Update</button>
    </form>
</div>

</body>
</html>