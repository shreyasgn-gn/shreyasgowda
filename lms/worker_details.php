<?php
session_start();
$conn = new mysqli("localhost", "root", "", "worker");

// Get worker ID from query
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get logged-in username
$username = $_SESSION['username'] ?? 'Guest';

// Fetch worker details
$row = null;
if ($id > 0) {
    $result = $conn->query("SELECT * FROM workers WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
}

// Save or update rating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'])) {
    $rating = intval($_POST['rating']);
    if ($rating >= 1 && $rating <= 5) {
        $check = $conn->query("SELECT * FROM ratings WHERE worker_id = $id AND username = '$username'");
        if ($check->num_rows > 0) {
            $conn->query("UPDATE ratings SET rating = $rating, created_at = NOW() WHERE worker_id = $id AND username = '$username'");
        } else {
            $conn->query("INSERT INTO ratings (worker_id, username, rating) VALUES ($id, '$username', $rating)");
        }
    }
}

// Save comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $conn->real_escape_string($_POST['comment']);
    if (!empty($comment)) {
        $conn->query("INSERT INTO comments (worker_id, username, comment) VALUES ($id, '$username', '$comment')");
    }
}

// Handle wishlist toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_wishlist'])) {
    $wish_check = $conn->query("SELECT * FROM wishlist WHERE worker_id = $id AND username = '$username'");
    if ($wish_check->num_rows > 0) {
        $conn->query("DELETE FROM wishlist WHERE worker_id = $id AND username = '$username'");
    } else {
        $conn->query("INSERT INTO wishlist (worker_id, username) VALUES ($id, '$username')");
    }
    header("Location: worker_details.php?id=$id");
    exit();
}

// Fetch average rating
$avg_rating = 'Not rated yet';
$avg_result = $conn->query("SELECT AVG(rating) AS avg_rating FROM ratings WHERE worker_id = $id");
if ($avg_result && $avg_row = $avg_result->fetch_assoc()) {
    $avg_rating = $avg_row['avg_rating'] ? round($avg_row['avg_rating'], 1) : 'Not rated yet';
}

// Fetch comments
$comments_result = $conn->query("SELECT * FROM comments WHERE worker_id = $id ORDER BY created_at DESC");

// Fetch user's current rating
$user_rating_result = $conn->query("SELECT rating FROM ratings WHERE worker_id = $id AND username = '$username'");
$user_rating = ($user_rating_result->num_rows > 0) ? $user_rating_result->fetch_assoc()['rating'] : null;

// Check if this worker is in the user's wishlist
$wish_result = $conn->query("SELECT * FROM wishlist WHERE worker_id = $id AND username = '$username'");
$is_wishlisted = ($wish_result && $wish_result->num_rows > 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worker Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .header {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            padding: 15px 30px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .back-link {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
        }

        .content {
            max-width: 800px;
            margin: auto;
            padding: 30px 20px;
        }

        .worker-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .worker-image {
            width: 100%;
            max-width: 300px;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .worker-info h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .info-item {
            margin: 10px 0;
            font-size: 18px;
        }

        .chat-btn, .wishlist-btn {
            display: inline-block;
            padding: 10px 25px;
            background: #007bff;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 15px;
            transition: 0.3s;
            font-size: 16px;
        }

        .chat-btn:hover, .wishlist-btn:hover {
            background: #0056b3;
        }

        .rating button {
            font-size: 30px;
            background: none;
            border: none;
            cursor: pointer;
            color: gold;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 10px;
        }

        .comment-box {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .comment-box strong {
            color: #007bff;
        }

        .hidden-comment {
            display: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="index.php" class="back-link">← Back</a>
</div>

<?php if ($row): ?>
    <div class="content">
        <div class="worker-card">
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="worker-image" alt="Worker Image">
            <div class="worker-info">
                <h1><?php echo htmlspecialchars($row['name']); ?></h1>
                <div class="info-item"><strong>Work Type:</strong> <?php echo htmlspecialchars($row['work_type']); ?></div>
                <div class="info-item"><strong>Availability:</strong> <?php echo $row['available'] ? "✅ Available" : "❌ Not Available"; ?></div>
                <div class="info-item"><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></div>
                <div class="info-item"><strong>Mobile:</strong> <?php echo htmlspecialchars($row['mobile']); ?></div>

                <a href="chat.php?worker_id=<?php echo $row['id']; ?>" class="chat-btn">💬 Chat with <?php echo htmlspecialchars($row['name']); ?></a>

                <form method="POST" style="display:inline;">
                    <button type="submit" name="toggle_wishlist" class="wishlist-btn">
                        <?php echo $is_wishlisted ? "❤️ Wishlisted" : "🤍 Add to Wishlist"; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Rating and Comments -->
    <div class="content">
        <h3>⭐ Average Rating: <?php echo $avg_rating; ?> / 5</h3>
        <?php if ($user_rating): ?>
            <p>You rated this worker: <strong><?php echo $user_rating; ?> / 5</strong></p>
        <?php endif; ?>

        <form method="POST">
            <label>Rate this worker:</label><br>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <button name="rating" value="<?php echo $i; ?>">
                    <?php echo ($user_rating == $i) ? "⭐" : "☆"; ?>
                </button>
            <?php endfor; ?>
        </form>

        <hr>

        <form method="POST">
            <label><strong>Leave a comment:</strong></label><br>
            <textarea name="comment" rows="3" required></textarea><br>
            <button type="submit">Post Comment</button>
        </form>

        <h3 style="margin-top:30px;">💬 Comments</h3>
        <div id="comment-container">
            <?php
            $comment_count = 0;
            while ($comment = $comments_result->fetch_assoc()):
                $comment_count++;
                $hidden_class = ($comment_count > 5) ? 'hidden-comment' : '';
            ?>
                <div class="comment-box <?php echo $hidden_class; ?>">
                    <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                    <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                    <small><?php echo date("d M Y, h:i A", strtotime($comment['created_at'])); ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <?php if ($comment_count > 5): ?>
            <div style="text-align:center; margin-top:10px;">
                <button id="toggle-comments" onclick="toggleComments()">Show More</button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        let expanded = false;
        function toggleComments() {
            const hidden = document.querySelectorAll('.hidden-comment');
            hidden.forEach(div => div.style.display = expanded ? 'none' : 'block');
            document.getElementById('toggle-comments').innerText = expanded ? 'Show More' : 'Show Less';
            expanded = !expanded;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.hidden-comment').forEach(div => div.style.display = 'none');
        });
    </script>

<?php endif; ?>

</body>
</html>
