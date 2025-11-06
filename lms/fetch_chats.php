<?php
session_start();
include("config.php");         // LMS for users
include("worker_config.php");  // messages DB

$worker = $_SESSION['username'];

// Get distinct users who messaged the current worker
$sql = "SELECT sender, MAX(seen) as seen FROM messages WHERE receiver=? GROUP BY sender";
$stmt = $worker_conn->prepare($sql);
$stmt->bind_param("s", $worker);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = ['username' => $row['sender'], 'seen' => $row['seen']];
}
echo json_encode($data);
?>
