<?php

require_once 'Conexion.php';

class Persona extends Conexion{

  private $pdo;

  public function __CONSTRUCT(){
    $this->pdo = parent::getConexion();
  }

  public function add($params = []):int{
    $idgenerado = null;
    try{
      $query = $this->pdo->prepare("call spu_personas_registrar(?,?,?,?,?,?)");
      $query->execute(
        array(
          $params['apepaterno'],
          $params['apematerno'],
          $params['nombres'],
          $params['nrodocumento'],
          $params['telprincipal'],
          $params['telsecundario']
        )
      );
      $row = $query->fetch(PDO::FETCH_ASSOC);
      $idgenerado = $row['idpersona'];
    }
    catch(Exception $e){
      $idgenerado = -1;
    }
    
    return $idgenerado;
  }



  public function searchByDoc($params = []):array{
    try{
      $query = $this->pdo->prepare("call spu_personas_buscar_dni(?)");
      $query->execute(
        array($params['nrodocumento'])
      );

      return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(Exception $e){
      die($e->getMessage());
    }
  }

}

