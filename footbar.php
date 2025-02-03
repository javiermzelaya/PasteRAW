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
@import url(https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;700;900&display=swap);

body {
    font-family: Poppins, sans-serif;
    margin-bottom: 80px; /* Altura estimada del footer */
    padding-bottom: 40px; /* Espacio adicional opcional */
}
html body {
	background-color: #ffffff;
}
	
div.container.mt-5 {
    zoom: 80% !important;
}
	
h1.mb-4,
h2.mt-5,
div div label {
    background-color: #f8f9fa !important;
    border: 15px solid #f8f9fa !important;
    border-radius: 20px !important;
    width: fit-content !important;
}
	h1.mb-4 {
		margin-top: 30px !important;
}
	
div.form-group.col-md-4 {
  text-align: center;
}	

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s, color 0.3s;
}

.nav-link.prominent-button {
    text-transform: uppercase;
}

.navbar {
    padding: 0.75rem 1.5rem;
}
	
.dark-mode h1.options.text-center {
    display: none !important;
}
	
.navbar.navbar-expand-lg.bg-light,
.footer.bg-light {
    background-color: #ffffff;
}

.navbar.navbar-expand-lg.bg-dark,
.footer.bg-dark {
    background-color: #1c1c1c !important;
}

.footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    padding: 1rem 0;
}

.text-muted {
    color: #999999 !important;
}

.container.d-flex.justify-content-center {
    background-color: #ffffff !important;
    color: #999999 !important;
}

/* Modo oscuro */
.dark-mode {
    background-color: #212121 !important;
    color: #ffffff !important;
}

.dark-mode .container,
.dark-mode .table,
.dark-mode .form-control,
.dark-mode pre {
    background-color: #212121 !important;
    color: #ffffff !important;
}

.dark-mode .table thead th,
.dark-mode .table tbody tr:nth-child(odd),
.dark-mode .table tbody tr:nth-child(even),
.dark-mode tbody tr td, table thead th {
    border: 1px solid #424242 !important;
}

tbody tr td {
  border: 1px solid #424242 !important;
}

.dark-mode .form-control {
    border: 1px solid #555555 !important;
}

.dark-mode .btn-primary {
    background-color: #007bff !important;
    border-color: #007bff !important;
}

.dark-mode .btn-primary:hover {
    background-color: #0056b3 !important;
    border-color: #0056b3 !important;
}

.dark-mode h1.mb-4,
.dark-mode h2.mt-5,
.dark-mode div div label {
    background-color: #1a1a1a !important;
    border: 15px solid #1a1a1a !important;
    border-radius: 20px !important;
    width: fit-content !important;
}

.dark-mode input.form-control,
.dark-mode textarea.form-control {
    border-style: solid !important;
    margin-bottom: 10px !important; !important;
    box-shadow: none !important;
}

.dark-mode pre code a {
    color: #007bff !important;
}

.dark-mode div.container-fluid {
	margin-top: 30px !important;
}
	
div.container-fluid {
	margin-top: 30px !important;
}
	
	.dark-mode div.container.mt-5 {
		background-color: #1c1c1c !important;
    	zoom: 80% !important;
}

.dark-mode div.container.d-flex.justify-content-center {
	background-color: #212121 !important;
}
	
	.dark-mode thead tr th {
		background-color: #212121 !important;
		color: #ffffff !important;
  
}

	.dark-mode tbody tr td {
		background-color: #212121 !important;
		color: #ffffff !important;
}
	.dark-mode div.container.my-5 {
  		background-color: #1c1c1c !important;
}

	.dark-mode .card  {
		background-color: #262626 !important;
}

	.dark-mode div.card-header {
		background-color: #1a1a1a !important;
}
	pre.code-content {
  overflow-y: hidden !important;
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
</style>