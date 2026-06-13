<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/auth.php';

requireLogin();

$userId = $_SESSION["user_id"];

$message = "";

/*
---------------------------------
NEUEN BEITRAG ERSTELLEN
---------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST["title"] ?? '';
    $content = $_POST["content"] ?? '';
    $imagePath = null;

    // -------------------------
    // Bild Upload
    // -------------------------
    if (!empty($_FILES["image"]["name"])) {

        $uploadDir = __DIR__ . "/../uploads/posts/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileType = $_FILES["image"]["type"];

        $allowed = $config["allowed_image_types"];

        if (in_array($fileType, $allowed)) {

            $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

            $fileName = bin2hex(random_bytes(16)) . "." . $ext;

            $target = $uploadDir . $fileName;

            move_uploaded_file($_FILES["image"]["tmp_name"], $target);

            $imagePath = "uploads/posts/" . $fileName;
        }
    }

    // -------------------------
    // DB speichern
    // -------------------------
    $stmt = $pdo->prepare("
        INSERT INTO posts (title, content, image_path, author_id)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $content,
        $imagePath,
        $userId
    ]);

    $message = "Beitrag erstellt!";
}

/*
---------------------------------
ALLE BEITRÄGE LADEN
---------------------------------
*/
$stmt = $pdo->query("
    SELECT p.*, u.username
    FROM posts p
    LEFT JOIN users u ON p.author_id = u.id
    ORDER BY p.created_at DESC
");

$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Beiträge</title>

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
    margin-bottom:20px;
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

.post img{
    max-width:100%;
    border-radius:10px;
}

.msg{
    color:green;
}
</style>

</head>
<body>

<div class="container">

<h2>Beiträge verwalten</h2>

<?php if($message): ?>
<p class="msg"><?= $message ?></p>
<?php endif; ?>

<!-- FORMULAR -->
<div class="card">

<h3>Neuen Beitrag erstellen</h3>

<form method="post" enctype="multipart/form-data">

    <input type="text" name="title" placeholder="Titel" required>

    <textarea name="content" rows="5" placeholder="Text..." required></textarea>

    <input type="file" name="image">

    <button>Veröffentlichen</button>

</form>

</div>

<!-- LISTE -->
<?php foreach($posts as $p): ?>

<div class="card post">

    <h3><?= htmlspecialchars($p["title"]) ?></h3>

    <small>von <?= $p["username"] ?> | <?= $p["created_at"] ?></small>

    <?php if($p["image_path"]): ?>
        <img src="../<?= $p["image_path"] ?>">
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($p["content"])) ?></p>

</div>

<?php endforeach; ?>

</div>

</body>
</html>