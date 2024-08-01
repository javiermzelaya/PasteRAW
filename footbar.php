<?php
require 'config.php'; // Incluir la configuración global

// Obtener la leyenda del footer desde la base de datos
$stmt = $pdo->query('SELECT footer_legend FROM settings LIMIT 1');
$settings = $stmt->fetch();
$footer_legend = $settings['footer_legend'] ?? '';
?>
<footer class="footer mt-auto py-3 bg-light" id="footer">
    <div class="container d-flex justify-content-center">
        <span class="text-muted"><?= htmlspecialchars($footer_legend) ?></span>
    </div>
</footer>

    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

        body {
            font-family: Poppins, sans-serif;
            margin-bottom: 100px;
        }
    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        padding: 1rem 0;
        transition: background-color 0.3s ease;
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