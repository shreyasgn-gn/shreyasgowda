<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php';
$result = $conn->query("SELECT * FROM vegetables ORDER BY uploaded_at DESC");
$vegetables = [];
while ($row = $result->fetch_assoc()) {
    $vegetables[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Uploaded Vegetables</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
            padding: 40px;
            margin: 0;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
        }

        .veg-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr); /* Fixed 6 columns */
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .veg-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

 .veg-card img {
    width: 150px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ccc;
    margin-bottom: 12px;
}

        .veg-card h3 {
            margin: 0;
            font-size: 16px;
            color: #27ae60;
        }

        .veg-card p {
            margin: 4px 0;
            font-size: 14px;
            color: #555;
        }

        .veg-card small {
            color: #888;
            font-size: 12px;
            margin-bottom: 8px;
            display: block;
        }

        .actions {
            display: flex;
            gap: 6px;
            margin-top: 8px;
        }

        .actions a {
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .edit-btn {
            background-color: #f39c12;
            color: white;
        }

        .edit-btn:hover {
            background-color: #e67e22;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .placeholder {
            opacity: 0.5;
        }
        .placeholder img {
            filter: grayscale(100%);
        }

.back-btn {
    position: absolute;
    top: 20px;
    left: 30px;
    background-color: #333837ff;
    color: white;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0,0,0
,0.15);
    transition: background-color 0.3s ease, transform 0.2s ease;
}
    </style>
</head>
<body>

    <h2>📦 Uploaded Vegetables</h2>

    <div class="veg-grid">
        <?php foreach ($vegetables as $veg): ?>
            <div class="veg-card">
                <img src="<?= htmlspecialchars($veg['image_path']) ?>" alt="<?= htmlspecialchars($veg['name']) ?>">
                <h3><?= htmlspecialchars($veg['name']) ?></h3>
                <p><strong>₹<?= htmlspecialchars($veg['price']) ?>/kg</strong></p>
                <p><?= htmlspecialchars($veg['description']) ?></p>
                <small><?= $veg['uploaded_at'] ?></small>
                <div class="actions">
                    <a href="edit_vegitable.php?id=<?= $veg['id'] ?>" class="edit-btn">✏️ Edit</a>
                    <a href="delete_vegetable.php?id=<?= $veg['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this vegetable?')">🗑️ Delete</a>
                </div>
            </div>
        <?php endforeach; ?>

        <?php
        $total = count($vegetables);
        $minCards = 6;
        $extra = $total % 6;
        $fillers = $extra > 0 ? 6 - $extra : 0;
        for ($i = 0; $i < $fillers; $i++):
        ?>
            <div class="veg-card placeholder">
                <img src="placeholder.jpg" alt="Placeholder">
                <h3>Empty Slot</h3>
                <p><strong>₹—/kg</strong></p>
                <p>No description</p>
                <small>—</small>
                <div class="actions">
                    <a href="edit_vegitable.php" class="edit-btn" style="pointer-events: none;">✏️ Edit</a>
                    <a href="delete_vegetable.php" class="delete-btn" style="pointer-events: none;">🗑️ Delete</a>
                </div>
            </div>
        <?php endfor; ?>
    </div>
<a href="admin_dashboard.php" class="back-btn">🔙 Back</a>
</body>
</html>