<?php
session_start();
require 'config.php'; // Incluir la configuración global

// Obtener el título del sitio y el logo desde la base de datos
$stmt = $pdo->query('SELECT title, logo_filename FROM settings LIMIT 1');
$settings = $stmt->fetch();
$title = $settings['title'] ?? 'Your Site Title';
$logo_filename = $settings['logo_filename'] ?? '';

// Obtener el contenido del paste
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
        @import url(https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;700;900&display=swap);
        body {
            font-family: Poppins, sans-serif;
        }
		.dark-mode body. {
 			 background-color: #1e1e1e;
  
		}
		h1.options.text-center {
  margin-top: 25px;
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php if (isset($ads['banner'])): ?>
        <div class="ad-container">
            <?= $ads['banner'] ?>
        </div>
    <?php endif; ?>

    <div class="container-fluid">
        <h1 class="options text-center"><?= htmlspecialchars($paste['title']) ?></h1>
	</div>
        <div class="code-container">
            <div class="line-numbers"></div>
            <pre class="code-content"><code id="paste-code"><?= htmlspecialchars($paste['content']) ?></code></pre>
        </div>
        <div class="options text-center">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="edit_paste.php?id=<?= $paste_id ?>" class="btn btn-primary">Edit</a>
                <a href="raw.php?id=<?= $paste_id ?>" class="btn btn-primary">View Raw</a>
            <?php endif; ?>

            <?php foreach (['skyscraper', 'leaderboard', 'rectangle', 'mobile'] as $adType): ?>
                <?php if (isset($ads[$adType])): ?>
                    <div class="ad-container">
                        <?= $ads[$adType] ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
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