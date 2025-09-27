<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$request_id = $_POST['request_id'] ?? null;

if ($request_id) {
    $checkStmt = $conn->prepare("SELECT id FROM purchase_requests WHERE id = ? AND user_id = ?");
    $checkStmt->bind_param("ii", $request_id, $user_id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $deleteStmt = $conn->prepare("DELETE FROM purchase_requests WHERE id = ?");
        $deleteStmt->bind_param("i", $request_id);
        $deleteStmt->execute();
    }

    $checkStmt->close();
}

header("Location: profile.php");
exit;