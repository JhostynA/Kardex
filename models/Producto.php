<?php

require_once 'Conexion.php';

class Producto extends Conexion{

    private $pdo;

    public function __CONSTRUCT(){
        $this->pdo = parent::getConexion();
    }

    public function add($datos) {
      try {
          $sql = "CALL spu_registrar_producto(?, ?, ?, ?)";
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute([
              $datos['idtipoproducto'],
              $datos['idmarca'],
              $datos['idtipopresentacion'],
              $datos['descripcion']
          ]);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          return $result['idproducto'];
      } catch (PDOException $e) {
          return -1;
      }
  }

  public function exists($datos) {
    try {
        $sql = "SELECT COUNT(*) AS count FROM productos 
                WHERE idtipoproducto = ? AND idmarca = ? AND idtipopresentacion = ? AND descripcion = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $datos['idtipoproducto'],
            $datos['idmarca'],
            $datos['idtipopresentacion'],
            $datos['descripcion']
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    } catch (PDOException $e) {
        return false;
    }
}


}



/*  $producto = new Producto();
$datos = [   
  "idtipoproducto" => 3,
  "idmarca"   => 3,
  "idtipopresentacion"   => 2 ,
  "descripcion" => "Multi bioticos 500gm"
];
$resultado = $producto->add($datos);
var_dump($resultado); */