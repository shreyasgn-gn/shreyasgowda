<?php
require 'db.php';

$term = $_GET['term'] ?? '';
$term = "%$term%";

$sql = "SELECT name FROM vegetables WHERE name LIKE ? ORDER BY name LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $term);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['name'];
}

echo json_encode($suggestions);