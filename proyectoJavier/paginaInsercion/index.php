<?php
require '../vendor/autoload.php';
  $error = "";
  $color = "";
function validarDato($dato){
  if(is_numeric($dato)){
    if(ctype_digit($dato)){
      return (int) $dato;
    }else{
      return (float) $dato;
    }
  }else{
    $temp = 'ND';
    return $temp;
  }    
}
function validarDatoNumericoObli($dato){
  if(is_numeric($dato)){
    if(ctype_digit($dato)){
      return (int) $dato;
    }else {
      return (float) $dato;
    }
  }else{
    $GLOBALS['error'] = "error";
    return $dato;
  }
}
//agregar horas, y estacion 1-1 con nombre y lon-lat.
//altitud no.
//Indice Holandes y WQIB y limitar insercion de datos. 
//Hacer tabla para insercion de mas datos, 2 columnas nombre, valor.
//atributo indice que limite los campos obligatorios.
//Hacer un loggin con autentificacion.
//Hacer el menú CRUD.


//Cambiar Kit por categoria sensables.

function validarEntero($dato){
  if(is_numeric($dato)){
    if(ctype_digit($dato)){
      return (int) $dato;
    }else{
      $GLOBALS['error'] = "error";
      return $dato;
    }
  }else{
    $GLOBALS['error'] = "error";
    return $dato;
  }
}

function indiceHol($PO2, $DBO, $NH4){
  //Variables
  $puntos = 0;
  //validacion PO2
  if($PO2 >= 91 && $PO2 <= 100){
    $puntos += 1;
  }elseif(($PO2 >= 71 && $PO2 <= 90)||($PO2 >= 111 && $PO2 <= 120)){
    $puntos += 2;
  }elseif(($PO2 >= 51 && $PO2 <= 70)||($PO2 >= 121 && $PO2 <= 130)){
    $puntos += 3;
  }elseif($PO2 >= 31 && $PO2 <= 50){
    $puntos += 4;
  }else{
    $puntos += 5;
  }
  //validacion DBO
  if($DBO <= 3.0){
    $puntos += 1;
  }elseif($DBO >= 3.1 && $DBO <= 6.0){
    $puntos += 2;
  }elseif($DBO >= 6.1 && $DBO <= 9.0){
    $puntos += 3;
  }elseif($DBO >= 9.1 && $DBO <= 15.0){
    $puntos += 4;
  }else{
    $puntos += 5;
  }
  //validacion NH4
  if($NH4 < 0.50){
    $puntos += 1;
  }elseif($NH4 >= 0.50 && $NH4 <= 1.0){
    $puntos += 2;
  }elseif($NH4 >= 1.1 && $NH4 <= 2.0){
    $puntos += 3;
  }elseif($NH4 >= 2.1 && $NH4 <= 5.0){
    $puntos += 4;
  }else{
    $puntos += 5;
  }
  //validacion puntos
  if($puntos == 3 ){
    $GLOBALS['color'] = "Azul";
  }elseif($puntos >= 4 && $puntos <= 6){
    $GLOBALS['color'] = "Verde";
  }elseif($puntos >= 7 && $puntos <= 9){
    $GLOBALS['color'] = "Amarillo";
  }elseif($puntos >= 10 && $puntos <= 12){
    $GLOBALS['color'] = "Anaranjado";
  }else{
    $GLOBALS['color'] = "Rojo";
  }
  return $puntos;
}
function indiceNSF($PO2, $CF, $DBO, $pH, $Fosfato, $Nitrato, $T, $turbidez, $solTot){
  //Multiplicacion por pesos.
  $PO2 = $PO2 * 0.17;
  $CF = $CF * 0.16;
  $DBO = $DBO * 0.11;
  $pH = $pH * 0.11;
  $Fosfato = $Fosfato * 0.10;
  $Nitrato = $Nitrato * 0.10;
  $T = $T * 0.10;
  $turbidez = $turbidez * 0.8;
  $solTot = $solTot * 0.7;
  //Variable puntos
  $puntos = $PO2 + $CF + $DBO + $pH + $Fosfato + $Nitrato + $T + $turbidez + $solTot;
  //validacion puntos
  if($puntos >= 91 && $puntos <= 100){
    $GLOBALS['color'] = "Azul";
  }elseif($puntos >= 71 && $puntos <= 90){
    $GLOBALS['color'] = "Verde";
  }elseif($puntos >= 51 && $puntos <= 70){
    $GLOBALS['color'] = "Amarillo";
  }elseif($puntos >= 26 && $puntos <= 50){
    $GLOBALS['color'] = "Anaranjado";
  }else{
    $GLOBALS['color'] = "Rojo";
  }
  return $puntos;

}
function indiceWQIB(){}


