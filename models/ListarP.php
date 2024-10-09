<?php

require_once 'Conexion.php';

class ListarP {

    private $pdo;

    public function __construct() {
        $this->pdo = (new Conexion())->getConexion();
    }

    public function listarProductos() {
        try {
            $sql = "CALL spu_listar_productos()";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $producto = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $producto;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
