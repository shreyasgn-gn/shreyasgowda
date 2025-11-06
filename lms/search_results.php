<?php
session_start();
include 'worker_config.php'; // assumes $worker_conn connection

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
    $searchTermEscaped = $worker_conn->real_escape_string($searchTerm);

    // Worker search query
    $workerSql = "SELECT * FROM workers WHERE work_type LIKE '%$searchTermEscaped%' OR name LIKE '%$searchTermEscaped%'";
    $workerResult = $worker_conn->query($workerSql);

    // Group search query
    $groupSql = "SELECT * FROM worker_groups 
                 WHERE group_name LIKE '%$searchTermEscaped%' 
                 OR address LIKE '%$searchTermEscaped%' 
                 OR group_work LIKE '%$searchTermEscaped%'";
    $groupResult = $worker_conn->query($groupSql);
} else {
    $workerResult = null;
    $groupResult = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="styleshomes.css">
    <style>
        /* Container styles */
        .result-section {
            margin: 40px 100px;
        }
        .result-section h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        /* Worker card styles */
        .worker-link {
            text-decoration: none;
            color: inherit;
            display: inline-block;
            vertical-align: top;
        }
        .worker-link:hover .worker-item {
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
            transform: scale(1.02);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .worker-item {
            width: 220px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px;
            margin: 10px;
            transition: transform 0.3s ease;
            text-align: center;
        }
        /* Added image style */
        .worker-photo {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        /* Group card styles */
        .group-card {
            width: 250px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 10px;
            padding: 10px;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }
        .group-image-wrapper {
            width: 100%;
            height: 150px;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .group-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header class="top-header">
    <div class="logo">
        <img src="LMS.png" alt="LMS logo" class="logo">
    </div>
    <section class="hero">
        <h1>Search Results</h1>
    </section>
    <div class="actions">
        <div>
            <button class="login" onclick="location.href='profile.php'">Profile</button>
        </div>
    </div>
</header>

<?php if (!$workerResult && !$groupResult): ?>
    <p>Please enter a search term.</p>
<?php else: ?>

    <?php if ($workerResult && $workerResult->num_rows > 0): ?>
        <div class="result-section">
            <h2>Worker Results</h2>
            <div class="listing-container">
                <?php while ($worker = $workerResult->fetch_assoc()): ?>
                    <a href="worker_details.php?id=<?= $worker['id'] ?>" class="worker-link">
                        <div class="worker-item">
                            <!-- Added worker photo here -->
                            <img src="uploads/<?= htmlspecialchars($worker['image']) ?>" alt="Worker Photo" class="worker-photo">
                            <h3><?= htmlspecialchars($worker['name']) ?> - <?= htmlspecialchars($worker['work_type']) ?></h3>
                            <p><?= htmlspecialchars($worker['address']) ?></p>
                            <p><strong>Mobile:</strong> <?= htmlspecialchars($worker['mobile']) ?></p>
                            <p><strong>Availability:</strong> <?= $worker['available'] ? 'Available' : 'Not Available' ?></p>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="result-section">
            <h2>Worker Results</h2>
            <p>No workers found for your search.</p>
        </div>
    <?php endif; ?>

    <?php if ($groupResult && $groupResult->num_rows > 0): ?>
        <div class="result-section">
            <h2>Grouped Worker Results</h2>
            <div class="listing-container">
                <?php while ($group = $groupResult->fetch_assoc()): ?>
                    <div class="group-card">
                        <div class="group-image-wrapper">
                            <img src="uploads/<?= htmlspecialchars($group['photo']) ?>" alt="Group Photo" class="group-image">
                        </div>
                        <div class="group-info">
                            <h3 class="group-name"><?= htmlspecialchars($group['group_name']) ?></h3>
                            <p class="group-work"><strong>Work:</strong> <?= htmlspecialchars($group['group_work']) ?></p>
                            <p class="group-address"><strong>Address:</strong> <?= htmlspecialchars($group['address']) ?></p>
                            <p class="group-phone"><strong>Phone:</strong> <?= htmlspecialchars($group['phone']) ?></p>
                            <button onclick="location.href='group_details.php?group_id=<?= $group['id'] ?>'">View Group</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="result-section">
            <h2>Grouped Worker Results</h2>
            <p>No grouped workers found for your search.</p>
        </div>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>
