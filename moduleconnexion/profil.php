<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$db_host = 'localhost';
$db_name = 'moduleconnexion';
$db_user = 'root';
$db_password = 'Scarface13';
$conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);

$user_id = $_SESSION['user_id'];
$query = $conn->prepare('SELECT * FROM user WHERE id = :id');
$query->bindParam(':id', $user_id);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_new_password) {
        die('Les nouveaux mots de passe ne correspondent pas.');
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{8,}$/', $new_password)) {
        die('Le nouveau mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.');
    }

    $query = $conn->prepare('UPDATE user SET password = :password WHERE id = :id');
    $query->bindParam(':password', password_hash($new_password, PASSWORD_DEFAULT));
    $query->bindParam(':id', $user_id);
    $query->execute();

    echo 'Mot de passe mis à jour.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
</head>
<body>
<h1>Profil</h1>
<h2>Bienvenue, <?php echo $user['login']; ?></h2>
<p>Modifier votre mot de passe :</p>
<form method="POST" action="profil.php">
    <label for="new_password">Nouveau mot de passe :</label>
    <input type="password" name="new_password" required><br>

    <label for="confirm_new_password">Confirmer le nouveau mot de passe :</label>
    <input type="password" name="confirm_new_password" required><br>

    <input type="submit" value="Modifier le mot de passe">
</form>
</body>
</html>
