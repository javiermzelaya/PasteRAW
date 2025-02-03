<?php
session_start();
require 'config.php'; // Incluir la configuración global

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener datos del usuario
$stmt = $pdo->prepare('SELECT username, email, role FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // Manejar caso donde el usuario no se encuentra
    echo "User not found.";
    exit;
}

$username = $user['username'];
$email = $user['email'];
$role = $user['role']; // Aquí se obtiene el rol del usuario

// Verificar si el usuario es administrador
$is_admin = ($role === 'admin');

// Si el administrador está accediendo a esta página, obtener todos los archivos creados por todos los usuarios
if ($is_admin) {
    // Obtener los archivos de todos los usuarios
    $stmt = $pdo->query('SELECT p.id, p.title, p.created_at, u.username FROM pastes p JOIN users u ON p.user_id = u.id');
    $pastes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Obtener solo los archivos del usuario actual
    $stmt = $pdo->prepare('SELECT id, title, created_at FROM pastes WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $pastes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_admin) {
    // Solo los usuarios no administradores pueden actualizar su perfil
    if (isset($_POST['update_profile'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
        $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), $user_id]);
    }
}

// Obtener el título y logo del sitio desde la base de datos
$stmt = $pdo->query('SELECT title, logo_filename FROM settings LIMIT 1');
$settings = $stmt->fetch();
$title = $settings['title'];
$logo_filename = $settings['logo_filename'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $is_admin ? 'Admin Panel' : 'User Panel' ?> - <?= htmlspecialchars($username) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-title {
            font-weight: bold;
        }
        .btn-actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container-fluid">
        <?php if ($is_admin): ?>
            <h1 class="mb-4">Admin Panel</h1>
        <?php else: ?>
            <h1 class="mb-4">User Panel</h1>
        <?php endif; ?>

        <!-- Mostrar perfil solo si es usuario -->
        <?php if (!$is_admin): ?>
            <h2>Profile</h2>
            <form action="user_panel.php" method="post">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary mt-3">Update Profile</button>
            </form>
        <?php endif; ?>

        <h2 class="mt-5"><?= $is_admin ? 'All Pastes' : 'My Pastes' ?></h2>
        <div class="row">
            <?php foreach ($pastes as $paste): ?>
                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($paste['title']) ?></h5>
                            <p class="card-text"><strong>Created At:</strong> <?= htmlspecialchars($paste['created_at']) ?></p>
                            <?php if ($is_admin): ?>
                                <p class="card-text"><strong>Username:</strong> <?= htmlspecialchars($paste['username']) ?></p>
                            <?php endif; ?>
                            <div class="btn-actions">
                                <a href="view.php?id=<?= $paste['id'] ?>" class="btn btn-info btn-sm">View</a>
                                <a href="edit_paste.php?id=<?= $paste['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_paste.php?id=<?= $paste['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this paste?');">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'footbar.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>