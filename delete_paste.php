<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$paste_id = $_GET['id'];
$from_admin = isset($_GET['from_admin']) ? true : false;

if ($_SESSION['role'] === 'admin' || ($_SESSION['role'] !== 'admin' && $paste_id)) {
    $stmt = $pdo->prepare('DELETE FROM pastes WHERE id = ?');
    $stmt->execute([$paste_id]);

    if ($from_admin) {
        header('Location: manage_pastes.php');
    } else {
        header('Location: user_panel.php');
    }
    exit;
}
?>