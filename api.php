<?php
header("Content-Type: application/json");
// Incluye las clases
include 'config/Database.php';
include 'models/ProductoModel.php';
include 'models/TalleresModel.php';
include 'controllers/ProductoController.php';
include 'controllers/TalleresController.php'; // Asegúrate de que no haya errores de tipeo en el nombre del archivo

// Lee el URI y el método HTTP
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];

// Elimina el prefijo 'restapi/' del URI si existe
$uri = str_replace('/restapi', '', parse_url($request_uri, PHP_URL_PATH));
$uri_segments = explode('/', trim($uri, '/'));

$resource = isset($uri_segments[0]) ? strtolower($uri_segments[0]) : '';
$id = isset($uri_segments[1]) ? (int)$uri_segments[1] : null;

// Instancia los controladores
$productoController = new ProductoController();
$tallerController = new TalleresController();

switch ($resource) {
    case 'productos':
        // Lógica para productos
        switch ($method) {
            case 'GET':
                if ($id) {
                    $productoController->verProducto($id);
                } else {
                    $productoController->obtenerProductos();
                }
                break;
            case 'POST':
                $input = json_decode(file_get_contents('php://input'), true);
                $productoController->crearProducto($input);
                break;
            case 'PUT':
                $input = json_decode(file_get_contents('php://input'), true);
                if ($id) {
                    $productoController->actualizarProducto($id, $input);
                } else {
                    jsonResponse(['error' => 'ID requerido para actualizar'], 400);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $productoController->eliminarProducto($id);
                } else {
                    jsonResponse(['error' => 'ID requerido para eliminar'], 400);
                }
                break;
        }
        break;

    case 'talleres':
        // Lógica para talleres
        switch ($method) {
            case 'GET':
                if ($id) {
                    $tallerController->verTaller($id);
                } else {
                    $tallerController->obtenerTalleres();
                }
                break;
            case 'POST':
                $input = json_decode(file_get_contents('php://input'), true);
                $tallerController->crearTaller($input);
                break;
            case 'PUT':
                $input = json_decode(file_get_contents('php://input'), true);
                if ($id) {
                    $tallerController->actualizarTaller($id, $input);
                } else {
                    jsonResponse(['error' => 'ID requerido para actualizar'], 400);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $tallerController->eliminarTaller($id);
                } else {
                    jsonResponse(['error' => 'ID requerido para eliminar'], 400);
                }
                break;
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada']);
        break;
}
?>