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
		
        body {
            font-family: Poppins;
        }
    </style>
</head>
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
<?php include 'footbar.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>