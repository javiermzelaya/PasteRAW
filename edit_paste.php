<?php
session_start();
require 'config.php'; // Incluir la configuración global

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$paste_id = $_GET['id'];
$message = '';

// Obtener el paste
$stmt = $pdo->prepare('SELECT * FROM pastes WHERE id = :id AND user_id = :user_id');
$stmt->execute(['id' => $paste_id, 'user_id' => $user_id]);
$paste = $stmt->fetch();

if (!$paste) {
    header('Location: user_panel.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare('UPDATE pastes SET title = :title, content = :content WHERE id = :id');
    if ($stmt->execute(['title' => $title, 'content' => $content, 'id' => $paste_id])) {
        $message = 'Paste updated successfully';
    } else {
        $message = 'Error updating paste';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paste</title>
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
        .alert-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #ffffff;
        }
    </style>
</head>
<body class="dark-mode">
<?php include 'navbar.php'; ?>
<div class="container mt-5">
    <h1>Edit Paste</h1>
    <?php if (!empty($message)): ?>
        <p class="alert alert-info"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form action="edit_paste.php?id=<?= htmlspecialchars($paste_id) ?>" method="POST">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" class="form-control" name="title" value="<?= htmlspecialchars($paste['title']) ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" class="form-control" name="content" rows="10" required><?= htmlspecialchars($paste['content']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>