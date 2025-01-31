<?php
require_once(__DIR__ . '/../core/Auth.php');  // Usamos __DIR__ para la ruta
require_once(__DIR__ . '/../models/User.php'); // Usamos __DIR__ para la ruta

class AuthController {
    // Método para mostrar el formulario de login y manejar el login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Intentar hacer login usando la clase Auth
            if (Auth::login($email, $password)) {
                // Si el login es exitoso, obtenemos los datos del usuario
                $user = User::findByEmail($email);
                
                // Guardamos la información del usuario y su rol en la sesión
                session_start();
                $_SESSION['user_id'] = $user->id;
                $_SESSION['role_id'] = $user->role_id;
                $_SESSION['email'] = $user->email;

                // Redirigimos al dashboard dependiendo del rol
                if ($user->role_id == 1) {  // 1 es el SuperAdmin
                    header('Location: dashboard.php');
                } elseif ($user->role_id == 2) {  // 2 es el Admin
                    header('Location: dashboard.php');
                } else {  // Usuario normal
                    header('Location: dashboard.php');
                }
                exit();
            } else {
                echo "Invalid login credentials!";
            }
        } else {
            require_once(__DIR__ . '/../public/index.php');  // Corregir ruta
        }
    }

    // Método para logout y destruir la sesión
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }
}





?>