<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener todos los pastes
$stmt = $pdo->query('SELECT id, title, created_at, user_id FROM pastes');
$pastes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Pastes - <?= htmlspecialchars($site_name) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
		@import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

.poppins-thin {
  font-family: "Poppins", sans-serif;
  font-weight: 100;
  font-style: normal;
}
        body {
            font-family: Poppins;
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
        .dark-mode h2 {
            color: #ffffff;
        }
        .dark-mode .table {
            background-color: #2c2c2c;
            color: #ffffff;
        }
		
        .dark-mode .table tbody tr:nth-child(even) {
            background-color: #2c2c2c;
        }
        .dark-mode .table tbody tr:nth-child(odd) {
            background-color: #3a3a3a;
        }
        .dark-mode .table .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #ffffff;
        }
        .dark-mode .table .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .dark-mode .table .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
        }
        .dark-mode .table .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
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
        div.container.mt-5 {
  border-style: none !important;
}
        thead tr th {
  border-style: none !important;
}
        tbody tr td {
  border-style: none !important;
}
    </style>
</head>
<body class="dark-mode">

<?php include 'navbar.php'; ?>
	
<div class="container mt-5">
    <h2>Manage Pastes</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Title</th>
                <th>Created At</th>
                <th>User ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pastes as $paste): ?>
                <tr>
                    <td><?= htmlspecialchars($paste['title']) ?></td>
                    <td><?= htmlspecialchars($paste['created_at']) ?></td>
                    <td><?= htmlspecialchars($paste['user_id']) ?></td>
                    <td>
                        <a href="view.php?id=<?= $paste['id'] ?>" class="btn btn-info btn-sm">View</a>
                        <a href="edit_paste.php?id=<?= $paste['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_paste.php?id=<?= $paste['id'] ?>&from_admin=1" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this paste?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>