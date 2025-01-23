<?php
session_start();
require 'config.php'; // Incluir la configuración global

// Obtener el título del sitio y el logo desde la base de datos
$stmt = $pdo->query('SELECT title, logo_filename FROM settings LIMIT 1');
$settings = $stmt->fetch();
$title = $settings['title'] ?? 'Your Site Title';
$logo_filename = $settings['logo_filename'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light" id="navbar">
    <a class="navbar-brand" href="index.php">
        <?php if ($logo_filename): ?>
            <img src="uploads/<?= htmlspecialchars($logo_filename) ?>" alt="Logo" class="navbar-logo">
        <?php else: ?>
            <?= htmlspecialchars($title) ?>
        <?php endif; ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link prominent-button" href="user_panel.php">User Panel</a>
                </li>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link prominent-button" href="admin_panel.php">Admin Panel</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link prominent-button" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link prominent-button" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link prominent-button" href="register.php">Register</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link prominent-button" id="dark-mode-toggle" href="#">Dark Mode</a>
            </li>
        </ul>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const toggle = document.getElementById('dark-mode-toggle');
        const currentTheme = localStorage.getItem('theme') || 'dark';
        const navbar = document.getElementById('navbar');
        const footer = document.getElementById('footer');

        if (currentTheme === 'dark') {
            document.body.classList.add('dark-mode');
            navbar.classList.replace('navbar-light', 'navbar-dark');
            navbar.classList.replace('bg-light', 'bg-dark');
            if (footer) {
                footer.classList.replace('bg-light', 'bg-dark');
            }
            toggle.textContent = 'Light Mode';
        } else {
            toggle.textContent = 'Dark Mode';
        }

        toggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const theme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);

            if (theme === 'dark') {
                navbar.classList.replace('navbar-light', 'navbar-dark');
                navbar.classList.replace('bg-light', 'bg-dark');
                if (footer) {
                    footer.classList.replace('bg-light', 'bg-dark');
                }
                toggle.textContent = 'Light Mode';
            } else {
                navbar.classList.replace('navbar-dark', 'navbar-light');
                navbar.classList.replace('bg-dark', 'bg-light');
                if (footer) {
                    footer.classList.replace('bg-dark', 'bg-light');
                }
                toggle.textContent = 'Dark Mode';
            }
        });
    });
</script>
<style>
	/* Estilo de los enlaces del navbar */
.nav-link {
    text-transform: uppercase; /* Mostrar en mayúsculas */
    transition: color 0.3s ease;
}
	/* Botón prominente */
.prominent-button {
    font-weight: bold;
    color: #999999 !important; /* Color de texto en modo claro */
    background-color: #ffffff !important; /* Color de fondo en modo claro */
    padding: 5px 10px;
    border-radius: 5px;
    margin-left: 5px; /* Espacio entre los botones */
    transition: all 0.3s ease;
}

.prominent-button:hover {
    color: #999999;
    background-color: #ffffff !important; /* Color de fondo en modo claro al pasar el mouse */
}

.dark-mode a.nav-link.prominent-button {
    color: #fff !important; /* Color de texto en modo oscuro */
    background-color: #007bff !important; /* Color de fondo en modo oscuro */
}

.dark-mode a.nav-link.prominent-button:hover {
    color: #000;
    background-color: #0056b3 !important; /* Color de fondo en modo oscuro al pasar el mouse */
}
/* Estilo del logo del navbar */
.navbar-logo {
    height: 100px;
    transition: transform 0.3s ease;
}

.navbar-logo:hover {
    transform: scale(1.1);
}
/* Responsividad */
@media (max-width: 768px) {
    .navbar {
        padding: 0.5rem 1rem;
    }

    .nav-item {
        text-align: center;
        margin: 0.5rem 0;
    }

    .navbar-logo {
        height: 30px;
    }
}

@media (min-width: 769px) {
    .navbar {
        padding: 0.75rem 1.5rem;
    }
}
</style>
