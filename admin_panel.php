<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener la configuración actual
$stmt = $pdo->query('SELECT title, logo_filename, footer_legend FROM settings LIMIT 1');
$settings = $stmt->fetch();
$title = $settings['title'] ?? 'Your Site Title';
$logo_filename = $settings['logo_filename'] ?? '';
$footer_legend = $settings['footer_legend'] ?? '';

// Obtener la configuración de anuncios
$stmt_ads = $pdo->query('SELECT ad_type, ad_code FROM ads_settings');
$ads_settings = $stmt_ads->fetchAll(PDO::FETCH_ASSOC);
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

    // Actualizar los anuncios
    $ad_types = ['banner', 'skyscraper', 'leaderboard', 'rectangle', 'mobile'];
    foreach ($ad_types as $type) {
        if (isset($_POST["ad_code_$type"])) {
            $ad_code = $_POST["ad_code_$type"];
            $stmt = $pdo->prepare('INSERT INTO ads_settings (ad_type, ad_code) VALUES (?, ?) ON DUPLICATE KEY UPDATE ad_code = ?');
            $stmt->execute([$type, $ad_code, $ad_code]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?= htmlspecialchars($title) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
		@import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

.poppins-thin {
  font-family: "Poppins", sans-serif;
  font-weight: 100;
  font-style: normal;
}
        body {
            font-family: Poppins;
        }
        .dark-mode {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        .dark-mode .navbar {
            background-color: #2c2c2c;
            border-bottom: 1px solid #3a3a3a;
        }
        .dark-mode .container {
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .dark-mode h1, .dark-mode h2 {
            color: #ffffff;
        }
        .dark-mode .form-group label {
            color: #cccccc;
        }
        .dark-mode .form-control {
            background-color: #3a3a3a;
            color: #ffffff;
            border: 1px solid #555555;
            border-radius: 5px;
        }
        .dark-mode .form-control:focus {
            background-color: #3a3a3a;
            color: #ffffff;
            border-color: #007bff;
            box-shadow: none;
        }
        .dark-mode .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 5px;
        }
        .dark-mode .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .dark-mode .table {
            background-color: #2c2c2c;
            color: #ffffff;
            border-radius: 5px;
        }
        .dark-mode .table thead th {
            background-color: #3a3a3a;
            border-bottom: 1px solid #4a4a4a;
        }
        .dark-mode .table tbody tr {
            border-top: 1px solid #4a4a4a;
        }
        .dark-mode .table tbody tr:nth-child(even) {
            background-color: #2c2c2c;
        }
        .dark-mode .table tbody tr:nth-child(odd) {
            background-color: #3a3a3a;
        }
        .dark-mode .table .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000000;
            border-radius: 5px;
        }
        .dark-mode .table .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .dark-mode .table .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
            border-radius: 5px;
        }
        .dark-mode .table .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .navbar-dark .navbar-brand {
            color: #ffffff;
        }
		div.container.mt-5 {
  			margin-bottom: 40px;
		}
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Admin Panel</h2>
    <form action="admin_panel.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Site Title:</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>">
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
        <div class="form-group">
            <label for="ad_code_banner">Banner Ad Code (728x90):</label>
            <textarea id="ad_code_banner" name="ad_code_banner" class="form-control" rows="5"><?= htmlspecialchars($ads['banner'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="ad_code_skyscraper">Skyscraper Ad Code (160x600):</label>
            <textarea id="ad_code_skyscraper" name="ad_code_skyscraper" class="form-control" rows="5"><?= htmlspecialchars($ads['skyscraper'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="ad_code_leaderboard">Leaderboard Ad Code (728x90):</label>
            <textarea id="ad_code_leaderboard" name="ad_code_leaderboard" class="form-control" rows="5"><?= htmlspecialchars($ads['leaderboard'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="ad_code_rectangle">Rectangle Ad Code (300x250):</label>
            <textarea id="ad_code_rectangle" name="ad_code_rectangle" class="form-control" rows="5"><?= htmlspecialchars($ads['rectangle'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="ad_code_mobile">Mobile Ad Code (300x50):</label>
            <textarea id="ad_code_mobile" name="ad_code_mobile" class="form-control" rows="5"><?= htmlspecialchars($ads['mobile'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Settings</button>
    </form>

    <div class="mt-5">
        <h2>Manage Users</h2>
        <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
    </div>

    <div class="mt-4">
        <h2>Manage Pastes</h2>
        <a href="manage_pastes.php" class="btn btn-primary">Manage Pastes</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>