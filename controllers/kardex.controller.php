<?php
require_once '../models/Kardex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idproducto = $_POST['idproducto'];
    $tipomovimiento = $_POST['tipomovimiento'];
    $cantidad = $_POST['cantidad'];
    $fecharegistro = $_POST['fecharegistro'];

    try {
        $kardex = new Kardex();
        $kardex->manejarKardex($idproducto, $tipomovimiento, $cantidad, $fecharegistro);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}


?>
