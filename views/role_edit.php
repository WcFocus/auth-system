<?php
require_once('../core/Auth.php');
require_once('../models/Role.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión si no está activa
}

// Verificar si el usuario está autenticado
if (!Auth::check()) {
    header("Location: ../views/login.php");
    exit();
}

$user = Auth::getUser();

// Verificar si el usuario tiene permisos de administrador o superior
if (!in_array($user->role_id, [1, 2])) {
    echo "<h3>No tienes permisos para acceder a esta página.</h3>";
    exit();
}

// Verificar si se ha enviado el ID del rol a través de la URL
if (isset($_GET['id'])) {
    $roleId = $_GET['id'];
    // Obtener el rol de la base de datos
    $role = Role::findById($roleId);

    if (!$role) {
        echo "<h3>Rol no encontrado.</h3>";
        exit();
    }
} else {
    echo "<h3>Error: ID de rol no proporcionado.</h3>";
    exit();
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el nuevo nombre del rol
    $roleName = htmlspecialchars(trim($_POST['role_name']));

    if (empty($roleName)) {
        echo "<h3>Error: El nombre del rol no puede estar vacío.</h3>";
    } else {
        // Actualizar el rol en la base de datos
        if (Role::update($roleId, $roleName)) {
            header("Location: role_list.php");
            exit();
        } else {
            echo "<h3>Error al actualizar el rol. Por favor, inténtalo de nuevo.</h3>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
        }
        .container {
            max-width: 600px;
        }
        h1 {
            font-size: 2rem;
            color: #343a40;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-custom {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Rol</h1>
        <form method="POST" action="role_edit.php?id=<?php echo $roleId; ?>" class="border p-4 shadow-sm rounded bg-white">
            <div class="mb-3">
                <label for="role_name" class="form-label">Nombre del Rol:</label>
                <input type="text" name="role_name" class="form-control" value="<?php echo htmlspecialchars($role->role_name); ?>" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success btn-custom">Actualizar Rol</button>
                <a href="role_list.php" class="btn btn-secondary btn-custom">Volver a la lista de roles</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
