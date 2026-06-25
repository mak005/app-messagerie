<div class="message-row <?= $isMine ? 'mine' : 'other' ?>">
    <div class="message-bubble">
        <div class="message-user"><?= htmlspecialchars($row['name']) ?></div>
        <div class="message-text"><?= htmlspecialchars($row['message']) ?></div>
        <div class="message-date"><?= $row['heure'] ?></div>
    </div>
</div>