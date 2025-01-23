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
    echo "User not found.";
    exit;
}

$username = $user['username'];
$email = $user['email'];

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
        $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), $user_id]);
        header('Location: user_panel.php'); // Refresh to prevent resubmission
        exit;
    }
}

// Fetch user pastes
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
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #343a40;
            color: #fff;
            font-weight: 600;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container-fluid">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-header">Profile Information</div>
            <div class="card-body">
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
            </div>
        </div>

        <!-- Pastes Card -->
        <div class="card">
            <div class="card-header">My Pastes</div>
            <div class="card-body">
                <?php if (!empty($pastes)): ?>
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
                <?php else: ?>
                    <p class="text-muted">You don't have any pastes yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footbar.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
