<?php
session_start();
require 'vendor/autoload.php';
require 'config.php'; // Incluir la configuración global

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if (!isset($_GET['id'])) {
    echo "<p class='text-danger'>No paste ID provided.</p>";
    exit;
}

$paste_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM pastes WHERE id = ?');
$stmt->execute([$paste_id]);
$paste = $stmt->fetch();

if (!$paste) {
    echo "<p class='text-danger'>Paste not found.</p>";
    exit;
}

// Obtener la configuración del sitio
$stmt_settings = $pdo->query('SELECT setting_key, setting_value FROM settings');
$settings = $stmt_settings->fetchAll(PDO::FETCH_KEY_PAIR);

// Convertir settings a un array asociativo si es necesario
$site_name = $settings['site_name'] ?? 'Your Site Name';

// Obtener los anuncios configurados
$stmt_ads = $pdo->query('SELECT ad_type, ad_code FROM ads_settings');
$ads_settings = $stmt_ads->fetchAll(PDO::FETCH_ASSOC);
$ads = [];
foreach ($ads_settings as $ad) {
    $ads[$ad['ad_type']] = $ad['ad_code'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($paste['title']) ?> - <?= htmlspecialchars($site_name) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
@import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

html body {
  font-family: Poppins
  
}
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            margin-top: 20px;
        }
        .preformatted-content {
            white-space: pre-wrap; /* Ajusta el contenido para que no se desborde */
            word-wrap: break-word;  /* Permite el salto de línea en palabras largas */
        }
        .ad-container {
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Mostrar anuncios -->
        <?php if (isset($ads['banner'])): ?>
            <div class="ad-container">
                <?= $ads['banner'] ?>
            </div>
        <?php endif; ?>

        <h1 class="display-4 text-truncate"><?= htmlspecialchars($paste['title']) ?></h1>
        <pre class="bg-light p-3 rounded preformatted-content"><?= htmlspecialchars($paste['content']) ?></pre>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="mt-3">
                <a href="edit_paste.php?id=<?= $paste_id ?>" class="btn btn-primary">Edit</a>
                <a href="raw.php?id=<?= $paste_id ?>" class="btn btn-primary">View Raw</a>
            </div>
        <?php endif; ?>

        <!-- Mostrar más anuncios -->
        <?php if (isset($ads['skyscraper'])): ?>
            <div class="ad-container">
                <?= $ads['skyscraper'] ?>
            </div>
        <?php endif; ?>

        <?php if (isset($ads['leaderboard'])): ?>
            <div class="ad-container">
                <?= $ads['leaderboard'] ?>
            </div>
        <?php endif; ?>

        <?php if (isset($ads['rectangle'])): ?>
            <div class="ad-container">
                <?= $ads['rectangle'] ?>
            </div>
        <?php endif; ?>

        <?php if (isset($ads['mobile'])): ?>
            <div class="ad-container">
                <?= $ads['mobile'] ?>
            </div>
        <?php endif; ?>

        <p class="mt-4"><a href="index.php" class="btn btn-secondary">Back to Home</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>