<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/auth.php';

requireLogin();

/*
------------------------
NACHRICHTEN LADEN
------------------------
*/
$stmt = $pdo->query("
    SELECT * FROM contact_messages
    ORDER BY created_at DESC
");

$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Kontakt Nachrichten</title>

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
    margin:0;
}

.container{
    max-width:900px;
    margin:auto;
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    margin-bottom:10px;
    border-radius:10px;
}
</style>

</head>
<body>

<div class="container">

<h2>Kontakt Nachrichten</h2>

<?php foreach($messages as $m): ?>

<div class="card">

    <p><strong><?= htmlspecialchars($m["name"]) ?></strong></p>
    <p><?= htmlspecialchars($m["email"]) ?></p>
    <p><strong><?= htmlspecialchars($m["subject"]) ?></strong></p>
    <p><?= nl2br(htmlspecialchars($m["message"])) ?></p>

    <small>
        Status:
        <?= $m["sent_successfully"] ? "gesendet" : "nicht gesendet" ?>
    </small>

</div>

<?php endforeach; ?>

</div>

</body>
</html>