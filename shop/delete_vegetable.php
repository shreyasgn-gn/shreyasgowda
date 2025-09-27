<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php';

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM vegetables WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: uploaded_vegetables.php");
exit;
?>