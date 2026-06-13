<?php

require_once 'config/database.php';

$username = "admin";

$email = "admin@kjg-albachten.de";

$password = "Passwort123!";

$hash = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = $pdo->prepare("
INSERT INTO users
(username,email,password_hash,role)
VALUES
(?,?,?,'admin')
");

$stmt->execute([
    $username,
    $email,
    $hash
]);

echo "Admin erstellt!";