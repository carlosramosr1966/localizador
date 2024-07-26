<?php
session_start();
require 'db.php';

// Comprobar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Actualizar ubicación
if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $stmt = $pdo->prepare("INSERT INTO location_logs (user_id, latitude, longitude) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $latitude, $longitude]);
}

// Obtener ubicación más reciente
$stmt = $pdo->prepare("SELECT latitude, longitude FROM location_logs WHERE user_id = ? ORDER BY log_time DESC LIMIT 1");
$stmt->execute([$user_id]);
$location = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function updateLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    document.getElementById('location-form').submit();
                });
            } else {
                alert("Geolocalización no soportada.");
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            updateLocation();
            setInterval(() => {
                document.getElementById('time').textContent = new Date().toLocaleTimeString('es-ES');
                document.getElementById('date').textContent = new Date().toLocaleDateString('es-ES');
            }, 1000);
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Panel Principal</h1>
        <div id="time" class="info"></div>
        <div id="date" class="info"></div>
        <div id="location" class="info">
            <?php if ($location): ?>
                Latitud: <?= htmlspecialchars($location['latitude']) ?>, Longitud: <?= htmlspecialchars($location['longitude']) ?>
            <?php else: ?>
                Ubicación no disponible
            <?php endif; ?>
        </div>

        <?php if ($role === 'admin'): ?>
            <h2>Gestión de Usuarios</h2>
            <!-- Aquí podrías añadir funcionalidades para la gestión de usuarios -->
            <p><a href="logout.php">Finalice</a> sesión aquí!.></p>
        <?php endif; ?>

        <form id="location-form" method="POST" style="display: none;">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </form>
    </div>
</body>
</html>
