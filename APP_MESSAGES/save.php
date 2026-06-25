<?php
session_start();

require 'db.php';

$name = $_SESSION['user'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO messages (name, message) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $message);
$stmt->execute();

header("Location: index.php?success=1");
exit;