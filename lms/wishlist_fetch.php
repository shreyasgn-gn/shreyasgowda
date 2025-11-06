<?php
session_start();
$conn = new mysqli("localhost", "root", "", "worker");
$username = $_SESSION['username'] ?? '';

if ($username) {
    $sql = "SELECT w.* FROM workers w 
            JOIN wishlist wl ON w.id = wl.worker_id 
            WHERE wl.username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div style='margin-bottom: 15px; border: 1px solid #ccc; padding: 10px; border-radius: 8px;'>";
            echo "<strong><a href='worker_details.php?id={$row['id']}' style='color:#007bff; text-decoration:none;' target='_blank'>{$row['name']}</a></strong><br>";
            echo "Address: " . htmlspecialchars($row['address']) . "<br>";
            echo "<button onclick='toggleWishlist({$row['id']})'>Remove ❤️</button> ";
            echo "<button onclick=\"window.location.href='worker_details.php?id={$row['id']}'\">View Details</button>";
            echo "</div>";
        }
    } else {
        echo "<p>No items in wishlist.</p>";
    }
} else {
    echo "<p>Please log in to view your wishlist.</p>";
}
?>
