<?php 
session_start();
require 'config.php'; // Incluir la configuración global

// Verificar permisos de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener la configuración actual
$stmt = $pdo->query('SELECT title, logo_filename, footer_legend FROM settings LIMIT 1');
$settings = $stmt->fetch();
$logo_filename = $settings['logo_filename'] ?? '';
$footer_legend = $settings['footer_legend'] ?? '';
$site_name = $settings['title'];

// Obtener la configuración de anuncios
$stmt = $pdo->prepare('SELECT ad_type, ad_code FROM ads_settings');
$stmt->execute();
$ads_settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ads = [];
foreach ($ads_settings as $ad) {
    $ads[$ad['ad_type']] = $ad['ad_code'];
}

// Verificar si se ha subido un logo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo_tmp_path = $_FILES['logo']['tmp_name'];
        $logo_name = $_FILES['logo']['name'];
        $logo_destination = 'uploads/' . $logo_name;

        if (move_uploaded_file($logo_tmp_path, $logo_destination)) {
            $logo_filename = $logo_name;
            $stmt = $pdo->prepare('UPDATE settings SET logo_filename = ?');
            $stmt->execute([$logo_filename]);
        }
    }

    if (isset($_POST['delete_logo'])) {
        $stmt = $pdo->prepare('UPDATE settings SET logo_filename = NULL');
        $stmt->execute();
        $logo_filename = '';
    }

    if (isset($_POST['title'])) {
        $title = $_POST['title'];
        $stmt = $pdo->prepare('UPDATE settings SET title = ?');
        $stmt->execute([$title]);
    }

    if (isset($_POST['footer_legend'])) {
        $footer_legend = $_POST['footer_legend'];
        $stmt = $pdo->prepare('UPDATE settings SET footer_legend = ?');
        $stmt->execute([$footer_legend]);
    }

    // Manejar la actualización de los anuncios
    if (isset($_POST['update_ads'])) {
        $ad_types = ['banner', 'skyscraper', 'leaderboard', 'rectangle', 'mobile'];
        foreach ($ad_types as $type) {
            if (isset($_POST["ad_code_$type"])) {
                $ad_code = $_POST["ad_code_$type"];
                $stmt = $pdo->prepare('INSERT INTO ads_settings (ad_type, ad_code) VALUES (?, ?) ON DUPLICATE KEY UPDATE ad_code = ?');
                $stmt->execute([$type, $ad_code, $ad_code]);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= htmlspecialchars($site_name) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap');

        body {
            font-family: Poppins;
        }
    </style>
</head>
<body>
	<?php include 'navbar.php'; ?>
<div class="container mt-5">
        <title>Admin Panel - <?= htmlspecialchars($site_name) ?></title>
    
    <form action="admin_panel.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Site Title:</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($site_name) ?>">
        </div>
        <div class="form-group">
            <label for="footer_legend">Footer Legend:</label>
            <input type="text" id="footer_legend" name="footer_legend" class="form-control" value="<?= htmlspecialchars($footer_legend) ?>">
        </div>
        <div class="form-group">
            <label for="logo">Upload Logo:</label>
            <input type="file" id="logo" name="logo" class="form-control">
            <?php if ($logo_filename): ?>
                <div class="mt-3">
                    <img src="uploads/<?= htmlspecialchars($logo_filename) ?>" alt="Logo" style="max-width: 200px;">
                    <button type="submit" name="delete_logo" class="btn btn-danger mt-2">Delete Logo</button>
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" name="update_logo" class="btn btn-primary">Update Settings</button>
    </form>
    
    <h3 class="mt-5">Advertisement Settings</h3>
    <form action="admin_panel.php" method="post">
        <?php
        $ad_types = ['banner' => 'Banner', 'skyscraper' => 'Skyscraper', 'leaderboard' => 'Leaderboard', 'rectangle' => 'Rectangle', 'mobile' => 'Mobile'];
        foreach ($ad_types as $type => $label): ?>
            <div class="form-group">
                <label for="ad_code_<?= $type ?>"><?= $label ?> Ad Code:</label>
                <textarea id="ad_code_<?= $type ?>" name="ad_code_<?= $type ?>" class="form-control" rows="4"><?= htmlspecialchars($ads[$type] ?? '') ?></textarea>
            </div>
        <?php endforeach; ?>
        <button type="submit" name="update_ads" class="btn btn-primary">Update Ads</button>
    </form>
	    <div class="mt-5">
        <h2>Manage Users</h2>
        <a href="manage_users.php" class="btn btn-primary">Manage Users</a> <a href="add_user.php" class="btn btn-primary">Add User</a>
    </div>

    <div class="mt-5">
        <h2>Manage Pastes</h2>
        <a href="manage_pastes.php" class="btn btn-primary">Manage Pastes</a>
    </div>
</div>
	<?php include 'footbar.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>