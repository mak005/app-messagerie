<?php session_start(); require 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inscription</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="card p-4 shadow">

<h3 class="text-center">Créer un compte</h3>

<form method="POST">
<input class="form-control mb-3" name="username" placeholder="Nom utilisateur" required>
<input class="form-control mb-3" type="password" name="password" placeholder="Mot de passe" required>

<button class="btn btn-success w-100">S'inscrire</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $user, $pass);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Compte créé !</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Nom déjà utilisé</div>";
    }
}
?>

<a href="login.php">Déjà un compte ? Se connecter</a>

</div>
</div>

</body>
</html>