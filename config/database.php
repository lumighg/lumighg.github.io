<?php

$dbHost = "localhost";
$dbName = "kjg_albachten";
$dbUser = "DB_BENUTZER";
$dbPass = "DB_PASSWORT";

try {

    $pdo = new PDO(
        "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

} catch (PDOException $e) {

    die("Datenbankfehler: " . $e->getMessage());

}