<?php
require_once __DIR__ . '/../app/bootstrap.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $text = $_POST["message"];

    // ------------------------
    // 1. IN DB SPEICHERN
    // ------------------------
    $stmt = $pdo->prepare("
        INSERT INTO contact_messages
        (name, email, subject, message, sent_successfully)
        VALUES (?, ?, ?, ?, 0)
    ");

    $stmt->execute([$name, $email, $subject, $text]);

    // ------------------------
    // 2. SMTP MAIL SENDEN
    // ------------------------
    require_once __DIR__ . '/../vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);

    try {

        // SERVER EINSTELLUNGEN
        $mail->isSMTP();
        $mail->Host = 'smtp.dein-mailserver.de';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@kjg-albachten.de';
        $mail->Password = 'DEIN_PASSWORT';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // EMPFÄNGER
        $mail->setFrom($email, $name);
        $mail->addAddress('info@kjg-albachten.de');

        // INHALT
        $mail->isHTML(true);
        $mail->Subject = $subject;

        $mail->Body = "
            <h3>Neue Nachricht von der KJG Website</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Nachricht:</strong><br>$text</p>
        ";

        $mail->send();

        // DB Update Erfolg
        $pdo->prepare("
            UPDATE contact_messages
            SET sent_successfully = 1
            WHERE email = ? ORDER BY id DESC LIMIT 1
        ")->execute([$email]);

        $message = "Nachricht erfolgreich gesendet!";

    } catch (Exception $e) {
        $message = "Fehler beim Senden: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Kontakt</title>

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

.msg{
    color:green;
}
</style>

</head>
<body>

<div class="container">

<div class="card">

<h2>Kontakt</h2>

<?php if($message): ?>
<p class="msg"><?= $message ?></p>
<?php endif; ?>

<form method="post">

    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="E-Mail" required>
    <input type="text" name="subject" placeholder="Betreff" required>

    <textarea name="message" rows="6" placeholder="Nachricht" required></textarea>

    <button>Senden</button>

</form>

</div>

</div>

</body>
</html>