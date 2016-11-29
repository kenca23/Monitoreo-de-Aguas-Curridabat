<?php
require 'vendor/autoload.php';
  $error = "";
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
//altitud no
//Indice Holandes y WQIB
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
      $documento['Latitud'] = validarDatoNumericoObli($_POST['latitud']);
      $documento['Longitud'] = validarDatoNumericoObli($_POST['longitud']);
      $documento['Altitud'] = validarDatoNumericoObli($_POST['altitud']);
			$documento['Color'] = $_POST['color'];
			$documento['indHol'] = validarEntero($_POST['indHol']);
			$documento['Porcentaje O2'] = validarDatoNumericoObli($_POST['pO2']); 
			$documento['DBO'] = validarDatoNumericoObli($_POST['dbo']);
			$documento['NH4'] = validarDatoNumericoObli($_POST['nh4']);
			$documento['CF'] = validarDato($_POST['cf']);
			$documento['DQO'] = validarDato($_POST['dqo']);
			$documento['EC'] = validarDato($_POST['ec']);
			$documento['PO4'] = validarDato($_POST['po4']);
			$documento['GYA'] = validarDato($_POST['gya']);
			$documento['pH'] = validarDato($_POST['ph']);
			$documento['SD'] = validarDato($_POST['sd']);
			$documento['Ssed'] = validarDato($_POST['ssed']);
			$documento['SST'] = validarDato($_POST['sst']);
			$documento['ST'] = validarDato($_POST['st']);
			$documento['SAAM'] = validarDato($_POST['saam']);
			$documento['T'] = validarDato($_POST['t']);
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
    <title>Proyecto Monitoreo de aguas</title>
  </head>
  <body>
		<nav class="navbar navbar-light bg-faded margenNav">
			<a class="navbar-brand" href="index.html"><h3>Índice Holandés</h3></a>
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
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<div class="form-group row">
  					<label for="institucion" class="col-xs-2 col-form-label">Institución:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="institucion" name="institucion">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="email" class="col-xs-2 col-form-label">Email:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="email" placeholder="example@example.com" id="email" name="email">
  					</div>
				</div>
				<div class="form-group row">
    				<label for="kit" class="col-xs-2 col-form-label">Kit:</label>
    				<div class="col-xs-10">
    					<select class="form-control" id="kit" name="kit">
      						<!--<option>Holandés</option>-->
      						<option>Otro</option>
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
						<input class="form-control" type="date" placeholder="24/11/2016" id="fecha" name="fecha">
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
            <label for="altitud" class="col-xs-2 col-form-label">Altitud:</label>
            <div class="col-xs-10">
              <input class="form-control" type="text" id="altitud" name="altitud">
            </div>
        </div>
		
			

		    <div class="verticalLine">
			     <h5 class="titulo">Información del kit</h5>
		    </div>

		    <div class="form-group row">
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
				<div class="form-group row">
  					<label for="indHol" class="col-xs-2 col-form-label">I-Hol:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="indHol" name="indHol" aria-describedby="ayudaIndHol">
              <small id="ayudaIndHol" class="form-text text-muted">
                Debe ser un dato entero entre 1 y 15.
              </small>
  					</div>
				</div>
				<div class="form-group row">
  					<label for="pO2" class="col-xs-2 col-form-label">% O2:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="pO2" name="pO2" aria-describedby="ayudaPO2">
              <small id="ayudaPO2" class="form-text text-muted">
                Debe ser un dato entero o con decimales.
              </small>
  					</div>
				</div>
				<div class="form-group row">
  					<label for="dbo" class="col-xs-2 col-form-label">DBO:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="dbo" name="dbo" aria-describedby="ayudaDBO">
              <small id="ayudaDBO" class="form-text text-muted">
                Debe ser un dato entero o con decimales.
              </small>
  					</div>
				</div>
				<div class="form-group row">
  					<label for="nh4" class="col-xs-2 col-form-label">NH4:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="nh4" name="nh4" aria-describedby="ayudaNH4">
              <small id="ayudaNH4" class="form-text text-muted">
                Debe ser un dato entero o con decimales.
              </small>
  					</div>
				</div>
				<div class="form-group row">
  					<label for="cf" class="col-xs-2 col-form-label">CF:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="cf" name="cf">
  					</div>
				</div>
				<div class="form-group row">
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
				<div class="form-group row">
  					<label for="ph" class="col-xs-2 col-form-label">pH:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="ph" name="ph">
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
				<div class="form-group row">
  					<label for="t" class="col-xs-2 col-form-label">T:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="t" name="t">
  					</div>
				</div>
				<div class="form-group row">
  					<label for="aforo" class="col-xs-2 col-form-label">Aforo:</label>
  					<div class="col-xs-10">
    					<input class="form-control" type="text" id="aforo" name="aforo">
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