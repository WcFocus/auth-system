<?php
// Iniciar sesión y cargar clases necesarias
require_once('../core/Auth.php');
require_once('../models/User.php');

// Verificar si el usuario está autenticado
if (!Auth::check()) {
    header("Location: login.php");
    exit();
}

// Obtener el usuario autenticado
$user = Auth::getUser();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Usando Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
        }
        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-body {
            padding: 1.5rem;
        }
        .btn-custom {
            width: 100%;
        }
        h1 {
            font-size: 2.5rem;
            color: #343a40;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Bienvenido al Dashboard</h1>
        
        <div class="card mb-4">
            <div class="card-header">
                Información del Usuario
            </div>
            <div class="card-body">
                <p>Hola, <strong><?php echo htmlspecialchars($user->name); ?></strong>. 
                Eres un <strong>
                <?php 
                    echo ($user->role_id == 1) ? 'Superadmin' : (($user->role_id == 2) ? 'Admin' : 'Usuario');
                ?>
                </strong>.</p>
            </div>
        </div>

        <!-- Opciones de gestión según el rol -->
        <?php if ($user->role_id == 1): ?>
            <div class="card mb-4">
                <div class="card-header">
                    Gestión de Roles y Usuarios
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="../views/role_create.php">Crear Nuevo Rol</a></li>
                        <li class="list-group-item"><a href="../views/role_list.php">Ver Lista de Roles</a></li>
                        <li class="list-group-item"><a href="../views/user_create.php">Crear Nuevo Usuario</a></li>
                        <li class="list-group-item"><a href="../views/user_list.php">Ver Lista de Usuarios</a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($user->role_id == 2): ?>
            <div class="card mb-4">
                <div class="card-header">
                    Gestión de Usuarios
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="../views/user_list.php">Ver Lista de Usuarios</a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($user->role_id == 3): ?>
            <div class="card mb-4">
                <div class="card-header">
                    Mi Perfil
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="../views/user_edit.php?id=<?php echo $user->id; ?>">Editar Mi Perfil</a></li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <a href="../views/logout.php" class="btn btn-danger btn-custom mt-4">Cerrar sesión</a>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
