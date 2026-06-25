<?php
require 'db.php';
session_start();

$result = $conn->query("SELECT *, TIME_FORMAT(created_at, '%H:%i') as heure FROM messages ORDER BY created_at ASC");

while ($row = $result->fetch_assoc()) {
    $isMine = $row['name'] === $_SESSION['user'];
    include 'message.php';
}
