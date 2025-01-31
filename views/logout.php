<?php
// Iniciar sesión
session_start();

// Destruir todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página que deseas (en este caso, la página pública)
header("Location: http://localhost/project-breaveus/public/");
exit();
?>
