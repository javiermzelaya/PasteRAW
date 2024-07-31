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
        .dark-mode h1, .dark-mode h2 {
            color: #ffffff;
        }
        .dark-mode .form-group label {
            color: #cccccc;
        }
        .dark-mode .form-control {
            background-color: #3a3a3a;
            color: #ffffff;
            border: 1px solid #555555;
            border-radius: 5px;
        }
        .dark-mode .form-control:focus {
            background-color: #3a3a3a;
            color: #ffffff;
            border-color: #007bff;
            box-shadow: none;
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
        .dark-mode .table {
            background-color: #2c2c2c;
            color: #ffffff;
        }
        .dark-mode .table thead th {
            background-color: #3a3a3a;
            border-bottom: 1px solid #4a4a4a;
        }
        .dark-mode .table tbody tr {
            border-top: 1px solid #4a4a4a;
        }
        .dark-mode .table tbody tr:nth-child(even) {
            background-color: #2c2c2c;
        }
        .dark-mode .table tbody tr:nth-child(odd) {
            background-color: #3a3a3a;
        }
        .dark-mode .table .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000000;
        }
        .dark-mode .table .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
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
        .dark-mode .table .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #ffffff;
        }
        .dark-mode .table .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        table.table.table-hover {
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
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4">User Panel</h1>

    <h2>Profile</h2>
    <form action="user_panel.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>