<!-- all_groups.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Worker Groups</title>
    <link rel="stylesheet" href="styleshomes.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }

        .top-header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo img {
            height: 40px;
        }

        .hero {
            text-align: center;
            padding: 20px;
            background-color:rgb(255, 255, 255);
            color: black;
        }

        .listing-container {
            padding: 20px;
        }

        .group-card-container {
            display: flex;
            flex-direction: row;
            gap: 16px;
            overflow-x: auto;
            padding: 10px;
        }

        .group-card {
            flex: 0 0 auto;
            width: 250px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .group-card:hover {
            transform: scale(1.03);
        }

        .group-image-wrapper {
            width: 100%;
            height: 120px;
            overflow: hidden;
            background: #f0f0f0;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .group-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .group-card:hover .group-image {
            transform: scale(1.05);
        }

        .group-info {
            padding: 10px;
        }

        .group-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .group-address,
        .group-phone {
            font-size: 13px;
            color: #555;
            margin-bottom: 6px;
        }

        .group-info button {
            padding: 8px 12px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .group-info button:hover {
            background-color: #0056b3;
        }

        /* Optional scrollbar styling */
        .group-card-container::-webkit-scrollbar {
            height: 8px;
        }

        .group-card-container::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<header class="top-header">
    <div class="logo">
        <img src="LMS.png" alt="LMS logo" class="logo">
    </div>
</header>

<section class="hero">
    <h1>All Worker Groups</h1>
    <p>Browse all registered worker teams</p>
</section>

<div class="listing-container">
    <?php
    $conn = new mysqli("localhost", "root", "", "worker");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM worker_groups ORDER BY id DESC";
    $result = $conn->query($sql);

    echo "<div class='group-card-container'>";
    while ($row = $result->fetch_assoc()) {
        echo "
        <div class='group-card'>
            <div class='group-image-wrapper'>
                <img src='uploads/{$row['photo']}' alt='Group Photo' class='group-image'>
            </div>
            <div class='group-info'>
                <h3 class='group-name'>{$row['group_name']}</h3>
                <p class='group-address'><strong>Address:</strong> {$row['address']}</p>
                <p class='group-phone'><strong>Phone:</strong> {$row['phone']}</p>
                <button onclick=\"location.href='group_details.php?group_id={$row['id']}'\">View Group</button>
            </div>
        </div>
        ";
    }
    echo "</div>";
    $conn->close();
    ?>
</div>
</body>
</html>
