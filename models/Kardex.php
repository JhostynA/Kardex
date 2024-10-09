<?php

require_once 'Conexion.php';

class Kardex {

    private $pdo;

    public function __construct() {
        $this->pdo = (new Conexion())->getConexion();
    }

    public function manejarKardex($idproducto, $tipomovimiento, $cantidad, $fecharegistro) {
        try {
            $sql = "CALL spu_manejar_kardex(:idproducto, :tipomovimiento, :cantidad, :fecharegistro)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idproducto', $idproducto, PDO::PARAM_INT);
            $stmt->bindParam(':tipomovimiento', $tipomovimiento, PDO::PARAM_STR);
            $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
            $stmt->bindParam(':fecharegistro', $fecharegistro, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function listarHistorialProducto($idProducto)
    {
        $query = 'CALL spu_listar_movimientos_producto(:idproducto)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':idproducto', $idProducto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
