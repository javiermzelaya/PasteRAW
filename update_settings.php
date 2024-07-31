<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$site_title = $_POST['site_title'];
$remove_logo = isset($_POST['remove_logo']) ? 1 : 0;

$logo_filename = null;

if ($remove_logo) {
    $stmt = $pdo->prepare('UPDATE settings SET logo_filename = NULL');
    $stmt->execute();
} elseif (!empty($_FILES['logo']['name'])) {
    $logo_filename = $_FILES['logo']['name'];
    move_uploaded_file($_FILES['logo']['tmp_name'], 'uploads/' . $logo_filename);
    $stmt = $pdo->prepare('UPDATE settings SET logo_filename = ?');
    $stmt->execute([$logo_filename]);
}

$stmt = $pdo->prepare('UPDATE settings SET title = ?');
$stmt->execute([$site_title]);

header('Location: admin_panel.php');
exit;
?>