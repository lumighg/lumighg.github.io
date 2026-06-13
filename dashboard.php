<?php
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/auth.php';

requireLogin();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<style>
:root{
    --main:#00b7b3;
    --bg:#f4f4f4;
    --card:#ffffff;
}

body.dark{
    --bg:#1e1e1e;
    --card:#2a2a2a;
    color:white;
}

body{
    margin:0;
    font-family:Arial;
    background:var(--bg);
}

.sidebar{
    width:200px;
    height:100vh;
    background:var(--main);
    position:fixed;
    padding:20px;
    color:white;
}

.content{
    margin-left:220px;
    padding:20px;
}

.card{
    background:var(--card);
    padding:20px;
    border-radius:10px;
    margin-bottom:15px;
}

a{
    color:white;
    display:block;
    margin:10px 0;
    text-decoration:none;
}

button{
    padding:10px;
    border:none;
    background:var(--main);
    color:white;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="sidebar">
    <h3>KJG Admin</h3>

    <a href="#">Beiträge</a>
    <a href="#">Kalender</a>
    <a href="#">Anmeldungen</a>
    <a href="#">Kontakt</a>


    <hr>
    <a href="events.php">Kalender</a>
    <a href="contacts.php">Kontakt Nachrichten</a>
    <a href="logout.php">Logout</a>

    <button onclick="toggleDark()">Dark Mode</button>
</div>

<div class="content">

<div class="card">
    <h2>Willkommen <?= $_SESSION["username"] ?></h2>
    <p>Admin Dashboard der KJG Albachten</p>
</div>

<div class="card">
    <h3>Status</h3>
    <p>System läuft korrekt</p>
</div>

</div>

<script>
function toggleDark(){
    document.body.classList.toggle("dark");
    localStorage.setItem("dark", document.body.classList.contains("dark"));
}

if(localStorage.getItem("dark")==="true"){
    document.body.classList.add("dark");
}
</script>

</body>
</html>