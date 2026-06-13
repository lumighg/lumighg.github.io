<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/auth.php';

requireLogin();

$message = "";

/*
-------------------------
EVENT ERSTELLEN
-------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"];
    $desc = $_POST["description"];
    $start = $_POST["start_date"];
    $end = $_POST["end_date"];

    $stmt = $pdo->prepare("
        INSERT INTO events
        (title, description, start_date, end_date, created_by)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $desc,
        $start,
        $end,
        $_SESSION["user_id"]
    ]);

    $message = "Termin erstellt!";
}

/*
-------------------------
EVENT LISTE
-------------------------
*/
$events = $pdo->query("
    SELECT * FROM events
    ORDER BY start_date DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Events Admin</title>

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
    margin-bottom:20px;
    border-radius:10px;
}

input, textarea{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    background:#00b7b3;
    color:white;
    border:none;
    padding:10px;
    cursor:pointer;
}
</style>

</head>
<body>

<div class="container">

<h2>Kalender Verwaltung</h2>

<?php if($message): ?>
<p style="color:green;"><?= $message ?></p>
<?php endif; ?>
<?php foreach($events as $e): ?>
<div class="card">
<p>
        <strong><?= htmlspecialchars($e["title"]) ?></strong><br>
        <?= $e["start_date"] ?> → <?= $e["end_date"] ?>
    </p>

    <!-- 🔽 NEU: Teilnehmer-Button -->
    <a href="registrations.php?event_id=<?= $e['id'] ?>">
        Teilnehmer ansehen
    </a>

</div>

<?php endforeach; ?>
<h3>Neuen Termin erstellen</h3>

<form method="post">

    <input type="text" name="title" placeholder="Titel" required>

    <textarea name="description" placeholder="Beschreibung"></textarea>

    <label>Start</label>
    <input type="datetime-local" name="start_date" required>

    <label>Ende</label>
    <input type="datetime-local" name="end_date" required>

    <button>Speichern</button>

</form>

</div>

<div class="card">

<h3>Alle Termine</h3>

<?php foreach($events as $e): ?>

    <p>
        <strong><?= htmlspecialchars($e["title"]) ?></strong><br>
        <?= $e["start_date"] ?> → <?= $e["end_date"] ?>
    </p>

<?php endforeach; ?>

</div>

</div>

</body>
</html>