$mensaje = 'noEnsennar';
$action = (!empty($_POST['btn_agregar']) && ($_POST['btn_agregar'] === 'Agregar')) ? 'guardarDoc' : 'ensennarForm';
switch ($action) {
	case 'guardarDoc':
		try {
			$connection = new MongoDB\Client;
			$database = $connection->proyectoJavier;
			$collection = $database->documentos;
			$documento = array();


			$documento['Institucion'] = $_POST['institucion'];
			$documento['email'] = $_POST['email'];
			$documento['kit'] = $_POST['kit'];
			$documento['Estacion'] = $_POST['estacion'];
			$documento['Nombre'] = $_POST['nombre'];
			$documento['Fecha'] = $_POST['fecha'];
      $documento['Hora'] = $_POST['hora'];
      $docAni1 = array();
      $docAni1['Latitud'] = validarDatoNumericoObli($_POST['latitud']);
      $docAni1['Longitud'] = validarDatoNumericoObli($_POST['longitud']);
      $documento['Location'] = $docAni1;
      $indice = $_POST['indice'];
      if($indice === 'holandes'){
        $documento['Indice'] = 'Holandés';
        $PO2 = validarDatoNumericoObli($_POST['pO2']); 
        $DBO = validarDatoNumericoObli($_POST['dbo']);
        $NH4 = validarDatoNumericoObli($_POST['nh4']);
        $documento['Porcentaje O2'] = $PO2 ;
        $documento['DBO'] = $DBO;
        $documento['NH4'] = $NH4;
        $documento['Índice Holandés'] = indiceHol($PO2, $DBO, $NH4);
        $documento['Color'] = $color;
        //Datos opcionales de otros kits.
        $documento['CF'] = validarDato($_POST['cfO']);
        $documento['pH'] = validarDato($_POST['phO']);
        $documento['Fosfato'] = validarDato($_POST['fosfatoO']);
        $documento['Nitrato'] = validarDato($_POST['nitratoO']);
        $documento['t'] = validarDato($_POST['t']);
        $documento['turbidez'] = validarDato($_POST['turbidezO']);
        $documento['Sólidos totales'] = validarDato($_POST['solTotO']);
      }elseif ($indice === 'NSF') {
        $documento['Indice'] = $indice;
        $PO2 = validarDatoNumericoObli($_POST['pO2']); 
        $CF = validarDatoNumericoObli($_POST['cf']);
        $DBO = validarDatoNumericoObli($_POST['dbo']);
        $pH = validarDatoNumericoObli($_POST['ph']);
        $Fosfato = validarDatoNumericoObli($_POST['fosfato']);     
        $Nitrato = validarDatoNumericoObli($_POST['nitrato']);
        $T = validarDatoNumericoObli($_POST['t']); 
        $turbidez = validarDatoNumericoObli($_POST['turbidez']);
        $solTot = validarDatoNumericoObli($_POST['solTot']);
        $documento['Porcentaje O2'] = $PO2;
        $documento['CF'] = $CF;
        $documento['DBO'] = $DBO;
        $documento['pH'] = $pH;
        $documento['Fosfato'] = $Fosfato;
        $documento['Nitrato'] = $Nitrato;
        $documento['t'] = $T;
        $documento['turbidez'] = $turbidez;
        $documento['Sólidos totales'] = $solTot;
        $documento['Índice NSF'] = indiceNSF($PO2, $CF, $DBO, $pH, $Fosfato, $Nitrato, $T, $turbidez, $solTot);
        $documento['Color'] = $color;
        //Datos opcionales de otros kits.
        $documento['NH4'] = validarDato($_POST['nh4O']);
      }else{
        $documento['Indice'] = $indice;
      }
			$documento['DQO'] = validarDato($_POST['dqo']);
			$documento['EC'] = validarDato($_POST['ec']);
			$documento['PO4'] = validarDato($_POST['po4']);
			$documento['GYA'] = validarDato($_POST['gya']);
			$documento['SD'] = validarDato($_POST['sd']);
			$documento['Ssed'] = validarDato($_POST['ssed']);
			$documento['SST'] = validarDato($_POST['sst']);
			$documento['ST'] = validarDato($_POST['st']);
			$documento['SAAM'] = validarDato($_POST['saam']);
			$documento['Aforo'] = validarDato($_POST['aforo']);

      if($error != "error") {
        $status = $collection->insertOne($documento, array('safe' => true));
        $mensaje = 'exitosa';
        $action = 'ensennarForm';
      } 
      else {
        $mensaje = 'noExitosa';
        $action = 'ensennarForm';
      } 
		}
		catch (MongoConnectionException $e) {
			die("No se ha podido conectar a la base de datos " . $e->getMessage());
		}catch(MongoCursorException $e){
			die("Ha fallado la insercion ". $e->getMessage());
		}catch (MongoException $e){
			die('No se han podido insertar los datos ' . $e->getMessage());
		}
		
		break;
	case 'ensennarForm' :
	default:
}
require 'html/insercion.html';
?>

