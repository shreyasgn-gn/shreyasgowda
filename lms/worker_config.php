<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "worker"; // This is for worker uploads

$worker_conn = new mysqli($host, $user, $password, $db);
if ($worker_conn->connect_error) {
    die("Connection failed: " . $worker_conn->connect_error);
}
?>