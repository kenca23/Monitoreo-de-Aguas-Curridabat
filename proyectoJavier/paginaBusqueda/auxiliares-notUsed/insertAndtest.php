<?php 

// Current time

// wait for 2 seconds
//usleep(10000000);

// back!
	$string = file_get_contents("../data/locationsJson.json");
	echo $string;
?>


db.getCollection('Puntos').insertMany([
{"latitud":9.834993,"longitud":-84.208317,"nivel":1},
{"latitud":10.290806,"longitud":-83.616051,"nivel":2},
{"latitud":9.009689,"longitud":-83.285920,"nivel":3},
{"latitud":10.312424,"longitud":-84.647132,"nivel":4},
{"latitud":10.559561,"longitud":-83.661587,"nivel":5}
]);