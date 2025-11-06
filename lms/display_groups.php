<!--display groups-->
<?php
$conn = new mysqli("localhost", "root", "", "worker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM worker_groups ORDER BY id DESC LIMIT 5"; // Show latest 5 groups
$results = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Latest Worker Groups</title>
    <link rel="stylesheet" href="styleshomes.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        h2 {
            text-align: center;
            margin: 30px 0 10px;
        }

        .group-horizontal-scroll {
            display: flex;
            overflow-x: auto;
            gap: 20px;
            padding: 20px;
            scroll-behavior: smooth;
        }

        .group-horizontal-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .group-horizontal-scroll::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }

        .group-card {
            flex: 0 0 auto;
            width: 250px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .group-card:hover {
            transform: scale(1.05);
        }

        .group-image-wrapper {
            width: 100%;
            height: 160px;
            overflow: hidden;
        }

        .group-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .group-info {
            padding: 15px;
        }

        .group-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .group-address, .group-phone, .group-work {
            font-size: 14px;
            margin: 5px 0;
            color: #555;
        }

        .view-btn {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .view-btn:hover {
            background-color: #0056b3;
        }

        .view-all {
            text-align: center;
            margin: 20px;
        }

        .view-all a button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .view-all a button:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>

<h2>Latest Worker Groups</h2>

<div class="group-horizontal-scroll">
    <?php while ($row = $results->fetch_assoc()): ?>
        <div class="group-card">
            <div class="group-image-wrapper">
                <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Group Photo" class="group-image">
            </div>
            <div class="group-info">
                <div class="group-name"><?php echo htmlspecialchars($row['group_name']); ?></div>
                <div class="group-work"><strong>Work:</strong> <?php echo htmlspecialchars($row['group_work']); ?></div>
                <div class="group-address"><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></div>
                <div class="group-phone"><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?></div>
                <button class="view-btn" onclick="location.href='group_details.php?group_id=<?php echo $row['id']; ?>'">View Group</button>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<div class="view-all">
    <a href="all_groups.php">
        <button>View All Groups</button>
    </a>
</div>

</body>
</html>

<?php $conn->close(); ?>
