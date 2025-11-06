<?php
session_start(); // Start the session

$host = "localhost";
$user = "root";
$pass = "";
$db = "worker";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get logged-in username
$username = $_SESSION['username'];

$name = $_POST['name'];
$work_type = $_POST['work_type'];
$available = isset($_POST['available']) ? 1 : 0;
$address = $_POST['address'];
$mobile = $_POST['mobile'];

$image = $_FILES['image']['name'];
$target = "uploads/" . basename($image);
move_uploaded_file($_FILES['image']['tmp_name'], $target);

$sql = "INSERT INTO workers (name, work_type, available, address, mobile, image, username)
        VALUES ('$name', '$work_type', $available, '$address', '$mobile', '$image', '$username')";

if ($conn->query($sql) === TRUE) {
  header("Location: index.php");
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
