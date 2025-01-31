<?php
require_once('../core/Auth.php');
require_once('../models/Role.php');

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener el cuerpo de la solicitud en formato JSON
    $data = json_decode(file_get_contents("php://input"), true);  // Decodificar el cuerpo JSON

    if (isset($data['method']) && $data['method'] === 'DELETE' && isset($data['id'])) {
        $roleId = $data['id'];  // Obtener el ID del rol desde el cuerpo de la solicitud

        // Eliminar el rol de la base de datos
        if (Role::delete($roleId)) {
            echo json_encode(['success' => true, 'message' => 'Rol eliminado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el rol']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Método o ID de rol no proporcionado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
