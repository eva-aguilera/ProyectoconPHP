<?php
header("Content-Type: application/json");
// Incluye el archivo que contiene la clase Database
include 'config/Database.php';
// Incluye tu modelo de oferta 
include 'models/OfertaModel.php';
// Incluye el controlador
include 'controllers/OfertaController.php';

// Obtener método y URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));


// 4. AHORA, instancia el controlador y PÁSALE el objeto de conexión
$ofertaController = new OfertaController();

if (count($uri) === 0 || $uri[0] === 'restapi' ) {
    $id = isset($uri[1]) ? (int)$uri[1] : null;




switch ($method) {
    case 'GET':
        if ($id) {
            $ofertaController->show($id);
        }else {
             // Llama al método del controlador
        $ofertaController->obtenerOfertas();
        }
       
        break;
    case 'POST':
        // Solo necesitas pasar los datos de entrada
        $ofertaController->crearOferta($input);
        break;
    case 'PUT':
        $ofertaController->actualizarOferta($id);
        break;
    case 'DELETE':
        if ($id) {
        $ofertaController->eliminarOferta($id);
        break;
        }else {
            jsonResponse(['error' => 'ID requerido para eliminar'], 400);
        }
        
        break;
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['message' => 'Método de solicitud no válido']);
        break;

    }
    
}else {
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
    exit;
}

?> 