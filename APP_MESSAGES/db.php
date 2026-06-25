<?php
$host = "db";
$user = "root";
$password = "root";
$database = "messages_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erreur connexion DB");
}

$conn->set_charset("utf8mb4");