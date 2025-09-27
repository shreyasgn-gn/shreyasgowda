<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php';

$name = trim($_POST['name']);
$price = floatval($_POST['price']);
$description = trim($_POST['description']);
$image = $_FILES['image'];

if ($image['error'] === UPLOAD_ERR_OK) {
    if (!getimagesize($image['tmp_name'])) {
        header("Location: admin_dashboard.php?error=not_image");
        exit;
    }

    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid('veg_', true) . '.' . $ext;
    $target = 'uploads/' . $filename;

    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    if (move_uploaded_file($image['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO vegetables (name, price, description, image_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdss", $name, $price, $description, $target);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_dashboard.php?success=1");
        exit;
    } else {
        header("Location: admin_dashboard.php?error=upload_failed");
        exit;
    }
} else {
    header("Location: admin_dashboard.php?error=upload_error");
    exit;
}
?>