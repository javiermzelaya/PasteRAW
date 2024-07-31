<?php
// config.php
require 'db.php';

// Obtener el nombre del sitio y otros ajustes globales
$stmt = $pdo->prepare('SELECT title, footer_legend FROM settings WHERE id = 1');
$stmt->execute();
$settings = $stmt->fetch();

$site_name = $settings['title'];
$footer_legend = $settings['footer_legend'];
?>
