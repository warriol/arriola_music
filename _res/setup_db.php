<?php
require_once __DIR__ . '/config/config.php';

try {
    // Connect without DB name first to create it if it doesn't exist
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbname = DB_NAME;
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $pdo->exec("USE `$dbname`");

    $sql = file_get_contents(__DIR__ . '/database.sql');
    $pdo->exec($sql);

    echo "Database setup completed successfully.";
} catch (PDOException $e) {
    die("ERROR: Could not setup database. " . $e->getMessage());
}
?>