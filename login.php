<?php
require_once __DIR__ . '/../app/bootstrap.php';
requireLogin();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $pdo->prepare("
        SELECT * FROM users
        WHERE username = ?
    ");

    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Login fehlgeschlagen";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:linear-gradient(135deg,#00b7b3,#006a67);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.box{
    background:white;
    padding:30px;
    border-radius:15px;
    width:300px;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    width:100%;
    padding:10px;
    background:#00b7b3;
    color:white;
    border:none;
    cursor:pointer;
}

.error{
    color:red;
}
</style>

</head>
<body>

<div class="box">

<h2>Admin Login</h2>

<?php if($error): ?>
<p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Passwort" required>
    <button>Login</button>
</form>

</div>

</body>
</html>