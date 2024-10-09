<?php

require_once '../models/Persona.php';
$persona = new Persona();


if (isset($_POST['operation'])){

  switch ($_POST['operation']){
    case 'add':
      $datos = [
        "apepaterno"    => $_POST['apepaterno'],
        "apematerno"    => $_POST['apematerno'],
        "nombres"       => $_POST['nombres'],
        "nrodocumento"  => $_POST['nrodocumento'],
        "telprincipal"  => $_POST['telprincipal'],
        "telsecundario" => $_POST['telsecundario']
      ];
      $idobtenido = $persona->add($datos); 
      echo json_encode(["idpersona" => $idobtenido]); 
      break;
  }
}

//SELECT
if (isset($_GET['operation'])){

  switch($_GET['operation']){
    case 'searchByDoc':
      echo json_encode($persona->searchByDoc(['nrodocumento' => $_GET['nrodocumento']]));
      break;
  }

}