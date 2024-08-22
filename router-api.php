<?php

require_once("libs/Router.php");
require_once("api/controller/UserController.php");
require_once("api/controller/ProductController.php");
require_once("api/controller/MessageController.php");

// Cabeceras CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Custom-Header");

// Manejo de solicitudes OPTIONS (preflight request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// Crear una instancia del Router
$router = new Router();

// Definir las rutas
$router->addRoute('login', 'POST', 'UserController', 'login');
$router->addRoute('products', 'GET', 'ProductController', 'getAllProducts');
$router->addRoute('user/:email', 'GET', 'UserController', 'getUserInfo');
$router->addRoute('register', 'POST', 'UserController', 'register');
$router->addRoute('verifyEmail', 'GET', 'UserController', 'verifymail');
$router->addRoute('loginWithGoogle', 'GET', 'UserController', 'loginGoogle');
$router->addRoute('resend_verification', 'POST', 'UserController', 'resendEmailVerification');
$router->addRoute('send_whatsapp', 'POST', 'MessageController', 'sendWhatsApp');

// Configurar la ruta por defecto
$router->setDefaultRoute('ErrorController', 'notFound');

// Extraer el recurso y el verbo
$url = $_GET['resource'] ?? '';
$verb = $_SERVER['REQUEST_METHOD'];

// Enrutar la solicitud
$router->route($url, $verb);
?>
