<?php
require_once(__DIR__ . '/../models/User.php'); // Asegúrate de que la ruta al archivo User.php es correcta

// Verificar si la ID del usuario está presente en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Intentar eliminar el usuario
    $deleted = User::delete($userId);

    if ($deleted) {
        header('Location: user_list.php'); // Redirige de vuelta al listado de usuarios
        exit();
    } else {
        echo "<h3>Error al eliminar el usuario.</h3>";
    }
} else {
    echo "<h3>ID de usuario inválido o no proporcionado.</h3>";
}
?>
