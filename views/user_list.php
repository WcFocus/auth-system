<?php
require_once('../core/Auth.php');
require_once('../models/User.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión si no está activa
}

// Verificar si el usuario está autenticado y tiene permisos de SuperAdmin o Admin
$user = Auth::getUser();
if (!$user || !in_array($user->role_id, [1, 2])) { // SuperAdmin o Admin
    header("Location: ../views/login.php");
    exit();
}

// Obtener todos los usuarios
$users = User::getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Lista de Usuarios</h2>
            </div>
            <div class="card-body">
                <?php if ($users && count($users) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo Electrónico</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['id']); ?></td>
                                        <td><?= htmlspecialchars($user['name']); ?></td>
                                        <td><?= htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?= $user['role_id'] == 1 ? 'SuperAdmin' : ($user['role_id'] == 2 ? 'Admin' : 'Usuario'); ?>
                                        </td>
                                        <td>
                                            <a href="user_edit.php?id=<?= $user['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                            <?php if ($user['role_id'] != 1): ?>
                                                <a href="user_delete.php?id=<?= $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">Eliminar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center">No hay usuarios registrados.</p>
                <?php endif; ?>
            </div>
            <div class="card-footer text-end">
                <a href="user_create.php" class="btn btn-success">Crear Nuevo Usuario</a>
                <a href="../public/dashboard.php" class="btn btn-secondary">Volver al Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
