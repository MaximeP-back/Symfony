<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->usePutenv(); // Ajoutez cette ligne
$dotenv->load(__DIR__ . '/../.env');

// Afficher toutes les variables d'environnement pour le débogage
print_r($_ENV);
print_r(getenv());

$databaseUrl = getenv('DATABASE_URL');
if (!$databaseUrl) {
    die('DATABASE_URL not set');
}

$connectionParams = parse_url($databaseUrl);
$host = $connectionParams['host'];
$port = $connectionParams['port'];
$user = $connectionParams['user'];
$password = $connectionParams['pass'];
$dbname = ltrim($connectionParams['path'], '/');

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
try {
    $pdo = new PDO($dsn, $user, $password);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}