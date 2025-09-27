<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php';

$id = intval($_POST['id']);
$name = trim($_POST['name']);
$price = floatval($_POST['price']);
$description = trim($_POST['description']);
$image = $_FILES['image'];

if ($image['error'] === UPLOAD_ERR_OK) {
    if (!getimagesize($image['tmp_name'])) {
        die("Invalid image.");
    }

    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = uniqid('veg_', true) . '.' . $ext;
    $target = 'uploads/' . $filename;

    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    move_uploaded_file($image['tmp_name'], $target);

    $stmt = $conn->prepare("UPDATE vegetables SET name=?, price=?, description=?, image_path=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $price, $description, $target, $id);
} else {
    $stmt = $conn->prepare("UPDATE vegetables SET name=?, price=?, description=? WHERE id=?");
    $stmt->bind_param("sdsi", $name, $price, $description, $id);
}

$stmt->execute();
$stmt->close();

header("Location: uploaded_vegetable.php");
exit;
?>