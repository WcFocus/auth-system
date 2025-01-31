<?php

class Role{
    public $id;
    public $role_name;
// Buscar un rol por su ID
public static function findById($id) {
    $db = Database::getConnection();
    $stmt = $db->prepare('SELECT * FROM ROLES WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_object(self::class);
}

// Buscar un rol por su nombre
public static function findByName($name) {
    $db = Database::getConnection();
    $stmt = $db->prepare('SELECT * FROM ROLES WHERE role_name = ?');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_object(self::class);
}

// Obtener todos los roles
public static function getAll() {
    $db = Database::getConnection();  // Obtener la conexión a la base de datos
    $stmt = $db->prepare('SELECT * FROM ROLES');  // Asegúrate de que la tabla en tu base de datos se llama 'ROLES'
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);  // Retorna todos los roles en formato asociativo
}
// Crear un nuevo rol
public static function create($role_name) {
    $db = Database::getConnection();
    $stmt = $db->prepare('INSERT INTO ROLES (role_name) VALUES (?)');
    $stmt->bind_param('s', $role_name);
    return $stmt->execute();  // Devuelve true si la inserción fue exitosa
}
// Eliminar un rol por su ID
public static function delete($id) {
    $db = Database::getConnection();
    $stmt = $db->prepare('DELETE FROM ROLES WHERE id = ?');
    $stmt->bind_param('i', $id);
    return $stmt->execute();  // Devuelve true si la eliminación fue exitosa
}
 // Clase Role (en el archivo 'Role.php')
 public static function update($id, $roleName) {
    $db = Database::getConnection();
    $stmt = $db->prepare('UPDATE roles SET role_name = ? WHERE id = ?');

    if (!$stmt) {
        error_log("Error en la consulta update: " . $db->error);
        return false;
    }

    $stmt->bind_param('si', $roleName, $id);
    return $stmt->execute();
}

}


?>