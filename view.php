<?php
session_start();
require 'config.php'; // Incluir la configuración global

// Obtener el título del sitio y el logo desde la base de datos
$stmt = $pdo->query('SELECT title, logo_filename FROM settings LIMIT 1');
$settings = $stmt->fetch();
$title = $settings['title'] ?? 'Your Site Title';
$logo_filename = $settings['logo_filename'] ?? '';

// Obtener el contenido del paste (simulado aquí para el ejemplo)
$paste_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare('SELECT title, content FROM pastes WHERE id = ?');
$stmt->execute([$paste_id]);
$paste = $stmt->fetch();
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
        @import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

        body {
            font-family: Poppins, sans-serif;
            margin-bottom: 100px;
            transition: background-color 0.3s, color 0.3s;
        }
        .navbar {
            transition: background-color 0.3s, color 0.3s;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, color 0.3s;
        }
        .dark-mode {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        .dark-mode .navbar {
            background-color: #2c2c2c;
        }
        .dark-mode .container {
            background-color: #2c2c2c;
        }
        .dark-mode .table {
            background-color: #2c2c2c;
            color: #ffffff;
        }
        .dark-mode .table thead th {
            background-color: #3a3a3a;
        }
        .dark-mode .table tbody tr:nth-child(even) {
            background-color: #2c2c2c;
        }
        .dark-mode .table tbody tr:nth-child(odd) {
            background-color: #3a3a3a;
        }
        .dark-mode .form-control {
            background-color: #3a3a3a;
            color: #ffffff;
            border: 1px solid #555555;
        }
        .dark-mode .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .dark-mode .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        pre {
            padding: 15px;
            border-radius: 5px;
        }
        .hljs-ln-numbers {
            text-align: right;
            padding-right: 10px;
            margin-right: 10px;
            border-right: 1px solid #888;
            color: #888;
            user-select: none;
            -webkit-user-select: none;
        }
        .hljs-ln-code {
            padding-left: 10px;
        }
        .dark-mode .hljs-ln-numbers {
            border-right-color: #555;
            color: #aaa;
        }
        .dark-mode pre {
            background-color: #1e1e1e;
            color: #f8f8f2;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <!-- Mostrar anuncios -->
        <?php if (isset($ads['banner'])): ?>
            <div class="ad-container">
                <?= $ads['banner'] ?>
            </div>
        <?php endif; ?>

        <h1 class="display-4 text-truncate"><?= htmlspecialchars($paste['title']) ?></h1>
        <pre><code class="language-php"><?= htmlspecialchars($paste['content']) ?></code></pre>
        
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
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const toggle = document.getElementById('theme-toggle');
            const codeThemeLight = document.getElementById('code-theme-light');
            const codeThemeDark = document.getElementById('code-theme-dark');

            let currentTheme = localStorage.getItem('theme') || 'light';

            function applyTheme(theme) {
                if (theme === 'dark') {
                    body.classList.add('dark-mode');
                    codeThemeLight.disabled = true;
                    codeThemeDark.disabled = false;
                } else {
                    body.classList.remove('dark-mode');
                    codeThemeLight.disabled = false;
                    codeThemeDark.disabled = true;
                }
            }

            applyTheme(currentTheme);

            toggle.addEventListener('click', () => {
                const newTheme = body.classList.contains('dark-mode') ? 'light' : 'dark';
                localStorage.setItem('theme', newTheme);
                applyTheme(newTheme);
            });

            hljs.initHighlightingOnLoad();
            hljs.lineNumbersBlock();
        });
    </script>
</body>
</html>