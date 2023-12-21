<?php
$host = 'localhost';
$dbname = 'id21678829_spu111phphoosting';
$user = 'id21678829_spu111phphoosting';
$pass = '########';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>Connected successfully</h3>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
