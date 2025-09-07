<?php

// Nota: La ruta de include aquí depende de la ubicación de tus archivos.
// Si el controlador está en 'controllers', esta ruta es la correcta para llegar a 'models'
include_once 'models/ProductoModel.php';
include_once 'views/jsonResponse.php';

class ProductoController {
    private $model;

    // EL CONSTRUCTOR CORREGIDO: ahora recibe la conexión a la base de datos
    public function __construct() {
        $this->model = new ProductoModel();
    }
    public function obtenerProductos() {
        $data = $this->model->getAll();
        jsonResponse($data);
    }
    // // Método para manejar la creación de una oferta (POST)
  public function crearProducto() {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['nombre']) || !isset($input['descripcion']) || !isset($input['precio']) || !isset($input['stock']) || !isset($input['categoria'])) {
        jsonResponse(['error' => 'Faltan campos requeridos para ingresar un nuevo producto.'], 400); 
        return;
    }

    $nuevoProducto = $this->model->create(
        $input['nombre'], 
        $input['descripcion'],
        $input['precio'],
        $input['stock'],
        $input['categoria'] // Pasa el nuevo valor
    );

    if ($nuevoProducto) {
        jsonResponse(['mensaje' => 'Producto creado exitosamente', 'Producto' => $nuevoProducto], 201);
    } else {
        jsonResponse(['error' => 'Error al ingresar el producto'], 500);
    }
}
     
    
    public function verProducto($id) {
        $producto = $this->model->findById($id);
        if ($producto) {
            jsonResponse($producto);
        } else {
            jsonResponse(['error' => 'Producto no encontrado'], 404);
        }
    }

   public function actualizarProducto($id) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['nombre']) || !isset($input['descripcion']) || !isset($input['precio']) || !isset($input['stock']) || !isset($input['categoria'])) {
        jsonResponse(['error' => 'Faltan campos requeridos para actualizar el producto.'], 400); 
        return;
    }

    $productoActualizado = $this->model->update(
        $id,
        $input['nombre'],
        $input['descripcion'],
        $input['precio'],
        $input['stock'],
        $input['categoria'] // Pasa el nuevo valor
    );

    if ($productoActualizado) {
        jsonResponse(['mensaje' => 'Producto actualizado', 'Producto' => $productoActualizado], 200);
    } else {
        jsonResponse(['error' => 'Producto no encontrado o sin cambios'], 404);
    }
}

    public function eliminarProducto($id) {
        $eliminado = $this->model->delete($id);
        if ($eliminado) {
            jsonResponse(['mensaje' => 'Producto eliminado']);
        } else {
            jsonResponse(['error' => 'Producto no encontrado'], 404);
        }
    }

}