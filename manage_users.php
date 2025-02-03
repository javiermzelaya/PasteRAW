<?php
session_start();
require 'vendor/autoload.php';
require 'config.php'; // Configuración global

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Configuración de la conexión a la base de datos
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Error en la conexión a la base de datos: " . $e->getMessage());
}

// Manejo de agregar usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])) {
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = trim($_POST['role']);

    if ($username && $email && $password && $role) {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, $password, $role]);
    }
}

// Manejo de eliminación de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user_id'])) {
    $delete_user_id = (int)$_POST['delete_user_id'];

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('DELETE FROM pastes WHERE user_id = ?');
        $stmt->execute([$delete_user_id]);

        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$delete_user_id]);

        $pdo->commit();
    } catch (\Exception $e) {
        $pdo->rollBack();
        die("Error al eliminar el usuario: " . $e->getMessage());
    }
}

// Manejo de actualización de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user_id'], $_POST['edit_username'], $_POST['edit_email'], $_POST['edit_role'])) {
    $edit_user_id = (int)$_POST['edit_user_id'];
    $edit_username = trim($_POST['edit_username']);
    $edit_email = filter_var(trim($_POST['edit_email']), FILTER_VALIDATE_EMAIL);
    $edit_role = trim($_POST['edit_role']);

    if ($edit_user_id && $edit_username && $edit_email && $edit_role) {
        if (!empty($_POST['edit_password'])) {
            $edit_password = password_hash(trim($_POST['edit_password']), PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?');
            $stmt->execute([$edit_username, $edit_email, $edit_password, $edit_role, $edit_user_id]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?');
            $stmt->execute([$edit_username, $edit_email, $edit_role, $edit_user_id]);
        }
    }
}

// Obtener todos los usuarios
$stmt = $pdo->query('SELECT * FROM users');
$users = $stmt->fetchAll();

// Obtener configuraciones del sitio
$stmt = $pdo->query('SELECT setting_key, setting_value FROM settings');
$settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
$footer_legend = $settings['footer_legend'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - <?= htmlspecialchars($settings['site_name'] ?? 'Admin Panel') ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
	<link href="styles.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container-fluid">
        <h2 class="mt-5">Add New User</h2>
        <form action="" method="post">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>

        <h2 class="mt-5">Existing Users</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <form action="" method="post" class="d-inline">
                                <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            <button class="btn btn-warning btn-sm" onclick="editUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>', '<?= htmlspecialchars($user['email']) ?>', '<?= htmlspecialchars($user['role']) ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function editUser(id, username, email, role) {
            // Lógica para rellenar modal con datos de usuario (opcional)
        }
    </script>
	<?php include 'footbar.php'; ?>
</body>
</html>