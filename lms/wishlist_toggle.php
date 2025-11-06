<?php
session_start();
$conn = new mysqli("localhost", "root", "", "worker");

$username = $_SESSION['username'] ?? '';
$worker_id = intval($_GET['worker_id']);

if ($username && $worker_id) {
    $check = $conn->query("SELECT * FROM wishlist WHERE username = '$username' AND worker_id = $worker_id");
    if ($check->num_rows > 0) {
        $conn->query("DELETE FROM wishlist WHERE username = '$username' AND worker_id = $worker_id");
        echo "Removed from wishlist";
    } else {
        $conn->query("INSERT INTO wishlist (username, worker_id) VALUES ('$username', $worker_id)");
        echo "Added to wishlist";
    }
} else {
    echo "Invalid user or worker.";
}
?>
