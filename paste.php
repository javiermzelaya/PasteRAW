<?php
require 'vendor/autoload.php';
require 'config.php'; // Incluir la configuración global

use PDO;


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];
    $stmt = $pdo->prepare('SELECT * FROM pastes WHERE slug = ?');
    $stmt->execute([$slug]);
    $paste = $stmt->fetch();
    if ($paste) {
        if (isset($_GET['raw'])) {
            header('Content-Type: text/plain');
            echo $paste['content'];
            exit;
        } else {
            echo "<h1>{$paste['title']}</h1>";
            echo "<pre>{$paste['content']}</pre>";
        }
    } else {
        echo "Paste not found";
        exit;
    }
} else {
    echo "Invalid request";
    exit;
}
?>
