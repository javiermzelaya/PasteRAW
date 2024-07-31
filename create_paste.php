<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $user_id = $_SESSION['user_id']; // Obtener el user_id de la sesión

    // Validar los datos
    if (empty($title) || empty($content)) {
        echo "Title and content cannot be empty.";
    } else {
        // Preparar la consulta de inserción
        $stmt = $pdo->prepare('INSERT INTO pastes (title, content, user_id, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$title, $content, $user_id]);

        // Redirigir al usuario a la vista del paste creado
        $paste_id = $pdo->lastInsertId();
        header("Location: view.php?id=" . $paste_id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Paste</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>Create a new Paste</h2>
    <form action="create_paste.php" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" class="form-control" rows="10" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Paste</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const toggle = document.getElementById('dark-mode-toggle');
        const currentTheme = localStorage.getItem('theme') || 'light';

        if (currentTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }

        toggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const theme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        });
    });
</script>
</body>
</html>