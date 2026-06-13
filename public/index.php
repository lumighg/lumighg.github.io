<?php
require_once __DIR__ . '/../app/bootstrap.php';

$stmt = $pdo->query("
    SELECT * FROM posts
    ORDER BY created_at DESC
");

$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>KJG Albachten</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f4f4;
}

header{
    background:#00b7b3;
    color:white;
    padding:20px;
    text-align:center;
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

img{
    max-width:100%;
    border-radius:10px;
}
</style>

</head>
<body>

<header>
    <h1>KJG Albachten</h1>
    <p>Gemeinschaft erleben – Glauben leben</p>
</header>

<div class="container">

<h2>Aktuelle Beiträge</h2>

<?php foreach($posts as $p): ?>

<div class="card">

    <h3><?= htmlspecialchars($p["title"]) ?></h3>

    <small><?= $p["created_at"] ?></small>

    <?php if($p["image_path"]): ?>
        <img src="../<?= $p["image_path"] ?>">
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($p["content"])) ?></p>

</div>

<?php endforeach; ?>

</div>

</body>
</html>