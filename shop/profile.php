<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$message = '';

// ✅ Clear responded_at to reset dashboard notification
$updateStmt = $conn->prepare("UPDATE purchase_requests SET responded_at = NULL WHERE user_id = ? AND responded_at IS NOT NULL");
$updateStmt->bind_param("i", $user_id);
$updateStmt->execute();
$updateStmt->close();

// 🔒 Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if ($new_pass === '' || $confirm_pass === '') {
        $message = "⚠️ Please fill in both fields.";
    } elseif ($new_pass !== $confirm_pass) {
        $message = "❌ Passwords do not match.";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Password updated successfully.";
    }
}

// 🧾 Handle feedback from delete_request.php
if (isset($_GET['success']) && $_GET['success'] === 'deleted') {
    $message = "✅ Request deleted successfully.";
} elseif (isset($_GET['error']) && $_GET['error'] === 'packed') {
    $message = "❌ Packed requests cannot be deleted.";
}

// 📦 Fetch user's purchase requests
$stmt = $conn->prepare("SELECT * FROM purchase_requests WHERE user_id = ? ORDER BY requested_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$purchase_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - <?= htmlspecialchars($user_name) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #c07890ff;
            padding: 30px;
        }
        .profile-container {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[readonly] {
            background-color: #eee;
        }
        .btn {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #38867cff;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #21867a;
        }
        .btn-danger {
            background-color: #e63946;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-danger:hover {
            background-color: #c92a3b;
        }
        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
            color: #e63946;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            text-decoration: none;
            color: #2a9d8f;
            font-weight: bold;
        }
        .request-section {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .request-card {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .request-card p {
            margin: 6px 0;
        }
        .request-card a {
            color: #2a9d8f;
            text-decoration: underline;
        }
        .request-card em {
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    
    <div class="profile-container">
        <h2>👤 Profile</h2>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Your Name</label>
            <input type="text" value="<?= htmlspecialchars($user_name) ?>" readonly>

            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password">

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password">

            <button type="submit" class="btn">🔒 Update Password</button>
        </form>
<div class="back-link">
    <a href="logout.php">🔐Logout</a>
</div>
        <div class="back-link">
            <a href="user_dashboard.php">← Back to Dashboard</a>
        </div>
    </div>

    <div class="request-section">
        <h2 style="text-align:center; color:#2a9d8f;">📦 Your Purchase Requests</h2>

        <?php if ($purchase_result->num_rows === 0): ?>
            <p style="text-align:center; color:#777;">You haven't submitted any purchase requests yet.</p>
        <?php else: ?>
            <?php while ($row = $purchase_result->fetch_assoc()): ?>
                <div class="request-card">
                    <p><strong>🕒 Requested At:</strong> <?= htmlspecialchars($row['requested_at']) ?></p>
                    <p><strong>📱 Mobile:</strong> <?= htmlspecialchars($row['mobile']) ?></p>
                    <?php if ($row['request_text']): ?>
                        <p><strong>🖊️ Request:</strong> <?= nl2br(htmlspecialchars($row['request_text'])) ?></p>
                    <?php endif; ?>
                    <?php if ($row['image_path']): ?>
                        <p><strong>📷 Image:</strong> <a href="<?= htmlspecialchars($row['image_path']) ?>" target="_blank">View Uploaded Image</a></p>
                    <?php endif; ?>
                    <p><strong>📌 Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
                    <?php if ($row['admin_message']): ?>
                        <p><strong>📬 Admin Message:</strong> <?= nl2br(htmlspecialchars($row['admin_message'])) ?></p>
                    <?php endif; ?>
                    <?php if ($row['total_amount'] !== null): ?>
                        <p><strong>💰 Total Amount:</strong> ₹<?= number_format($row['total_amount'], 2) ?></p>
                    <?php endif; ?>
                    <?php if ($row['responded_at']): ?>
                        <p><em>Responded on <?= htmlspecialchars($row['responded_at']) ?></em></p>
                    <?php endif; ?>

                    <?php if (strtolower($row['status']) !== 'packed'): ?>
                        <form action="delete_request.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn-danger">🗑️ Delete Request</button>
                        </form>
                    <?php else: ?>
                        <p style="color:#777; font-style:italic;">✅ Packed request cannot be deleted.</p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</body>
</html>