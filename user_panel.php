<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$stmt = $pdo->prepare('SELECT username, email FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // Handle the case where the user is not found
    echo "User not found.";
    exit;
}

$username = $user['username'];
$email = $user['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
        $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), $user_id]);
    }
}

// Obtener el título y el logo del sitio desde la base de datos
$stmt = $pdo->query('SELECT title, logo_filename FROM settings LIMIT 1');
$settings = $stmt->fetch();
$title = $settings['title'];
$logo_filename = $settings['logo_filename'];

// Obtener los pastes del usuario
$stmt = $pdo->prepare('SELECT id, title, created_at FROM pastes WHERE user_id = ?');
$stmt->execute([$user_id]);
$pastes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel - <?= htmlspecialchars($username) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url(https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap);
        body {
            font-family: Poppins;
        }

        .dark-mode thead tr th {
            border: 1px solid #4a4a4a !important;
        }

        .dark-mode tbody tr td {
            border: 1px solid #4a4a4a !important;
        }
        thead tr th {
            border: 1px solid #d9d9d9 !important;
        }

        tbody tr td {
            border: 1px solid #d9d9d9 !important;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container-fluid">
        <h1 class="mb-4">User Panel</h1>

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

        <h2 class="mt-5">My Pastes</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pastes as $paste): ?>
                    <tr>
                        <td><?= htmlspecialchars($paste['id']) ?></td>
                        <td><?= htmlspecialchars($paste['title']) ?></td>
                        <td><?= htmlspecialchars($paste['created_at']) ?></td>
                        <td>
                            <a href="view.php?id=<?= $paste['id'] ?>" class="btn btn-info btn-sm">View</a>
                            <a href="edit_paste.php?id=<?= $paste['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_paste.php?id=<?= $paste['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this paste?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footbar.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
