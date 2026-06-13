<?php
require_once __DIR__ . '/../app/bootstrap.php';

$stmt = $pdo->query("
    SELECT id, title, description, start_date, end_date
    FROM events
    ORDER BY start_date ASC
");

$events = [];

while ($row = $stmt->fetch()) {

    $events[] = [
        "id" => $row["id"],
        "title" => $row["title"],
        "start" => $row["start_date"],
        "end" => $row["end_date"],
        "description" => $row["description"]
    ];
}

header('Content-Type: application/json');
echo json_encode($events);