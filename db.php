<?php
$host = 'localhost';
$dbname = 'user_management';
$username = 'root'; // Cambiar si el usuario es diferente
$password = ''; // Cambiar si la contraseña es diferente

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
//INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES ('1', 'CARR', '12345', 'admin', CURRENT_TIMESTAMP);
?>
