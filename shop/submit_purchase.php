<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$mobile = $_POST['mobile'] ?? '';
$request_text = $_POST['request_text'] ?? '';
$image_path = '';

if (isset($_FILES['request_image']) && $_FILES['request_image']['error'] === UPLOAD_ERR_OK) {
    $image_name = basename($_FILES['request_image']['name']);
    $target_dir = "uploads/requests/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $target_file = $target_dir . time() . "_" . $image_name;

    if (move_uploaded_file($_FILES['request_image']['tmp_name'], $target_file)) {
        $image_path = $target_file;
    }
}

$stmt = $conn->prepare("INSERT INTO purchase_requests 
    (user_id, user_name, mobile, request_text, image_path, requested_at) 
    VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("issss", $user_id, $user_name, $mobile, $request_text, $image_path);
$stmt->execute();
$stmt->close();

header("Location: profile.php?success=1");
exit;