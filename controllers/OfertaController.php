<?php

// Nota: La ruta de include aquí depende de la ubicación de tus archivos.
// Si el controlador está en 'controllers', esta ruta es la correcta para llegar a 'models'
include_once 'models/OfertaModel.php';
include_once 'views/jsonResponse.php';

class OfertaController {
    private $model;

    // EL CONSTRUCTOR CORREGIDO: ahora recibe la conexión a la base de datos
    public function __construct() {
        $this->model = new OfertaModel();
    }
    public function obtenerOfertas() {
        $data = $this->model->getAll();
        jsonResponse($data);
    }
    // // Método para manejar la creación de una oferta (POST)
    public function crearOferta() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['cargo']) || !isset($input['descripcion']) || !isset($input['empresa']) || !isset($input['requisitos']) || !isset($input['ubicacion']) || !isset($input['salario'])) {
           // Llama a la función auxiliar que creamos en el archivo principal
            jsonResponse(['error' => 'Faltan campos requeridos para la oferta laboral.'], 400); 
            return;
        }

        //Llama al método del modelo. Asegúrate de que los nombres de los parámetros coincidan.
        $nuevaOferta = $this->model->crearOferta(
            $input['cargo'], 
            $input['descripcion'],
            $input['empresa'],
            $input['requisitos'], 
            $input['ubicacion'], 
            $input['salario']
        );

        if ($nuevaOferta) {
            jsonResponse(['mensaje' => 'Oferta creada exitosamente', 'oferta' => $nuevaOferta], 201);
        } else {
            jsonResponse(['error' => 'Error al crear la oferta laboral'], 500);
        }
    }
     public function show($id) {
        $oferta = $this->model->findById($id);
        if ($oferta) {
            jsonResponse($oferta);
        } else {
            jsonResponse(['error' => 'oferta no encontrada'], 404);
        }
    }

    public function actualizarOferta($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['cargo']) || !isset($input['descripcion']) || !isset($input['empresa']) || !isset($input['requisitos']) || !isset($input['ubicacion']) || !isset($input['salario'])) {
            jsonResponse(['error' => 'Faltan campos requeridos: nombre y descripcion'], 400);
            return;
        }

        $actualizado = $this->model->actualizarOferta($id, $input['cargo'], 
            $input['descripcion'],
            $input['empresa'],
            $input['requisitos'], 
            $input['ubicacion'], 
            $input['salario']);

        if ($actualizado) {
            jsonResponse(['mensaje' => 'Servicio actualizado', 'servicio' => $actualizado]);
        } else {
            jsonResponse(['error' => 'Servicio no encontrado o sin cambios'], 404);
        }
    }
    
    public function eliminarOferta($id) {
        $eliminado = $this->model->delete($id);
        if ($eliminado) {
            jsonResponse(['mensaje' => 'Servicio eliminado']);
        } else {
            jsonResponse(['error' => 'Servicio no encontrado'], 404);
        }
    }



    //Los otros métodos del controlador (obtener, actualizar, eliminar, etc.)
}