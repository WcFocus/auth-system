<?php
// Asegúrate de llamar session_start() solo una vez en la ejecución
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../models/User.php');

class Auth {
    // Método para iniciar sesión
    public static function login($email, $password) {
        $user = User::findByEmail($email);

        if (!$user) {
            // Usuario no encontrado, depuración
            echo "Usuario no encontrado";
            return false;
        }

        // Verificar la contraseña
        if (!password_verify($password, $user->password)) {
            // Contraseña incorrecta, depuración
            echo "Contraseña incorrecta";
            return false;
        }

        // Si el usuario y la contraseña son correctos, iniciar sesión
        $_SESSION['user_id'] = $user->id;
        $_SESSION['role_id'] = $user->role_id;
        $_SESSION['email'] = $user->email;
        return true;
    }

    // Verifica si el usuario está logueado
    public static function check() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role_id']);
    }

    // Obtiene el usuario autenticado
    public static function getUser() {
        if (self::check()) {
            return User::findById($_SESSION['user_id']);
        }
        return null;
    }

    // Verifica si el usuario tiene un rol específico
    public static function hasRole($role) {
        if (!self::check()) return false;

        $user = self::getUser();
        if (!$user) return false;

        return ($user->role_id == $role);
    }

    // Cierra la sesión
    public static function logout() {
        // Es buena práctica asegurarse de que la sesión esté activa antes de destruirla
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        header("Location: login.php");  // Asegúrate de que esta URL sea correcta
        exit();
    }

    // Verifica si el usuario tiene alguno de los roles pasados como parámetro
    public static function hasAnyRole(array $roles) {
        if (!self::check()) return false;

        $user = self::getUser();
        if (!$user) return false;

        return in_array($user->role_id, $roles);
    }

    // Verifica si el usuario tiene permisos para editar usuarios
    public static function canEditUser($userToEditId) {
        $user = self::getUser();
        // Los SuperAdmins pueden editar cualquier usuario
        if ($user->role_id == 1) {
            return true;
        }
        // Los Admins pueden editar cualquier usuario, pero no pueden editar a otros Admins o SuperAdmins
        if ($user->role_id == 2 && $userToEditId != $user->id && $userToEditId != 1) {
            return true;
        }
        // Los usuarios solo pueden editar su propio perfil
        return $userToEditId == $user->id;
    }

    // Verifica si el usuario tiene permisos para eliminar usuarios
    public static function canDeleteUser($userToDeleteId) {
        $user = self::getUser();
        // Solo SuperAdmins pueden eliminar usuarios
        return $user->role_id == 1;
    }

    // Verifica si el usuario tiene permisos para gestionar roles
    public static function canManageRoles() {
        $user = self::getUser();
        // Solo los SuperAdmins pueden gestionar roles
        return $user->role_id == 1;
    }
}
