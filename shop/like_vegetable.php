<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$veg_id = $_POST['veg_id'] ?? 0;

if ($veg_id > 0) {
    // Check if already liked
    $check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND vegetable_id = ?");
    $check->bind_param("ii", $user_id, $veg_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Remove like
        $remove = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND vegetable_id = ?");
        $remove->bind_param("ii", $user_id, $veg_id);
        $remove->execute();
    } else {
        // Add like
        $insert = $conn->prepare("INSERT INTO likes (user_id, vegetable_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $veg_id);
        $insert->execute();
    }
}

header("Location: user_dashboard.php");
exit;
?>