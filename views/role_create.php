<?php
// Incluir las clases necesarias
require_once('../core/Auth.php');
require_once('../models/Role.php');

// Verificar si el usuario está autenticado y tiene permisos de administrador
if (!Auth::check() || Auth::getUser()->role_id != 1) {
    header("Location: login.php");  // Si no está autenticado, redirigir al login
    exit();
}

// Procesar el formulario de creación de rol
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir los datos del formulario
    $roleName = $_POST['role_name'];

    // Crear el nuevo rol
    $roleCreated = Role::create($roleName);

    // Redirigir al listado de roles después de crear el rol
    if ($roleCreated) {
        header("Location: role_list.php");  // Redirigir a la lista de roles
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>Error al crear el rol. Por favor, inténtalo de nuevo.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Rol</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="width: 25rem;">
        <h2 class="text-center mb-4">Crear Rol</h2>
        <form method="POST" action="role_create.php">
            <div class="mb-3">
                <label for="role_name" class="form-label">Nombre del Rol:</label>
                <input type="text" name="role_name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2">Crear Rol</button>
            <a href="../public/dashboard.php" class="btn btn-secondary w-100">Volver al Dashboard</a>
        </form>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
