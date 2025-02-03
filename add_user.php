<?php
session_start();
require 'config.php'; // Incluir la configuración global

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener la configuración del sitio
$site_name = $settings['title'] ?? 'Admin Panel';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanitizar entradas del formulario
    $username = trim($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    if ($username && $email && $password && $role) {
        try {
            // Verificar si el correo electrónico ya existe
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $email_exists = $stmt->fetchColumn();

            if ($email_exists) {
                $error_message = "El correo electrónico ya está registrado.";
            } else {
                // Insertar el nuevo usuario en la base de datos
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
                $stmt->execute([$username, $email, $hashed_password, $role]);
                header('Location: manage_users.php');
                exit;
            }
        } catch (PDOException $e) {
            $error_message = "Error al agregar el usuario: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $error_message = "Por favor, completa todos los campos correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - <?= htmlspecialchars($site_name) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1e1e1e;
            color: #fff;
        }

        .container {
            background-color: #2c2c2c;
            padding: 20px;
            border-radius: 8px;
            margin-top: 50px;
        }

        .form-control {
            background-color: #3a3a3a;
            color: #fff;
            border: 1px solid #555;
        }

        .form-control:focus {
            border-color: #007bff;
            background-color: #3a3a3a;
            color: #fff;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .alert {
            background-color: #d9534f;
            color: #fff;
            border-radius: 4px;
            padding: 10px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container-fluid">
    <h2 class="mt-5">Add User</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form action="add_user.php" method="post">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label for="role">Role:</label>
                <select id="role" name="role" class="form-control" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php include 'footbar.php'; ?>
</body>
</html>