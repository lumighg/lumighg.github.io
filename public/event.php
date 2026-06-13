<?php
require_once __DIR__ . '/../app/bootstrap.php';

$id = $_GET["id"] ?? null;

if (!$id) {
    die("Event nicht gefunden");
}

/*
------------------------
EVENT LADEN
------------------------
*/
$stmt = $pdo->prepare("
    SELECT * FROM events WHERE id = ?
");

$stmt->execute([$id]);
$event = $stmt->fetch();

if (!$event) {
    die("Event existiert nicht");
}

/*
------------------------
ANMELDUNG SPEICHERN
------------------------
*/
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first = $_POST["first_name"];
    $last = $_POST["last_name"];
    $email = $_POST["email"];

    // -------------------------
    // Prüfen ob schon angemeldet
    // -------------------------
    $check = $pdo->prepare("
        SELECT id FROM event_registrations
        WHERE event_id = ? AND email = ?
    ");

    $check->execute([$id, $email]);

    if ($check->fetch()) {
        $message = "Du bist bereits angemeldet!";
    } else {

        // -------------------------
        // Teilnehmerzahl prüfen
        // -------------------------
        $count = $pdo->prepare("
            SELECT COUNT(*) FROM event_registrations
            WHERE event_id = ?
        ");

        $count->execute([$id]);
        $total = $count->fetchColumn();

        if ($event["max_participants"] && $total >= $event["max_participants"]) {
            $message = "Leider keine Plätze mehr frei.";
        } else {

            $insert = $pdo->prepare("
                INSERT INTO event_registrations
                (event_id, first_name, last_name, email)
                VALUES (?, ?, ?, ?)
            ");

            $insert->execute([$id, $first, $last, $email]);

            $message = "Erfolgreich angemeldet!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Event Anmeldung</title>

<style>
body{
    font-family:Arial;
    background:#f4f4f4;
    margin:0;
}

.container{
    max-width:700px;
    margin:auto;
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
}

input{
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

.msg{
    color:green;
}
</style>

</head>
<body>

<div class="container">

<div class="card">

<h2><?= htmlspecialchars($event["title"]) ?></h2>

<p><?= nl2br(htmlspecialchars($event["description"])) ?></p>

<p>
<strong>Start:</strong> <?= $event["start_date"] ?><br>
<strong>Ende:</strong> <?= $event["end_date"] ?>
</p>

<?php if($message): ?>
<p class="msg"><?= $message ?></p>
<?php endif; ?>

<h3>Anmeldung</h3>

<form method="post">

    <input type="text" name="first_name" placeholder="Vorname" required>
    <input type="text" name="last_name" placeholder="Nachname" required>
    <input type="email" name="email" placeholder="E-Mail" required>

    <button>Anmelden</button>

</form>

</div>

</div>

</body>
</html>