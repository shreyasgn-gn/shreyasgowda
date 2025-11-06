<?php
session_start();
include("config.php");
include("worker_config.php");
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Delete worker
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $worker_conn->query("DELETE FROM workers WHERE id=$id AND username='$username'");
}

// Delete group
if (isset($_GET['delete_group'])) {
    $group_id = intval($_GET['delete_group']);
    $worker_conn->query("DELETE FROM worker_groups WHERE id=$group_id AND username='$username'");
    $worker_conn->query("DELETE FROM group_members WHERE group_id=$group_id");
}

// Update availability
if (isset($_POST['update_availability'])) {
    $id = intval($_POST['worker_id']);
    $available = isset($_POST['available']) ? 1 : 0;
    $worker_conn->query("UPDATE workers SET available=$available WHERE id=$id AND username='$username'");
}

// Fetch data
$result = $worker_conn->query("SELECT * FROM workers WHERE username='$username'");
$group_result = $worker_conn->query("SELECT * FROM worker_groups WHERE username='$username'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, rgb(127, 255, 240), rgb(230, 255, 141));
            margin: 0;
            padding: 40px;
        }

        h3 {
            text-align: center;
            color: #333;
            animation: fadeInDown 1s ease;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin: 20px auto;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.5s ease;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .card img {
            display: block;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin: 0 auto 15px;
        }

        .card p {
            margin: 8px 0;
            color: #444;
        }

        input[type="checkbox"] {
            transform: scale(1.2);
            margin-right: 8px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            margin-left: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            margin: 10px 5px 0;
            display: inline-block;
            color: #0066cc;
            text-decoration: none;
            transition: color 0.2s;
        }

        a:hover {
            color: #003366;
            text-decoration: underline;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-bar {
            background: #f1f1f1;
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px auto;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
        }

        .chat-bar .green-dot {
            height: 12px;
            width: 12px;
            background-color: green;
            border-radius: 50%;
            position: absolute;
            right: 10px;
            top: 10px;
        }

        .chat-window {
            display: none;
            border: 1px solid #ccc;
            padding: 15px;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 10px;
            background: white;
        }
    </style>
</head>
<body>

<h3>Messages</h3>
<div id="message-bar-container"></div>

<div id="chat-window" class="chat-window">
    <div id="chat-content" style="height: 200px; overflow-y: auto; margin-bottom: 10px;"></div>
    <textarea id="reply" rows="3" style="width: 100%;"></textarea><br>
    <button onclick="sendReply()">Send</button>
</div>

<h3>Your Uploaded Workers</h3>
<?php while ($row = $result->fetch_assoc()): ?>
<div class="card">
    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Worker Photo">
    <p><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
    <p><strong>Work Type:</strong> <?php echo htmlspecialchars($row['work_type']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
    <p><strong>Mobile:</strong> <?php echo htmlspecialchars($row['mobile']); ?></p>
    <form method="POST" style="display:inline-block;">
        <input type="hidden" name="worker_id" value="<?php echo $row['id']; ?>">
        <label>
            <input type="checkbox" name="available" <?php if ($row['available']) echo 'checked'; ?>> Available
        </label>
        <button type="submit" name="update_availability">Update</button>
    </form>
    
    <a href="yourworks.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a> |
    <a href="chat.php?worker_id=<?php echo $row['id']; ?>" class="chat-button">Chat with Worker</a>
</div>
<?php endwhile; ?>

<h3>Your Uploaded Worker Groups</h3>
<?php while ($group = $group_result->fetch_assoc()): ?>
<div class="card">
    <img src="uploads/<?php echo htmlspecialchars($group['photo']); ?>" alt="Group Photo">
    <p><strong>Group Name:</strong> <?php echo htmlspecialchars($group['group_name']); ?></p>
    <p><strong>Work Type:</strong> <?php echo htmlspecialchars($group['group_work']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($group['address']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($group['phone']); ?></p>
    <a href="yourworks.php?delete_group=<?php echo $group['id']; ?>" onclick="return confirm('Are you sure you want to delete this group?')">Delete</a>
</div>
<?php endwhile; ?>

<script>
let currentUser = null;

function loadChats() {
    fetch("fetch_chats.php")
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById("message-bar-container");
            container.innerHTML = '';
            data.forEach(user => {
                const bar = document.createElement("div");
                bar.className = 'chat-bar';
                bar.innerText = "Message from: " + user.username;
                bar.onclick = () => openChat(user.username);
                if (!user.seen) {
                    const dot = document.createElement("div");
                    dot.className = 'green-dot';
                    bar.appendChild(dot);
                }
                container.appendChild(bar);
            });
        });
}

function openChat(user) {
    currentUser = user;
    fetch(`fetch_messages.php?user=${user}`)
        .then(res => res.json())
        .then(messages => {
            const content = document.getElementById("chat-content");
            content.innerHTML = messages.map(m => `<div><b>${m.sender}:</b> ${m.message}</div>`).join('');
            document.getElementById("chat-window").style.display = 'block';
        });
}

function sendReply() {
    const msg = document.getElementById("reply").value;
    fetch("send_reply.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({to: currentUser, msg: msg})
    }).then(() => {
        document.getElementById("reply").value = '';
        openChat(currentUser); // refresh messages
    });
}

setInterval(loadChats, 5000);
loadChats();
</script>

</body>
</html>
