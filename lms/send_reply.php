<?php
session_start();
include("worker_config.php");

$worker = $_SESSION['username'];
$data = json_decode(file_get_contents("php://input"), true);

$to = $data['to'];
$msg = $data['msg'];

$stmt = $worker_conn->prepare("INSERT INTO messages (sender, receiver, message, seen) VALUES (?, ?, ?, 0)");
$stmt->bind_param("sss", $worker, $to, $msg);
$stmt->execute();
?>
