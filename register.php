<?php
session_start();
require 'config.php'; // Incluir la configuración global
$site_name = $settings['title'];
$footer_legend = $settings['footer_legend'];

function generateRecoveryPhrase() {
    $words = file('words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    shuffle($words);
    return implode(' ', array_slice($words, 0, 12));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $recovery_phrase = generateRecoveryPhrase();

    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, recovery_phrase) VALUES (?, ?, ?, ?)');
    $stmt->execute([$username, $email, $password, $recovery_phrase]);

    $message = 'User registered successfully. Your recovery phrase is: <div class="alert alert-info mt-3">' . htmlspecialchars($recovery_phrase) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?= htmlspecialchars($site_name) ?></title>
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
        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .register-container h2 {
            margin-bottom: 20px;
        }
        .register-container .form-group {
            margin-bottom: 15px;
        }
        .register-container .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        .register-container .alert {
            margin-bottom: 20px;
        }
        .register-container a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
        .recovery-phrase {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2>Register</h2>
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <form action="register.php" method="post">
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
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <a href="recover_password.php">Forgot Password?</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>