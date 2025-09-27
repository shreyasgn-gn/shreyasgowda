<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$search = $_GET['search'] ?? '';
$searchQuery = "%$search%";

// 🔔 Check for responded purchase requests
$alertMessage = '';
$checkStmt = $conn->prepare("SELECT COUNT(*) FROM purchase_requests WHERE user_id = ? AND responded_at IS NOT NULL");
$checkStmt->bind_param("i", $user_id);
$checkStmt->execute();
$checkStmt->bind_result($respondedCount);
$checkStmt->fetch();
$checkStmt->close();

if ($respondedCount > 0) {
    $alertMessage = "🔔 You have $respondedCount new message" . ($respondedCount > 1 ? "s" : "") . " in your <a href='profile.php' style='color:#856404; font-weight:bold;'>profile</a>.";
}

// 📦 Pagination setup
$limit = 12;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// 🥬 Count total matching vegetables
$countStmt = $conn->prepare("SELECT COUNT(*) FROM vegetables WHERE name LIKE ?");
$countStmt->bind_param("s", $searchQuery);
$countStmt->execute();
$countStmt->bind_result($totalRows);
$countStmt->fetch();
$countStmt->close();

$totalPages = ceil($totalRows / $limit);

// 🥕 Fetch paginated vegetables
$sql = "SELECT v.*, 
               (SELECT COUNT(*) FROM likes WHERE vegetable_id = v.id) AS like_count,
               EXISTS(SELECT 1 FROM likes WHERE user_id = ? AND vegetable_id = v.id) AS user_liked
        FROM vegetables v 
        WHERE v.name LIKE ? 
        ORDER BY uploaded_at DESC
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isii", $user_id, $searchQuery, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome <?= htmlspecialchars($user_name) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eee4e4;
            margin: 0;
            padding: 20px;
   
        }

        h2 {
            text-align: center;
            color: #e9e505ff;
        }

        .alert-box {
            background-color: #fff3cd;
            color: #856404;
            padding: 10px 16px;
            border-radius: 6px;
            margin: 20px auto;
            max-width: 600px;
            border: 1px solid #ffeeba;
            text-align: center;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 20px;
        }

        .action-btn {
            text-decoration: none;
            background-color: #2a9d8f;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 0.95em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-btn:hover {
            background-color: #21867a;
            transform: translateY(-2px);
        }

        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            padding: 6px;
            width: 220px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-bar button {
            padding: 6px 10px;
            border: none;
            background-color: #2a9d8f;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .veg-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .veg-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease;
        }

        .veg-card:hover {
            transform: scale(1.015);
        }

        .veg-card img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            border-bottom: 1px solid #ccc;
        }

        .veg-info {
            padding: 12px;
        }

        .veg-info h3 {
            margin: 0 0 4px;
            font-size: 1.1em;
            font-weight: bold;
        }

        .veg-info p {
            margin: 4px 0;
            font-size: 0.95em;
            color: #333;
        }

        .veg-info small {
            display: block;
            margin-top: 6px;
            font-size: 0.8em;
            color: #777;
        }

        .like-form {
            margin-top: 8px;
        }

        .like-btn {
            background: none;
            border: none;
            font-size: 0.95em;
            cursor: pointer;
            color: #c91b2a;
        }

        .like-count {
            margin-top: 4px;
            font-size: 0.85em;
            color: #999;
            text-align: right;
        }
        .header-strip {
  position: relative;
  background: url('https://wallpaperaccess.com/full/1306571.jpg') no-repeat center center;
  background-size: cover;
  padding: 30px 20px;
  color: black;
  border-radius: 8px;
  margin-bottom: 20px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Optional: Gradient overlay for readability */
.header-strip::before {
  content: "";
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: linear-gradient(to right, rgba(230, 217, 217, 0.5), rgba(207, 184, 184, 0));
  z-index: 0;
  border-radius: 8px;

}

.header-strip > * {
  position: relative;
  z-index: 1;
}
    </style>
</head>
<body>
<div class="header-strip">
<h2><b>🥕 Welcome <?= htmlspecialchars($user_name) ?>! <div>FreshVeg shop</div></h2>
<?php if ($alertMessage): ?>
    <div class="alert-box"><?= $alertMessage ?></div>
<?php endif; ?>

<div class="action-buttons">
    <a href="profile.php" class="action-btn">👤 Profile</a>
    <button class="action-btn" onclick="document.getElementById('purchaseModal').style.display='block'">🛒 Buy</button>
    <a href="about.php" class="action-btn">ℹ️ About Us</a>
</div>


<div class="search-bar">
    <form method="GET">
        <input type="text" name="search" placeholder="Search vegetables..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">🔍 Search</button>
    </form>
</div>
</div>

<?php if ($search !== ''): ?>
    <p style="text-align:center; font-weight:bold; color:<?= $result->num_rows > 0 ? '#2a9d8f' : '#888' ?>;">
        <?= $result->num_rows > 0 ? '✅ Search result found' : '❌ No vegetables found for "' . htmlspecialchars($search) . '"' ?>
    </p>
<?php endif; ?>

<div class="veg-grid">
    <?php if ($result->num_rows === 0): ?>
        <p style="text-align:center; color:#888;">No vegetables found.</p>
    <?php endif; ?>

    <?php while ($veg = $result->fetch_assoc()): ?>
        <div class="veg-card">
            <img src="<?= htmlspecialchars($veg['image_path'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($veg['name']) ?>">
            <div class="veg-info">
                <h3><?= htmlspecialchars($veg['name']) ?></h3>
                <p><strong>₹<?= htmlspecialchars($veg['price']) ?>/kg</strong></p>
                <p><?= htmlspecialchars($veg['description']) ?></p>
                <small>Updated on <?= htmlspecialchars($veg['uploaded_at']) ?></small>

                <form class="like-form" action="like_vegetable.php" method="POST">
                    <input type="hidden" name="veg_id" value="<?= $veg['id'] ?>">
                    <button type="submit" class="like-btn" title="Click to like/unlike">
                        <?= $veg['user_liked'] ? '❤️' : '🤍' ?> Like
                    </button>
                </form>

                <div class="like-count">❤️ <?= $veg['like_count'] ?> likes</div>
            </div>
        </div>
    <?php endwhile; ?>
</div>


<?php if ($totalPages > 1): ?>
    <div style="text-align:center; margin-top: 20px;">
        <?php if ($page > 1): ?>
            <a href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>" style="margin: 0 6px;">⬅️ Prev</a>
        <?php endif; ?>
        Page <?= $page ?> of <?= $totalPages ?>         
        <?php if ($page < $totalPages): ?>
            <a href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>" style="margin: 0 6px;">Next ➡️</a>
        <?php endif; ?>
    </div>
<?php endif; ?>



<!-- 🛒 Purchase Request Modal -->
<div id="purchaseModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:999;">
  <div style="background:#fff; max-width:500px; margin:80px auto; padding:30px 40px; border-radius:12px; position:relative; box-shadow:0 8px 20px rgba(0,0,0,0.2);">
    <h3 style="margin-top:0; color:#2a9d8f; font-size:1.4em;">📝 Purchase Request</h3>
    <form id="purchaseForm" action="submit_purchase.php" method="POST" enctype="multipart/form-data">
      <label>📱 Mobile Number:</label>
      <input type="tel" name="mobile" id="mobile" required pattern="[0-9]{10}" placeholder="Enter 10-digit mobile"
             style="width:100%; margin-bottom:18px; padding:10px; border-radius:8px; border:1px solid #ccc; font-size:1em;">

      <label>🖊️ Write your request:</label>
      <textarea name="request_text" id="request_text" rows="4" placeholder="e.g. 2kg tomatoes, 1kg onions"
                style="width:100%; margin-bottom:18px; padding:10px; border-radius:8px; border:1px solid #ccc; font-size:1em;"></textarea>

      <label>📷 Upload handwritten image (optional):</label>
      <input type="file" name="request_image" id="request_image" accept="image/*"
             style="margin-bottom:24px; font-size:1em;">

      <button type="submit"
              style="background-color:#2a9d8f; color:white; padding:12px 20px; border:none; border-radius:8px; font-weight:bold; font-size:1em; width:100%;">
        Submit Request
      </button>
    </form>
    <button onclick="document.getElementById('purchaseModal').style.display='none'"
            style="position:absolute; top:12px; right:12px; background:none; border:none; font-size:22px; cursor:pointer;">❌</button>
  </div>
</div>

<script>
document.getElementById('purchaseForm').addEventListener('submit', function(e) {
  const mobile = document.getElementById('mobile').value.trim();
  const text = document.getElementById('request_text').value.trim();
  const imageUploaded = document.getElementById('request_image').files.length > 0;

  if (!/^\d{10}$/.test(mobile)) {
    alert("Please enter a valid 10-digit mobile number.");
    e.preventDefault();
    return;
  }

  if (text === "" && !imageUploaded) {
    alert("Please either write your request or upload an image.");
    e.preventDefault();
    return;
  }
});
</script>
</body>
</html>