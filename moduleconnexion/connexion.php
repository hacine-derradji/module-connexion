<?php
$db_host = 'localhost';
$db_name = 'moduleconnexion';
$db_user = 'root';
$db_password = 'Scarface13';
$conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $query = $conn->prepare('SELECT * FROM user WHERE login = :login');
    $query->bindParam(':login', $login);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login'] = $user['login'];

        header('Location: profil.php');
        exit();
    } else {
        echo 'Identifiants incorrects.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
<h1>Connexion</h1>
<form method="POST" action="connexion.php">
    <label for="login">Login :</label>
    <input type="text" name="login" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required><br>

    <input type="submit" value="Se connecter">
</form>
</body>
</html>
