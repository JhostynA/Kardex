<?php

require_once '../models/Producto.php';

$producto = new Producto();

if (isset($_POST['operation'])) {
    switch ($_POST['operation']) {
        case 'add':
            $datos = [
                "idtipoproducto"        => $_POST['idtipoproducto'],
                "idmarca"               => $_POST['idmarca'],
                "idtipopresentacion"    => $_POST['idtipopresentacion'],
                "descripcion"           => $_POST['descripcion']
            ];
            $idobtenido = $producto->add($datos);
            echo json_encode(["idproducto" => $idobtenido]);
            break;
    }
} elseif (isset($_GET['operation']) && $_GET['operation'] === 'verify') {
    $datos = [
        "idtipoproducto"        => $_GET['idtipoproducto'],
        "idmarca"               => $_GET['idmarca'],
        "idtipopresentacion"    => $_GET['idtipopresentacion'],
        "descripcion"           => $_GET['descripcion']
    ];
    $existe = $producto->exists($datos);
    echo json_encode(["exists" => $existe]);
}

?>
