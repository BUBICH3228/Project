<?php
session_start();

$host = 'MySQL-8.4';
$dbname = 'auth_system';
$user = 'root';
$password = '';
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Ошибка БД: " . $e->getMessage());
}
?>