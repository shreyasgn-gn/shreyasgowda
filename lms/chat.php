<?php
session_start();
$conn = new mysqli("localhost", "root", "", "worker");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$worker_id = intval($_GET['worker_id']);

// Fetch worker info
$worker = $conn->query("SELECT * FROM workers WHERE id = $worker_id")->fetch_assoc();

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $conn->query("INSERT INTO messages (sender, receiver_worker_id, message) VALUES ('$username', $worker_id, '$message')");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat with <?php echo htmlspecialchars($worker['name']); ?></title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 20px; }
        .chat-box { background: white; padding: 20px; max-width: 600px; margin: auto; box-shadow: 0 0 10px #ccc; }
        .messages { height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        .msg { margin-bottom: 10px; }
        .msg.user { text-align: right; color: blue; }
        .msg.worker { text-align: left; color: green; }
    </style>
</head>
<body>
<div class="chat-box">
    <h2>Chat with <?php echo htmlspecialchars($worker['name']); ?></h2>

    <div class="messages" id="chatMessages"></div>

    <form method="POST">
        <input type="text" name="message" placeholder="Type your message..." required style="width: 80%;">
        <button type="submit">Send</button>
    </form>
</div>

<script>
function fetchMessages() {
    fetch('fetch_messages.php?worker_id=<?php echo $worker_id; ?>')
        .then(res => res.text())
        .then(html => document.getElementById('chatMessages').innerHTML = html);
}
setInterval(fetchMessages, 2000);
fetchMessages();
</script>
</body>
</html>
