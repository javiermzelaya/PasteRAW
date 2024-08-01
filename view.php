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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/github.min.css"> <!-- Puedes cambiar el tema según tu preferencia -->
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

        html body {
            font-family: Poppins;
			margin-bottom: 150px;
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
		body {
  			margin-bottom: 100px;
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
        .hljs-ln-numbers {
            text-align: right;
            padding-right: 10px;
            margin-right: 10px;
            border-right: 1px solid #ddd;
            color: #999;
            user-select: none;
            -webkit-user-select: none;
        }
        .hljs-ln-code {
            padding-left: 10px;
        }
    </style>
</head>
		<?php include 'navbar.php'; ?>
<body>
    <div class="container mt-5">
        <!-- Mostrar anuncios -->
        <?php if (isset($ads['banner'])): ?>
            <div class="ad-container">
                <?= $ads['banner'] ?>
            </div>
        <?php endif; ?>

        <h1 class="display-4 text-truncate"><?= htmlspecialchars($paste['title']) ?></h1>
        <pre><code class="bg-light p-3 rounded preformatted-content"><?= htmlspecialchars($paste['content']) ?></code></pre>
        
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
    </div>
			<?php include 'footbar.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightBlock(block);
                hljs.lineNumbersBlock(block, { singleLine: true });
            });
        });
    </script>
</body>
</html>