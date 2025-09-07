<?php
include_once 'models/TalleresModel.php';
include_once 'views/jsonResponse.php';

class TalleresController {
    private $model;

    // EL CONSTRUCTOR CORREGIDO: ahora recibe la conexión a la base de datos
    public function __construct() {
        $this->model = new TalleresModel();
    }
    public function obtenerTalleres() {
        $data = $this->model->getAll();
        jsonResponse($data);
        }
    
    public function verTaller($id) {
        $taller = $this->model->findById($id);
        if ($taller) {
            jsonResponse($taller);
        } else {
            jsonResponse(['error' => 'Producto no encontrado'], 404);
        }
    }
    
    public function crearTaller() {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['nombre']) || !isset($input['descripcion']) || !isset($input['fecha']) || !isset($input['ubicacion']) || !isset($input['precio']) || !isset($input['cupo_maximo']) || !isset($input['imagen_url'])) {
        jsonResponse(['error' => 'Faltan campos requeridos para crear un nuevo taller.'], 400); 
        return;
    }

    $nuevoTaller = $this->model->create(
        $input['nombre'], 
        $input['descripcion'],
        $input['fecha'],
        $input['ubicacion'],
        $input['precio'], 
        $input['cupo_maximo'],
        $input['imagen_url']
    );

    if ($nuevoTaller) {
        jsonResponse(['mensaje' => 'Taller creado exitosamente', 'taller' => $nuevoTaller], 201);
    } else {
        jsonResponse(['error' => 'Error al crear el Taller'], 500);
    }
}

public function actualizarTaller($id) {
    // 1. Lee los datos JSON de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    // 2. Valida que los campos requeridos estén presentes en el JSON
    if (!isset($input['nombre']) || !isset($input['descripcion']) || !isset($input['fecha']) || !isset($input['ubicacion']) || !isset($input['precio']) || !isset($input['cupo_maximo']) || !isset($input['imagen_url'])) {
        jsonResponse(['error' => 'Faltan campos requeridos para actualizar el taller.'], 400); 
        return;
    }

    // 3. Llama al método 'update' del modelo con el ID y los datos de entrada
    $talleractualizado = $this->model->update( $id, 
        $input['nombre'], 
        $input['descripcion'],
        $input['fecha'],
        $input['ubicacion'],
        $input['precio'], 
        $input['cupo_maximo'],
        $input['imagen_url']
    );

    // 4. Responde al cliente según el resultado de la actualización
    if ($talleractualizado) {
        jsonResponse(['mensaje' => 'Taller actualizado', 'taller' => $talleractualizado]);
    } else {
        jsonResponse(['error' => 'Taller no encontrado o sin cambios'], 404);
    }
}

  public function eliminarTaller($id) {
        $eliminado = $this->model->delete($id);
        if ($eliminado) {
            jsonResponse(['mensaje' => 'Taller eliminado']);
        } else {
            jsonResponse(['error' => 'Taller no encontrado'], 404);
        }
    }
    
}