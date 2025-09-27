<?php
session_start();
require 'db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$result = $conn->query("SELECT * FROM purchase_requests WHERE visible_to_admin = TRUE ORDER BY requested_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Purchase Requests</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e6cfdbff;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #2a9d8f;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #eeebebff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #e0f7f1;
            color: #333;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        textarea, input[type="number"] {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 0.95em;
            margin-bottom: 8px;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            margin-right: 6px;
        }

        button[name="action"][value="Packed"] {
            background-color: #2a9d8f;
            color: white;
        }

        button[name="action"][value="Rejected"] {
            background-color: #e63946;
            color: white;
        }

        .hide-btn {
            background-color: #999;
            color: white;
        }

        .image-preview {
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .image-preview img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            display: block;
        }

        .no-image {
            color: #999;
            font-style: italic;
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

<h2>📦 Purchase Requests</h2>

<table>
    <tr>
        <th>User</th>
        <th>Mobile</th>
        <th>Request</th>
        <th>Image</th>
        <th>Status</th>
        <th>Respond</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['user_name']) ?></td>
        <td><?= htmlspecialchars($row['mobile']) ?></td>
        <td><?= nl2br(htmlspecialchars($row['request_text'])) ?></td>
        <td style="text-align:center;">
            <?php if ($row['image_path']): ?>
                <a href="<?= htmlspecialchars($row['image_path']) ?>" target="_blank" class="image-preview">
                    <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Request Image">
                </a>
            <?php else: ?>
                <span class="no-image">—</span>
            <?php endif; ?>
        </td>
        <td><strong><?= htmlspecialchars($row['status']) ?></strong></td>
        <td>
            <form action="respond_request.php" method="POST">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <textarea name="admin_message" placeholder="Message to user" required><?= htmlspecialchars($row['admin_message']) ?></textarea>
                <input type="number" step="0.01" name="total_amount" placeholder="Total ₹" value="<?= $row['total_amount'] ?>">
                <button name="action" value="Packed">✅ Packed</button>
                <button name="action" value="Rejected">❌ Reject</button>
            </form>

            <form action="hide_request.php" method="POST" onsubmit="return confirm('Hide this request from admin dashboard?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="hide-btn">🗑️ Hide</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<a href="admin_dashboard.php" class="back-btn">🔙 Back</a>
</body>
</html>