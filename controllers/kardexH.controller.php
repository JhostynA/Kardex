<?php
require_once '../models/Kardex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idProducto'])) {
    $idProducto = $_POST['idProducto'];
    $kardexModel = new Kardex(); 
    $historial = $kardexModel->listarHistorialProducto($idProducto);
    echo json_encode($historial);
} else {
    http_response_code(400); 
    echo json_encode(['error' => 'Solicitud incorrecta']);
}

?>