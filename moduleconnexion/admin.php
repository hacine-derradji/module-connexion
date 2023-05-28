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
$query = $conn->prepare('SELECT * FROM user WHERE id = :id AND login = "admin"');
$query->bindParam(':id', $user_id);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Accès non autorisé.');
}

$query = $conn->query('SELECT * FROM user');
$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
</head>
<body>
<h1>Administration</h1>
<h2>Liste des utilisateurs :</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Login</th>
    </tr>
    <?php foreach ($users as $user) : ?>
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['login']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
