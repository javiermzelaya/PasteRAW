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
            font-family: Poppins;
			margin-bottom: 80px; /* Altura estimada del footer */
    		padding-bottom: 40px; /* Espacio adicional opcional para asegurarse */
        }
		
		a.nav-link.prominent-button {
  			text-transform: uppercase;
  
		}

		nav.navbar.navbar-expand-lg.navbar-light.bg-light {
			background-color: #ffffff;
		}

		footer.footer.mt-auto.py-3.bg-light {
  			background-color: #ffffff;
		}
		
		span.text-muted {
  			color: #999999 !important;
		}

		div.container.d-flex.justify-content-center {
			background-color: #ffffff;
			color: #999999;
		}
		
		.footer {
        	position: fixed;
        	bottom: 0;
        	width: 100%;
        	padding: 1rem 0;
    	}
		footer.footer.mt-auto.py-3.bg-dark {
  			background-color: #2c2c2c !important;
  
		}
		nav.navbar.navbar-expand-lg.navbar-dark.bg-dark {
  			background-color: #2c2c2c !important;
		}
		.dark-mode div.container.d-flex.justify-content-center {
  			background-color: #1e1e1e;
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
        .dark-mode pre {
            background-color: #1e1e1e;
            color: #f8f8f2;
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