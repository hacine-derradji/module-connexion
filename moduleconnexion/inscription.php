<?php

$db_host = 'localhost';
$db_name = 'moduleconnexion';
$db_user = 'root';
$db_password = 'Scarface13';
$conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
    $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';


    if ($password !== $confirm_password) {
        die('Les mots de passe ne correspondent pas.');
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{8,}$/', $password)) {
        die('Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.');
    }


    $query = $conn->prepare('SELECT COUNT(*) FROM user WHERE login = :login');
    $query->bindParam(':login', $login);
    $query->execute();
    $count = $query->fetchColumn();
    if ($count > 0) {
        die('Ce login est déjà utilisé.');
    }


    $query = $conn->prepare('INSERT INTO user (login, password, firstname, lastname) VALUES (:login, :password, :firstname, :lastname)');
    $query->bindParam(':login', $login);
    $query->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));
    $query->bindParam(':firstname', $firstname);
    $query->bindParam(':lastname', $lastname);
    $query->execute();


    header('Location: connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<body>
<h1>Inscription</h1>
<form method="POST" action="inscription.php">
    <label for="login">Login :</label>
    <input type="text" name="login" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required><br>

    <label for="confirm_password">Confirmer le mot de passe :</label>
    <input type="password" name="confirm_password" required><br>

    <label for="firstname">Prénom :</label>
    <input type="text" name="firstname"><br>

    <label for="lastname">Nom :</label>
    <input type="text" name="lastname"><br>

    <input type="submit" value="S'inscrire">
</form>
</body>
</html>
