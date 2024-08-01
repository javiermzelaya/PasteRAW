<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Insertar el nuevo usuario en la base de datos
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$username, $email, $password, $role]);

    header('Location: manage_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add User - <?= htmlspecialchars($site_name) ?></title>
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
            background-color: #121212;
            color: #e0e0e0;
            font-family: Poppins;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #1e1e1e;
            border-bottom: 1px solid #333333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }

        .container {
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        h1, h2 {
            color: #ffffff;
            font-weight: 700;
        }

        .form-group label {
            color: #b0b0b0;
            font-weight: 500;
        }

        .form-control {
            background-color: #333333;
            color: #e0e0e0;
            border: 1px solid #555555;
            border-radius: 5px;
            padding: 10px;
        }

        .form-control:focus {
            background-color: #333333;
            color: #e0e0e0;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.2s, border-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .table {
            background-color: #1e1e1e;
            color: #e0e0e0;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            width: 100%;
            margin: 20px 0;
        }

        .table thead th {
            background-color: #2c2c2c;
            border-bottom: 2px solid #3a3a3a;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #e0e0e0;
        }

        .table tbody tr {
            border: 1px solid #3a3a3a;
            border-radius: 5px;
            background-color: #2c2c2c;
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: #424242;
        }

        .table tbody tr:nth-child(even) {
            background-color: #2c2c2c;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #333333;
        }

        .table .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #000000;
            font-weight: 500;
        }

        .table .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .table .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #ffffff;
            font-weight: 500;
        }

        .table .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>Add User</h2>
    <form action="add_user.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Role:</label>
            <select id="role" name="role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add User</button>
    </form>
</div>
<?php include 'footbar.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>