<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
</head>

<body>

    <div class="chat-container">

        <div class="top-bar">
            <div>Connecté : <strong><?= htmlspecialchars($_SESSION['user']) ?></strong></div>
            <h5 class="text-center">Groupe TC2-E</h5>
            <a href="logout.php" class="btn btn-danger btn-sm">
                Déconnexion
            </a>
        </div>

        <div id="messages" hx-get="load_messages.php" hx-trigger="every 3s" hx-swap="innerHTML" class="messages-area">


            <?php
            require 'db.php';

            $result = $conn->query(
                "SELECT *, TIME_FORMAT(created_at, '%H:%i') as heure FROM messages ORDER BY created_at ASC"
            );

            while ($row = $result->fetch_assoc()) {
                $isMine = $row['name'] === $_SESSION['user'];
                include 'message.php';
            }
            ?>
        </div>

        <div class="input-area">
            <form action="save.php" method="POST" class="w-100 d-flex gap-2 align-items-center">
                <button type="button" id="emoji-btn" style="background:none;border:none;font-size:1.4rem;cursor:pointer;">😊</button>
                <div id="emoji-picker-container" style="display:none;position:absolute;bottom:70px;left:10px;z-index:999;"></div>
                <input type="text" id="msg-input" name="message" class="form-control" placeholder="Écrivez un message..." required>
                <button class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>

    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/emoji-picker-element@1/index.js';

        const messagesArea = document.querySelector('.messages-area');
        messagesArea.scrollTop = messagesArea.scrollHeight;

        const input = document.getElementById('msg-input');
        const form = document.querySelector('form');
        const emojiBtn = document.getElementById('emoji-btn');
        const pickerContainer = document.getElementById('emoji-picker-container');

        // Créer le picker
        const picker = document.createElement('emoji-picker');
        pickerContainer.appendChild(picker);

        // Ouvrir/fermer le picker
        emojiBtn.addEventListener('click', () => {
            pickerContainer.style.display =
                pickerContainer.style.display === 'none' ? 'block' : 'none';
        });

        // Insérer l'emoji dans le champ
        picker.addEventListener('emoji-click', event => {
            input.value += event.detail.unicode;
            pickerContainer.style.display = 'none';
            input.focus();
        });

        // Fermer si on clique ailleurs
        document.addEventListener('click', (e) => {
            if (!pickerContainer.contains(e.target) && e.target !== emojiBtn) {
                pickerContainer.style.display = 'none';
            }
        });

        // Envoi avec Enter
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.submit();
            }
        });

        // Rafraîchissement automatique
        setInterval(function() {
            fetch('load_messages.php')
                .then(res => res.text())
                .then(html => {
                    const area = document.getElementById('messages');
                    const wasAtBottom = area.scrollTop + area.clientHeight >= area.scrollHeight - 10;
                    area.innerHTML = html;
                    if (wasAtBottom) area.scrollTop = area.scrollHeight;
                });
        }, 3000);
    </script>

</body>

</html>