<?php
require_once(__DIR__ . '/../controllers/AuthController.php');  // Usar __DIR__ para la ruta

$authController = new AuthController();
$authController->login();
?>
