<?php

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




}
?>