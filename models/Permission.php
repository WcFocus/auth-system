<?php
class Permission {
    public $id;
    public $role_id;
    public $permission_name;

    // Verificar si un rol tiene un permiso específico
    public static function hasPermission($role_id, $permission_name) {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM PERMISSION WHERE role_id = ? AND permission_name = ?');
        $stmt->bind_param('is', $role_id, $permission_name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
?>
