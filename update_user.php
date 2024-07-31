<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (!empty($password)) {
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET email = ?, password = ? WHERE id = ?');
    $stmt->execute([$email, $passwordHash, $_SESSION['user_id']]);
} else {
    $stmt = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
    $stmt->execute([$email, $_SESSION['user_id']]);
}

header('Location: user_panel.php');
exit;
?>