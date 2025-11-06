<?php
session_start();
$conn = new mysqli("localhost", "root", "", "worker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'] ?? '';
$gid = intval($_GET['group_id'] ?? 0);

// Handle wishlist toggle
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_wishlist'])) {
    $check = $conn->query("SELECT * FROM wishlist_group WHERE group_id=$gid AND username='$username'");
    if ($check->num_rows)
        $conn->query("DELETE FROM wishlist_group WHERE group_id=$gid AND username='$username'");
    else
        $conn->query("INSERT INTO wishlist_group (group_id,username) VALUES ($gid,'$username')");
    header("Location: group_details.php?group_id=$gid");
    exit;
}

// Handle rating
if (isset($_POST['rating'])) {
    $r = intval($_POST['rating']);
    $exists = $conn->query("SELECT * FROM ratings_group WHERE group_id=$gid AND username='$username'");
    if ($exists->num_rows)
        $conn->query("UPDATE ratings_group SET rating=$r, created_at=NOW() WHERE group_id=$gid AND username='$username'");
    else
        $conn->query("INSERT INTO ratings_group (group_id,username,rating) VALUES ($gid,'$username',$r)");
}

// Handle comment
if (isset($_POST['comment'])) {
    $c = $conn->real_escape_string($_POST['comment']);
    if ($c) $conn->query("INSERT INTO comments_group (group_id,username,comment) VALUES ($gid,'$username','$c')");
}

// Fetch group and stats
$gr = $conn->query("SELECT * FROM worker_groups WHERE id=$gid")->fetch_assoc() ?? die("Group not found");
$avg = $conn->query("SELECT AVG(rating) avg FROM ratings_group WHERE group_id=$gid")->fetch_assoc()['avg'];
$avg = $avg ? round($avg, 1) : 'Not rated';
$wish = $conn->query("SELECT COUNT(*) c, SUM(username='$username') me FROM wishlist_group WHERE group_id=$gid")->fetch_assoc();
$comments = $conn->query("SELECT * FROM comments_group WHERE group_id=$gid ORDER BY created_at DESC");
$myRating = $conn->query("SELECT rating FROM ratings_group WHERE group_id=$gid AND username='$username'")->fetch_assoc()['rating'] ?? null;

// ✅ Fetch group members from group_members table
$members = $conn->query("SELECT * FROM group_members WHERE group_id=$gid");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Group Details</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        a {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        img {
            max-width: 100%;
            border-radius: 10px;
            margin: 15px 0;
        }
        p {
            margin: 8px 0;
            font-size: 16px;
        }
        .rating-stars button {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: gold;
        }
        textarea {
            width: 100%;
            height: 80px;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .comment {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #007bff;
            margin: 15px 0;
            border-radius: 8px;
        }
        .comment strong {
            color: #007bff;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .wishlist-btn {
            background-color: #ff4d6d;
            margin-bottom: 15px;
        }
        .wishlist-btn:hover {
            background-color: #d43758;
        }
        .section-title {
            margin-top: 30px;
            font-size: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        /* Members grid */
        .members-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }
        .member-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
            width: 180px;
            text-align: center;
            background: #fafafa;
        }
        .member-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .member-card h3 {
            margin: 10px 0 5px;
            font-size: 18px;
        }
        .member-card p {
            margin: 0;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php">← Back</a>
    <h1><?php echo htmlspecialchars($gr['group_name']); ?></h1>
    <p><strong>Work:</strong> <?php echo htmlspecialchars($gr['group_work']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($gr['address']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($gr['phone']); ?></p>
    <img src="uploads/<?php echo htmlspecialchars($gr['photo']); ?>" alt="Group Photo">
    <p><strong>Average Rating:</strong> <?php echo $avg; ?> ⭐</p>

    <!-- Wishlist -->
    <form method="POST">
        <button class="wishlist-btn" name="toggle_wishlist">
            <?php echo $wish['me'] ? '❤️ Like' : '🤍 Like' ?>
            (<?php echo $wish['c']; ?>)
        </button>
    </form>

    <!-- Rating -->
    <form method="POST">
        <div class="rating-stars">
            <p>Your Rating:</p>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <button type="submit" name="rating" value="<?php echo $i; ?>">
                    <?php echo ($myRating == $i ? '★' : '☆'); ?>
                </button>
            <?php endfor; ?>
        </div>
    </form>

    <!-- Member List -->
    <h2 class="section-title">Group Members</h2>
    <div class="members-container">
        <?php if ($members && $members->num_rows > 0): ?>
            <?php while($mem = $members->fetch_assoc()): ?>
                <div class="member-card">
                    <img src="uploads/<?php echo htmlspecialchars($mem['photo']); ?>" alt="Member Photo">
                    <h3><?php echo htmlspecialchars($mem['name']); ?></h3>
                    <p>Experience: <?php echo htmlspecialchars($mem['experience']); ?> years</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No members found in this group.</p>
        <?php endif; ?>
    </div>

    <!-- Comment Form -->
    <h2 class="section-title">Leave a Comment</h2>
    <form method="POST">
        <textarea name="comment" placeholder="Write your comment..." required></textarea>
        <button type="submit">Post Comment</button>
    </form>

    <!-- Comments -->
    <h2 class="section-title">Comments</h2>
    <?php while ($com = $comments->fetch_assoc()): ?>
        <div class="comment">
            <strong><?php echo htmlspecialchars($com['username']); ?></strong>
            <p><?php echo nl2br(htmlspecialchars($com['comment'])); ?></p>
            <small><?php echo date("d M Y, H:i", strtotime($com['created_at'])); ?></small>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
