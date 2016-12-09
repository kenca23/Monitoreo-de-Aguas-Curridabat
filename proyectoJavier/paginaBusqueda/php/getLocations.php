<?php
	require '../vendor/autoload.php'; // include Composer goodies

	$client = new MongoDB\Client("mongodb://localhost:27017");
	$coleccion = $client->PuntosMuestreo->DatosCurri; //ingresar a la base de datos Peliculas
	$query=array();
	$projection["projection"] = ['location' => true,'color'=>true];
	$datos = $coleccion->find($query,$projection);
	//var_dump($datos);
	//echo $datos;
	echo json_encode(iterator_to_array($datos));
?>