<?php
require_once('../core/Auth.php');
require_once('../models/Role.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión si no está activa
}

// Verificar si el usuario está autenticado
if (!Auth::check()) {
    header("Location: ../views/login.php");  // Redirigir al login si no está autenticado
    exit();
}

$user = Auth::getUser();

// Verificar si el usuario tiene permisos de administrador o superior
if (!in_array($user->role_id, [1, 2])) {
    echo "<h3>No tienes permisos para acceder a esta página.</h3>";
    exit();
}

// Obtener todos los roles de la base de datos
$roles = Role::getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Lista de Roles</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($role['id']); ?></td>
                        <td><?php echo htmlspecialchars($role['role_name']); ?></td>
                        <td>
                            <a href="role_edit.php?id=<?php echo $role['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                            <button onclick="deleteRole(<?php echo $role['id']; ?>)" class="btn btn-danger btn-sm">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
    async function deleteRole(roleId) {
        if (!confirm("¿Seguro que quieres eliminar este rol?")) {
            return;
        }

        const response = await fetch('../api/role_delete.php', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                method: 'DELETE',  // Especificamos el método DELETE en el cuerpo
                id: roleId  // Enviamos el ID del rol
            })
        });

        const result = await response.json().catch(error => {
            alert('Error: respuesta no válida del servidor');
            console.error(error);
        });

        if (result && result.success) {
            alert('Rol eliminado exitosamente');
            location.reload();  // Recargar la página para reflejar los cambios
        } else {
            alert('Error: ' + (result ? result.message : 'Respuesta inesperada'));
        }
    }
    </script>
</body>
</html>
