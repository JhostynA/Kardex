<?php

require_once 'Conexion.php';

class ListarU {

    private $pdo;

    public function __construct() {
        $this->pdo = (new Conexion())->getConexion();
    }

    public function listarUsuarios() {
        try {
            $sql = "CALL spu_listar_usuario()";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $usuarios;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
