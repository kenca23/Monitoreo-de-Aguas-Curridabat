<?php
require 'vendor/autoload.php';
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
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script src="js/validacion.js"></script>
    <title>Proyecto Monitoreo de aguas</title>
  </head>
  <body>
		<nav class="navbar navbar-light bg-faded margenNav">
			<a class="navbar-brand" href="index.html"><h3>Inserción de datos</h3></a>
		</nav>
		
		<?php if ($action === 'ensennarForm'): ?>
      <?php if($mensaje === 'exitosa'):?>
        <div class="alert alert-success" role="alert">
          <strong>Archivo agregado con exito!</strong>
        </div>
      <?php elseif($mensaje === 'noExitosa'): ?>
        <div class="alert alert-danger" role="alert">
          <strong>No se logro insertar!</strong> revise los datos enviados.
        </div>


      <?php endif; ?>



		<div class = "container">
      <div class="infoB">
        <h5 class="titulo">Información básica</h5>
      </div>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validateForm()">
				<div class="form-group row">
  					<label for="institucion" class="col-xs-2 col-form-label">Institución:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="institucion" name="institucion" required>
  					</div>
				</div>
				<div class="form-group row">
  					<label for="email" class="col-xs-2 col-form-label">Email:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="email" placeholder="example@example.com" id="email" name="email" required="">
  					</div>
				</div>
				<div class="form-group row">
    				<label for="kit" class="col-xs-2 col-form-label">Kit:</label>
    				<div class="col-xs-10">
    					<select class="form-control" id="kit" name="kit" required>
                  <option selected disabled hidden value = "nada">Escoge un kit</option>
      						<option>LaMotte</option>
      						<option>Pasco</option>
                  <option>Prof</option>
    					</select>
    				</div>
  				</div>
  				<div class="form-group row">
    				<label for="estacion" class="col-xs-2 col-form-label">Estación:</label>
    				<div class="col-xs-10">
    					<select class="form-control" id="estacion" name="estacion">
      						<option>Estación 1</option>
      						<option>Estación 2</option>
      						<option>Estación 3</option>
      						<option>Estación 4</option>
      						<option>Estación 5</option>
      						<option>Estación 6</option>
      						<option>Estación 7</option>
      						<option>Estación 8</option>
      						<option>Estación 9</option>
      						<option>Estación 10</option>
    					</select>
    				</div>
  				</div>
  				<div class="form-group row">
  					<label for="nombre" class="col-xs-2 col-form-label">Nombre:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="nombre" name="nombre">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="fecha" class="col-xs-2 col-form-label">Fecha:</label>
					<div class="col-xs-10">
						<input class="form-control" type="date" placeholder="ej: 24/11/2016" id="fecha" name="fecha">
					</div>
				</div>
        <div class="form-group row">
            <label for="hora" class="col-xs-2 col-form-label">Hora:</label>
          <div class="col-xs-10">
            <input class="form-control" type="date" placeholder="ej: 24/11/2016" id="hora" name="hora">
          </div>
        </div>
        <div class="form-group row">
            <label for="latitud" class="col-xs-2 col-form-label">Latitud:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="latitud" name="latitud">
            </div>
        </div>
        <div class="form-group row">
            <label for="longitud" class="col-xs-2 col-form-label">Longitud:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="longitud" name="longitud">
            </div>
        </div>
        <div class="form-group row">
            <label for="indice" class="col-xs-2 col-form-label">Índice:</label>
            <div class="col-xs-10">
              <select class="form-control" id="indice" name="indice" onchange="cambio()" required>
                  <option selected disabled hidden value = "nada">Escoge un índice</option>
                  <option value = "holandes">Holandés</option>
                  <option value = "WQIB">WQIB</option>
                  <option value = "NSF">NSF</option>
              </select>
            </div>
          </div>
			
        <div class="verticalLine">
           <h5 class="titulo">Información Obligatoria</h5>
        </div>
        <div class="form-group row" id="divNada">
              <small id="nada" class="form-text text-muted">
                No has escogido un indice.
              </small>
        </div>
        
        <div style="display:none;" id="divPO2" class="form-group row">
            <label for="pO2" class="col-xs-2 col-form-label">% O2:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="pO2" name="pO2">
            </div>
        </div>
        <div style="display:none;" id="divCF" class="form-group row">
            <label for="cf" class="col-xs-2 col-form-label">CF:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="cf" name="cf">
            </div>
        </div>
        <div style="display:none;" id="divDBO" class="form-group row">
            <label for="dbo" class="col-xs-2 col-form-label">DBO:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="dbo" name="dbo">
            </div>
        </div>
        <div  style="display:none;" id="divNH4" class="form-group row">
            <label for="nh4" class="col-xs-2 col-form-label">NH4:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="nh4" name="nh4">
            </div>
        </div>
        <div style="display:none;" id="divPH" class="form-group row">
            <label for="ph" class="col-xs-2 col-form-label">pH:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="ph" name="ph">
            </div>
        </div>
        <div style="display: none" id="divFosfato" class="form-group row">
            <label for="fosfato" class="col-xs-2 col-form-label">Fosfato:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="fosfato" name="fosfato">
            </div>
        </div>
        <div style="display: none" id="divNitrato" class="form-group row">
            <label for="nitrato" class="col-xs-2 col-form-label">Nitrato:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="nitrato" name="nitrato">
            </div>
        </div>
        <div style="display:none;" id="divT" class="form-group row">
            <label for="t" class="col-xs-2 col-form-label">T:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="t" name="t">
            </div>
        </div>
        <div style="display:none;" id="divTurbidez" class="form-group row">
            <label for="turbidez" class="col-xs-2 col-form-label">Turbidez:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="turbidez" name="turbidez">
            </div>
        </div>
        <div style="display:none;" id="divSolTot" class="form-group row">
            <label for="solTot" class="col-xs-2 col-form-label">Sólidos totales:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="solTot" name="solTot">
            </div>
        </div>




		    <div class="verticalLine">
			     <h5 class="titulo">Información del kit</h5>
		    </div>

		    <div style="display:none;" class="form-group row">
            <label for="color" class="col-xs-2 col-form-label">Color:</label>
            <div class="col-xs-10">
              <select class="form-control" id="color" name="color">
                  <option>Azul</option>
                  <option>Verde</option>
                  <option>Amarillo</option>
                  <option>Anaranjado</option>
                  <option>Rojo</option>
              </select>
          </div>
        </div>
				<div style="display:none;" class="form-group row">
  					<label for="indHol" class="col-xs-2 col-form-label">I-Hol:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="indHol" name="indHol" aria-describedby="ayudaIndHol">
              <small id="ayudaIndHol" class="form-text text-muted">
                Debe ser un dato entero entre 1 y 15.
              </small>
  					</div>
				</div>
        <div  style="display:none;" id="divNH4_O" class="form-group row">
            <label for="nh4O" class="col-xs-2 col-form-label">NH4:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="nh4O" name="nh4O">
            </div>
        </div>
				<div style="display:none;" id="divCF_O" class="form-group row">
  					<label for="cfO" class="col-xs-2 col-form-label">CF:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="cfO" name="cfO">
  					</div>
				</div>
				<div  class="form-group row">
  					<label for="dqo" class="col-xs-2 col-form-label">DQO:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="dqo" name="dqo">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="ec" class="col-xs-2 col-form-label">EC:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="ec" name="ec">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="po4" class="col-xs-2 col-form-label">PO4:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="po4" name="po4">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="gya" class="col-xs-2 col-form-label">GYA:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="gya" name="gya">
  					</div>
				</div>
				<div style="display:none;" id="divPH_O"  class="form-group row">
  					<label for="phO" class="col-xs-2 col-form-label">pH:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="phO" name="phO">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="sd" class="col-xs-2 col-form-label">SD:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="sd" name="sd">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="ssed" class="col-xs-2 col-form-label">Ssed:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="ssed" name="ssed">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="sst" class="col-xs-2 col-form-label">SST:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="sst" name="sst">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="st" class="col-xs-2 col-form-label">ST:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="st" name="st">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="saam" class="col-xs-2 col-form-label">SAAM:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="saam" name="saam">
  					</div>
				</div>
				<div style="display:none;" id="divT_O"  class="form-group row">
  					<label for="tO" class="col-xs-2 col-form-label">T:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="tO" name="tO">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="aforo" class="col-xs-2 col-form-label">Aforo:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="aforo" name="aforo">
  					</div>
				</div>
        <div style="display: none" id="divFosfato_O" class="form-group row">
            <label for="fosfatoO" class="col-xs-2 col-form-label">Fosfato:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="fosfatoO" name="fosfatoO">
            </div>
        </div>
        <div style="display: none" id="divNitrato_O" class="form-group row">
            <label for="nitratoO" class="col-xs-2 col-form-label">Nitrato:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="nitratoO" name="nitratoO">
            </div>
        </div>
        <div style="display:none;" id="divTurbidez_O" class="form-group row">
            <label for="turbidezO" class="col-xs-2 col-form-label">Turbidez:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="turbidezO" name="turbidezO">
            </div>
        </div>
        <div style="display:none;" id="divSolTot_O" class="form-group row">
            <label for="solTotO" class="col-xs-2 col-form-label">Sólidos totales:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="solTotO" name="solTotO">
            </div>
        </div>
				
				<input type="submit" class="btn btn-primary" name="btn_agregar" id="btn_agregar" value="Agregar"/>

			</form>
		</div>
	<?php endif; ?>

		<div class="espacio"></div>

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" integrity="sha384-3ceskX3iaEnIogmQchP8opvBy3Mi7Ce34nWjpBIwVTHfGYWQS9jwHDVRnpKKHJg7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0tGmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
  </body>
</html>