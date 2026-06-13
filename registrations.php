<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/auth.php';

requireLogin();

$eventId = $_GET["event_id"] ?? null;

if (!$eventId) {
    die("Kein Event gewählt");
}

/*
-------------------------
EVENT
-------------------------
*/
$stmt = $pdo->prepare("
    SELECT * FROM events WHERE id = ?
");

$stmt->execute([$eventId]);
$event = $stmt->fetch();

/*
-------------------------
TEILNEHMER
-------------------------
*/
$stmt = $pdo->prepare("
    SELECT * FROM event_registrations
    WHERE event_id = ?
    ORDER BY registered_at DESC
");

$stmt->execute([$eventId]);

$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Teilnehmer</title>

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
    border-radius:10px;
    margin-bottom:10px;
}
</style>

</head>
<body>

<div class="container">

<h2>Teilnehmer: <?= htmlspecialchars($event["title"]) ?></h2>

<div class="card">

<?php foreach($users as $u): ?>

<p>
<strong><?= htmlspecialchars($u["first_name"]) ?>
<?= htmlspecialchars($u["last_name"]) ?></strong><br>
<?= htmlspecialchars($u["email"]) ?><br>
<small><?= $u["registered_at"] ?></small>
</p>

<hr>

<?php endforeach; ?>

</div>

</div>

</body>
</html>