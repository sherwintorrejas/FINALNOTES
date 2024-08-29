<?php
require 'vendor/autoload.php'; 

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dbConnection = $_ENV['DB_CONNECTION'];
$servername = $_ENV['DB_HOST'];
$dbPort = $_ENV['DB_PORT'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_DATABASE'];

try {
    $dsn = "$dbConnection:host=$servername;port=$dbPort;dbname=$dbname";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    // Echo credentials for debugging (remove in production)
    echo "Connection: " . htmlspecialchars($dbConnection) . "<br>";
    echo "Host: " . htmlspecialchars($servername) . "<br>";
    echo "Port: " . htmlspecialchars($dbPort) . "<br>";
    echo "Username: " . htmlspecialchars($username) . "<br>";
    echo "Database: " . htmlspecialchars($dbname) . "<br>";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>