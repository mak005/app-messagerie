<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username=?"
    );

    $stmt->bind_param("s", $user);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if (password_verify($pass, $row['password'])) {

            $_SESSION['user'] = $user;

            header("Location: index.php");
            exit;
        }
    }

    $error = "Identifiants incorrects";
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card p-4 shadow">

            <h3 class="text-center">Connexion</h3>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input class="form-control mb-3" name="username" placeholder="Nom d'utilisateur" required>
                <input class="form-control mb-3" type="password" name="password" placeholder="Mot de passe" required>

                <button class="btn btn-primary w-100">Se connecter</button>
            </form>



            <a href="register.php">Créer un compte</a>

        </div>
    </div>

</body>

</html>