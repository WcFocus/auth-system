<?php
class UserController {
    // Muestra todos los usuarios
    public function index() {
        if (!Auth::check()) {
            header("Location: /login.php");
            exit();
        }

        $users = User::getAll();  // Obtiene todos los usuarios
        require_once 'views/user_list.php';  // Muestra la vista con la lista de usuarios
    }

    // Actualiza la información de un usuario
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];  // Recibe el nombre
            $email = $_POST['email'];  // Recibe el email
            
            // Actualiza el usuario en la base de datos
            User::update($id, $name, $email);
        }
    }
}
?>
