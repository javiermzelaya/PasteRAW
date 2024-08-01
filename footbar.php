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
    .footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #f8f9fa;
        padding: 1rem 0;
        transition: background-color 0.3s ease;
    }

    .footer .container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .dark-mode .footer {
        background-color: #0d0d0d !important; /* Color más oscuro */
        color: #fff;
    }

    .dark-mode .text-muted {
        color: #ccc !important;
    }
</style>