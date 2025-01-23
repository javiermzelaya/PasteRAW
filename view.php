<?php
session_start();
require 'config.php'; // Incluir la configuración global

// Obtener el título del sitio y el logo desde la base de datos
$stmt = $pdo->query('SELECT title, logo_filename FROM settings LIMIT 1');
$settings = $stmt->fetch();
$title = $settings['title'] ?? 'Your Site Title';
$logo_filename = $settings['logo_filename'] ?? '';

// Obtener la configuración de anuncios desde la base de datos
$stmt = $pdo->prepare('SELECT ad_type, ad_code FROM ads_settings');
$stmt->execute();
$ads_settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ads = [];
foreach ($ads_settings as $ad) {
    $ads[$ad['ad_type']] = $ad['ad_code'];
}

// Obtener el contenido del paste
$paste_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare('SELECT title, content FROM pastes WHERE id = ?');
$stmt->execute([$paste_id]);
$paste = $stmt->fetch();

// Función para convertir URLs en enlaces clickeables
function make_links_clickable($text) {
    return preg_replace(
        '/(https?:\/\/[^\s]+)/',
        '<a href="$1" target="_blank">$1</a>',
        htmlspecialchars($text)
    );
}

$content = make_links_clickable($paste['content']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Paste Viewer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/default.min.css" id="code-theme-light">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/styles/github-dark.min.css" id="code-theme-dark" disabled>
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;700;900&display=swap);
        body {
            font-family: Poppins, sans-serif;
        }
        .dark-mode body {
            background-color: #212121;
        }
        .code-container {
            font-family: 'Consolas', 'Courier New', monospace;
            display: flex;
            color: #d4d4d4;
            padding: 10px;
            overflow: auto;
            height: auto;
            box-sizing: border-box;
        }
        pre.code-content {
            padding-top: 0px;
        }
        div.code-container {
            line-height: 15px;
        }
        .line-numbers {
            font-family: 'Consolas', 'Courier New', monospace;
            padding-right: 10px;
            text-align: right;
            color: #858585;
            user-select: none;
        }
        .code-content {
            white-space: pre-wrap;
        }
        h1.display-4.text-nowrap {
            overflow-y: hidden !important;
            overflow-x: auto !important;
            white-space: nowrap !important;
        }
        .ad-container {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .banner-ad {
            width: 100%;
            height: 90px; /* Tamaño típico de un banner */
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <?php if (isset($ads['banner_top'])): ?>
        <div class="ad-container banner-ad">
            <?= $ads['banner_top'] ?>
        </div>
    <?php endif; ?>
    
    <div class="container-fluid">
        <h1 class="options text-center"><?= htmlspecialchars($paste['title']) ?></h1>
    </div>
    <div class="code-container">
        <div class="line-numbers"></div>
        <pre class="code-content"><code id="paste-code"><?= $content ?></code></pre>
    </div>
    
    <div class="options text-center">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="edit_paste.php?id=<?= $paste_id ?>" class="btn btn-primary">Edit</a>
            <a href="raw.php?id=<?= $paste_id ?>" class="btn btn-primary">View Raw</a>
        <?php endif; ?>
    </div>

    <?php if (isset($ads['banner_bottom'])): ?>
        <div class="ad-container banner-ad">
            <?= $ads['banner_bottom'] ?>
        </div>
    <?php endif; ?>
    <script>
        window.onload = function() {
            const codeElement = document.getElementById('paste-code');
            const lines = codeElement.innerHTML.split('\n').length;
            const lineNumbersElement = document.querySelector('.line-numbers');

            let lineNumbersHTML = '';
            for (let i = 1; i <= lines; i++) {
                lineNumbersHTML += i + '<br>';
            }
            lineNumbersElement.innerHTML = lineNumbersHTML;
        };
    </script>
    
    <?php include 'footbar.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.7.2/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlightjs-line-numbers.js/2.8.0/highlightjs-line-numbers.min.js"></script>
</body>
</html>
