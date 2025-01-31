<?php
require_once(__DIR__ . '/../core/Database.php'); // Asegurar la correcta inclusión de Database.php
require_once(__DIR__ . '/../core/Auth.php');
class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $role_id;
    public $role_name;  // Añado el campo para el nombre del rol

// Busca un usuario por su email
public static function findByEmail($email) {
    $db = Database::getConnection();
    if (!$db) {
        error_log("Error en la conexión a la base de datos");
        return null;
    }
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log("Email inválido: $email");
        return null;
    }

    $stmt = $db->prepare('SELECT u.*, r.role_name FROM user u JOIN roles r ON u.role_id = r.id WHERE u.email = ?');
    if (!$stmt) {
        error_log("Error en la consulta findByEmail: " . $db->error);
        return null;
    }
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_object(self::class);
}

// Busca un usuario por su ID
public static function findById($id) {
    $db = Database::getConnection();
    if (!$db) {
        error_log("Error en la conexión a la base de datos");
        return null;
    }

    $stmt = $db->prepare('SELECT u.*, r.role_name FROM user u JOIN roles r ON u.role_id = r.id WHERE u.id = ?');
    if (!$stmt) {
        error_log("Error en la consulta findById: " . $db->error);
        return null;
    }
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_object(self::class);
}

    // Obtiene todos los usuarios, solo los Admins y SuperAdmins pueden verlos
    public static function getAll() {
        $db = Database::getConnection();
        if (!$db) {
            error_log("Error en la conexión a la base de datos");
            return [];
        }
        
        // Verificar si el usuario tiene el rol adecuado para acceder
        if (Auth::hasRole(1) || Auth::hasRole(2)) { // SuperAdmin o Admin
            $stmt = $db->prepare('SELECT u.id, u.name, u.email, u.role_id, r.role_name FROM user u JOIN roles r ON u.role_id = r.id');
            if (!$stmt) {
                error_log("Error en la consulta getAll: " . $db->error);
                return [];
            }
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return []; // Si no tiene permisos, no devuelve usuarios
    }

// Crea un nuevo usuario
public static function create($name, $email, $password, $role_id) {
    $db = Database::getConnection();
    if (!$db) {
        error_log("Error en la conexión a la base de datos");
        return false;
    }

    // Verificar si el email ya está registrado
    if (self::findByEmail($email)) {
        error_log("Intento de creación con email duplicado: $email");
        return false;
    }

    // Hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta
    $stmt = $db->prepare('INSERT INTO user (name, email, password, role_id) VALUES (?, ?, ?, ?)');
    if (!$stmt) {
        error_log("Error en la consulta prepare: " . $db->error);
        return false;
    }

    // Vincular los parámetros
    $stmt->bind_param('sssi', $name, $email, $hashedPassword, $role_id);

    // Intentar ejecutar la consulta
    if (!$stmt->execute()) {
        error_log("Error al ejecutar la consulta create: " . $stmt->error);
        return false;
    }

    return true;
}
// Actualiza la información de un usuario
public static function update($id, $name, $email, $password, $role_id) {
    $db = Database::getConnection();
    if (!$db) {
        error_log("Error en la conexión a la base de datos");
        return false;
    }

    // Si la contraseña es proporcionada, la ciframos
    $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    // Verificar si el usuario tiene permisos para editar
    if (!Auth::canEditUser($id)) {
        error_log("No tiene permisos para editar este usuario");
        return false;
    }

    // Preparar la consulta de actualización
    if ($hashedPassword) {
        $stmt = $db->prepare('UPDATE user SET name = ?, email = ?, password = ?, role_id = ? WHERE id = ?');
        $stmt->bind_param('sssii', $name, $email, $hashedPassword, $role_id, $id);
    } else {
        $stmt = $db->prepare('UPDATE user SET name = ?, email = ?, role_id = ? WHERE id = ?');
        $stmt->bind_param('ssii', $name, $email, $role_id, $id);
    }

    // Intentar ejecutar la consulta
    return $stmt->execute();
}
// Elimina un usuario por ID, solo puede hacerlo un SuperAdmin
public static function delete($id) {
    $db = Database::getConnection();
    if (!$db) {
        error_log("Error en la conexión a la base de datos");
        return false;
    }

    // Verificar si el usuario tiene permisos para eliminar
    if (!Auth::canDeleteUser($id)) {
        error_log("No tiene permisos para eliminar este usuario");
        return false;
    }

    $stmt = $db->prepare('DELETE FROM user WHERE id = ?');
    if (!$stmt) {
        error_log("Error en la consulta delete: " . $db->error);
        return false;
    }
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}
}
?>