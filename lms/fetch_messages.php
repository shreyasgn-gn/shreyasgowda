<?php
session_start();
$conn = new mysqli("localhost", "root", "", "worker");

$username = $_SESSION['username'] ?? '';
$worker_id = intval($_GET['worker_id'] ?? 0);

// Auto delete old messages (optional, for cleanup)
$conn->query("DELETE FROM messages WHERE created_at < NOW() - INTERVAL 2 MINUTE");

// Fetch only messages from the last 2 minutes
$sql = "SELECT * FROM messages 
        WHERE receiver_worker_id = ? 
        AND created_at >= NOW() - INTERVAL 2 MINUTE 
        ORDER BY created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $isUser = ($row['sender'] === $username);
    echo "<div class='msg " . ($isUser ? "user" : "worker") . "'>";
    echo "<strong>" . htmlspecialchars($row['sender']) . ":</strong> " . htmlspecialchars($row['message']);
    echo "</div>";
}
?>
