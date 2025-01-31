<?php
require_once('../core/Auth.php');
require_once('../models/User.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión si no está activa
}

// Verificar si el usuario está autenticado y tiene permisos de administrador
$user = Auth::getUser();
if (!$user || $user->role_id != 1) {
    header("Location: ../views/login.php");
    exit();
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar los datos del formulario
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);
    $role_id = intval($_POST['role_id']);

    // Validar datos
    if (!$email || empty($name) || empty($password)) {
        echo "<div class='alert alert-danger text-center'>Error: Todos los campos son obligatorios y deben ser válidos.</div>";
    } else {
        // Verificar si el email ya está registrado
        if (User::findByEmail($email)) {
            echo "<div class='alert alert-warning text-center'>Error: El correo electrónico ya está registrado.</div>";
        } else {
            // Crear el nuevo usuario
            $userCreated = User::create($name, $email, $password, $role_id);

            if ($userCreated) {
                header("Location: user_list.php");
                exit();
            } else {
                echo "<div class='alert alert-danger text-center'>Error al crear el usuario. Por favor, inténtalo de nuevo.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="width: 30rem;">
        <h2 class="text-center mb-4">Crear Usuario</h2>
        <form method="POST" action="user_create.php">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label for="role_id" class="form-label">Rol:</label>
                <select name="role_id" class="form-select" required>
                    <option value="2">Admin</option>
                    <option value="3">Usuario</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-success w-100 mb-2">Crear Usuario</button>
            <a href="../public/dashboard.php" class="btn btn-secondary w-100">Volver al Dashboard</a>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>