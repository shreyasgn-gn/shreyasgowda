<?php
session_start();
require 'db.php';
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

$id = $_POST['id'];
$status = $_POST['action'];
$message = $_POST['admin_message'];
$amount = $_POST['total_amount'];

$stmt = $conn->prepare("UPDATE purchase_requests 
    SET status = ?, admin_message = ?, total_amount = ?, responded_at = NOW() 
    WHERE id = ?");
$stmt->bind_param("ssdi", $status, $message, $amount, $id);
$stmt->execute();
$stmt->close();

header("Location: purchase_requests.php");