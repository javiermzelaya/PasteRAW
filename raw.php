<?php
require 'vendor/autoload.php';
require 'config.php'; // Incluir la configuración global

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

if (!isset($_GET['id'])) {
    echo "No paste ID provided.";
    exit;
}

$paste_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT content FROM pastes WHERE id = ?');
$stmt->execute([$paste_id]);
$paste = $stmt->fetch();

if (!$paste) {
    echo "Paste not found.";
    exit;
}

header('Content-Type: text/plain');
echo $paste['content'];