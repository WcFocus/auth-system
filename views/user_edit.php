<?php
require_once('../core/Auth.php');
require_once('../models/User.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Iniciar sesión si no está activa
}

// Verificar si el usuario está autenticado
$user = Auth::getUser();
if (!$user) {
    header("Location: ../views/login.php");
    exit();
}

// Verificar si se ha proporcionado un ID válido en el GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: user_list.php");
    exit();
}

// Obtener el usuario por su ID
$userToEdit = User::findById($_GET['id']);
if (!$userToEdit) {
    echo "<h3>El usuario no existe.</h3>";
    exit();
}

// Verificar si el usuario logueado es el mismo que el que está editando
if ($user->role_id != 1 && $user->id != $userToEdit->id) {
    // Si no es un SuperAdmin, solo puede editar su propio perfil
    header("Location: user_list.php");
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
    if (!$email || empty($name)) {
        echo "<h3>Error: Todos los campos son obligatorios y deben ser válidos.</h3>";
    } else {
        // Si el usuario es Admin, no puede cambiar el rol a SuperAdmin
        if ($user->role_id == 2 && $role_id == 1) {
            echo "<h3>Error: Un Admin no puede cambiar el rol a SuperAdmin.</h3>";
        } elseif ($role_id == $userToEdit->role_id) {
            // Si el rol no cambia, no es necesario actualizarlo
            $role_id = $userToEdit->role_id;
        } else {
            // Validar cambios de rol (esto ya lo estás manejando correctamente)
            if ($user->role_id == 2 && $role_id == 1) {
                echo "<h3>Error: Un Admin no puede cambiar el rol a SuperAdmin.</h3>";
                exit();
            }
        }

        // Si no se proporciona una nueva contraseña, se conserva la anterior
        if (empty($password)) {
            $password = $userToEdit->password; // No cambiar la contraseña
        } else {
            $password = password_hash($password, PASSWORD_BCRYPT); // Hashear la nueva contraseña
        }

        // Actualizar el usuario
        $userUpdated = User::update($userToEdit->id, $name, $email, $password, $role_id);

        if ($userUpdated) {
            header("Location: user_list.php");
            exit();
        } else {
            echo "<h3>Error al actualizar el usuario. Por favor, inténtalo de nuevo.</h3>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Editar Usuario</h1>
        <form method="POST" action="user_edit.php?id=<?= $userToEdit->id ?>" class="border p-4 shadow-sm rounded">
            <div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($userToEdit->name) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userToEdit->email) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" name="password" class="form-control" placeholder="Deja en blanco si no deseas cambiarla">
            </div>
            
            <?php if ($user->role_id == 1): ?>
                <!-- Solo el SuperAdmin puede cambiar el rol -->
                <div class="mb-3">
                    <label for="role_id" class="form-label">Rol:</label>
                    <select name="role_id" class="form-select" required>
                        <option value="1" <?= $userToEdit->role_id == 1 ? 'selected' : '' ?>>Superadmin</option>
                        <option value="2" <?= $userToEdit->role_id == 2 ? 'selected' : '' ?>>Admin</option>
                        <option value="3" <?= $userToEdit->role_id == 3 ? 'selected' : '' ?>>Usuario</option>
                    </select>
                </div>
            <?php elseif ($user->role_id == 2 && $user->id == $userToEdit->id): ?>
                <!-- El Admin no puede cambiar su rol a SuperAdmin -->
                <input type="hidden" name="role_id" value="<?= $userToEdit->role_id ?>">
            <?php elseif ($user->role_id == 3 && $user->id == $userToEdit->id): ?>
                <!-- Un Usuario solo puede ver y editar su propio perfil -->
                <input type="hidden" name="role_id" value="<?= $userToEdit->role_id ?>">
            <?php endif; ?>
            
            <button type="submit" class="btn btn-success">Actualizar Usuario</button>
            <a href="./user_list.php" class="btn btn-secondary">Volver a la lista</a>
        </form>
    </div>
</body>
</html>
