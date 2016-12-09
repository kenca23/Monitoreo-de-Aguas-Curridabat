function (cantidad){
	var latitude;
	var longitud;
	var upLatBounding=-84.573212;
	var downLatBounding=-84.278218;
	var leftLongBounding=9.807527;
	var rightLongBounding=10.264377;
	var nivel = 0;
	var documento="";
	for (var i = 0; i < cantidad; i++) {
		latitude=Math.random() * (downLatBounding - upLatBounding) + upLatBounding;
		longitud =Math.random() * (rightLongBounding- leftLongBounding) + leftLongBounding;
		nivel = Math.floor(Math.random() * (5 - 1 + 1)) + 1;
		documento = {"latitude":latitude,"longitud":longitud,"nivel":nivel};
		db.getCollection('Puntos').insert(documento);
	}
}