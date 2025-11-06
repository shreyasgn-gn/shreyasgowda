<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "worker");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Get group details
$group_name = $conn->real_escape_string($_POST['group_name']);
$group_address = $conn->real_escape_string($_POST['group_address']);
$group_phone = $conn->real_escape_string($_POST['group_phone']);
$group_work = $conn->real_escape_string($_POST['group_work']);
$member_count = intval($_POST['member_count']);

// Handle group photo
$group_photo = $_FILES['group_photo']['name'];
$group_photo_tmp = $_FILES['group_photo']['tmp_name'];
$group_photo_path = "uploads/" . basename($group_photo);
move_uploaded_file($group_photo_tmp, $group_photo_path);

// Insert into `worker_groups`
$group_stmt = $conn->prepare("INSERT INTO worker_groups (group_name, address, phone, username, photo, group_work) VALUES (?, ?, ?, ?, ?, ?)");
$group_stmt->bind_param("ssssss", $group_name, $group_address, $group_phone, $username, $group_photo, $group_work);
$group_stmt->execute();
$group_id = $group_stmt->insert_id;
$group_stmt->close();

// Insert members into `group_members`
for ($i = 0; $i < $member_count; $i++) {
    $name = $conn->real_escape_string($_POST['member_name'][$i]);
    $experience = intval($_POST['member_experience'][$i]);

    $photo_name = $_FILES['member_photo']['name'][$i];
    $tmp_name = $_FILES['member_photo']['tmp_name'][$i];
    $target = "uploads/" . basename($photo_name);
    move_uploaded_file($tmp_name, $target);

    $member_stmt = $conn->prepare("INSERT INTO group_members (group_id, name, experience, photo) VALUES (?, ?, ?, ?)");
    $member_stmt->bind_param("isis", $group_id, $name, $experience, $photo_name);
    $member_stmt->execute();
    $member_stmt->close();
}

$conn->close();
header("Location: index.php");
exit();
?>
