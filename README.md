# se que debia ser privado el repositorio pero es para poder enviarlo como enlace por correo

# Sistema de Autenticación y Gestión de Usuarios

Este proyecto es un sistema de autenticación y gestión de usuarios con roles y permisos 
usando PHP puro (sin frameworks ni librerías externas) y una interfaz frontend en HTML, 
JS y CSS.

## Requisitos

- PHP 7.x o 8.1+
- MySQL
- Servidor web (Apache, Nginx, etc.)

## Instalación

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/WcFocus/auth-system.git
   cd auth-system

2. **cargar la base de datos database.sql a mysql**
   ***claves de todos los usuarios es 1234***
   wcmeloe"example.com superadmin
   admin"admin.com   admin
   user@user.com     user



***punto 1 se creo la base de datos segun lo indicado***

***punto 2 de realizo control de acceso de roles y permisos***

***realice autenticacion de usuairo pero a travez de sesiones ya que aun no tengo muy claro a traves de JWT sin librerias***

***no realice la documentacion del Endpont API porque no puede implementar en el tiempo el JWT sin embargo realice un codigo base para dar los tokens con las sesiones***

***realice las interfaces solicitadas***

***adjunto solucion de problema de compatibildiad de codigo***

#problema resuelto

6. El Problema: PHP 7 vs PHP 8.1+
## Compatibilidad PHP 7 vs PHP 8.1+

class Usuario { 
    public string $nombre; 

    public function getNombre(): string { 
        return $this->nombre; 
    } 
} 

$usuario = new Usuario(); 
echo $usuario->getNombre(); 


warning o error debido a la inicialización de una propiedad tipada. A partir de PHP 8.0, las propiedades tipadas (como public string $nombre) deben ser inicializadas antes de ser accedidas. Si no se inicializan


¿Cómo solucionar el error en PHP 8.1+?

inicialiso primero

class Usuario { 
    public string $nombre = ''; // Inicializar con un valor por defecto

    public function getNombre(): string { 
        return $this->nombre; 
    } 
} 

$usuario = new Usuario(); 
echo $usuario->getNombre(); // No generará error

e inicializo constructor
class Usuario { 
    public string $nombre; 

    public function __construct() {
        $this->nombre = ''; // Inicializar en el constructor
    }

    public function getNombre(): string { 
        return $this->nombre; 
    } 
} 

$usuario = new Usuario(); 
echo $usuario->getNombre(); // No generará error




En PHP 8.1+, las propiedades tipadas deben estar inicializadas antes de ser accedidas. Para resolver este problema, se inicializó la propiedad `$nombre` en la definición de la clase:
public string $nombre = '';