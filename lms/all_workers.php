<?php
$conn = new mysqli("localhost", "root", "", "worker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM workers ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Workers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            margin: 30px 0;
            color: #333;
        }

        .all-workers-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .worker-card {
            width: 230px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .worker-card:hover {
            transform: scale(1.05);
        }

        .worker-image-wrapper {
            width: 100%;
            height: 140px;
            overflow: hidden;
        }

        .worker-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .worker-info {
            padding: 12px;
        }

        .worker-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .worker-work,
        .worker-availability,
        .worker-location {
            font-size: 14px;
            color: #555;
            margin-bottom: 4px;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin: 20px;
        }

        .back-btn a button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .back-btn a button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>All Workers</h2>

<div class="all-workers-container">
    <?php while ($row = $result->fetch_assoc()): ?>
        <a href="worker_details.php?id=<?= $row['id'] ?>" class="worker-card">
            <div class="worker-image-wrapper">
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="worker-image" alt="Worker Image">
            </div>
            <div class="worker-info">
                <div class="worker-name"><?= htmlspecialchars($row['name']) ?></div>
                <div class="worker-work"><?= htmlspecialchars($row['work_type']) ?></div>
                <div class="worker-availability"><?= $row['available'] ? "Available" : "Not Available" ?></div>
                <div class="worker-location"><?= htmlspecialchars($row['address']) ?></div>
            </div>
        </a>
    <?php endwhile; ?>
</div>

<div class="back-btn">
    <a href="index.php">
        <button>Back to Home</button>
    </a>
</div>

</body>
</html>

<?php $conn->close(); ?>
