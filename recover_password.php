<?php
session_start();
require 'config.php'; // Incluir la configuración global
$site_name = $settings['title'];
$footer_legend = $settings['footer_legend'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = $_POST['username_or_email'];
    $recovery_phrase = $_POST['recovery_phrase'];
    
    // Buscar usuario por nombre de usuario o email
    $stmt = $pdo->prepare('SELECT * FROM users WHERE (username = ? OR email = ?) AND recovery_phrase = ?');
    $stmt->execute([$username_or_email, $username_or_email, $recovery_phrase]);
    $user = $stmt->fetch();

    if ($user) {
        // Generar nueva contraseña aleatoria
        $new_password = bin2hex(random_bytes(4)); // Genera una nueva contraseña de 8 caracteres
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Actualizar la contraseña del usuario
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hashed_password, $user['id']]);

        $message = 'Your new password is: ' . htmlspecialchars($new_password);
    } else {
        $error = 'Invalid username/email or recovery phrase.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Password - <?= htmlspecialchars($site_name) ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
            font-family: Poppins;
        }
        .recover-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .recover-container h2 {
            margin-bottom: 20px;
        }
        .recover-container .form-group {
            margin-bottom: 15px;
        }
        .recover-container .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .recover-container .alert {
            margin-bottom: 20px;
        }
        .recover-container a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .recover-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="recover-container">
    <h2>Recover Password</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form action="recover_password.php" method="post">
        <div class="form-group">
            <label for="username_or_email">Username or Email:</label>
            <input type="text" id="username_or_email" name="username_or_email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="recovery_phrase">Recovery Phrase:</label>
            <input type="text" id="recovery_phrase" name="recovery_phrase" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Recover Password</button>
    </form>
    <a href="login.php">Back to Login</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>