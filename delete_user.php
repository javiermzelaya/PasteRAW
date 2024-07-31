<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Eliminar el usuario de la base de datos
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$user_id]);

    header('Location: manage_users.php');
    exit;
} else {
    header('Location: manage_users.php');
    exit;
}
?>