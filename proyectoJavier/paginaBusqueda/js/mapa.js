// The following example creates a marker in Stockholm, Sweden using a DROP
// animation. Clicking on the marker will toggle the animation between a BOUNCE
// animation and no animation.

var map; //mapa general 
var markers=[];//marcadores indicadores de calidad del agua
var niveles=[];//es paralelo a vector de marcadores acá se guardan las calidades del agua del marcador i
var filterMarker;//marcador movible para indicar areas de filtro
  var colors = ["null","blue","green","yellow","orange","red"];//colores asociados a cada calidad
  var calidad = ["null","excelente","buena calidad","aceptable","contaminada","fuertemente contaminada"];//nombres asociados a cada calidad

function initMap() {

	  //creación del mapa
	 map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 8,
	    center: {"lat":9.586920,"lng":-83.814613},
	    radius:19
	  });


	//marcador draggable para aplicar filtro
	 filterMarker = new google.maps.Marker({
	    map: map,
	    draggable:true,
	    icon: "data/Templatic-map-icons/arts-crafts.png",
	    title:"colocar en area de filtro",
	    position:{"lat":9.904446,"lng":-84.026786}
	  });

	 //inserción de todos los marcadores presentes en la BD
	 insertMarker();
	//map.addListener('click', function(e) {
	  //placeMarkerAndPanTo(e.latLng, map);
	//});
}


function  insertMarker(){
//peticion ajax al servidor
  $.ajax({
      async:true,
      url: "php/getLocations.php",//devuelve un json con los marcadores que están en la base de datos.
      dataType: "json",
      success:pintar
      });
}



function pintar(jsonData){
  //alert(jsonData[0].location.lat+" "+jsonData[0].location.lng+" "+jsonData[0].color);
	for (var i = 0; i < jsonData.length; i++) {
	    markers[i] = new google.maps.Marker({
	    map: map,
	    position:jsonData[i].location,
	    title: 'Calidad del agua: '+jsonData[i].color,
	    icon:"data/Templatic-map-icons/"+jsonData[i].color+".png"
	  });
	  //markers[i].setAlpha=2;
	  niveles[i]=jsonData[i].color;
	}
}



//eventos de los botones de calidades de agua
document.getElementById("calidad1").onclick = function(){
  for(var i=0;i<niveles.length;i++){
  	if(niveles[i]=="Azul"){
    	markers[i].setVisible(false);  		
  	}
  } 
}

document.getElementById("calidad2").onclick = function(){
	for(var i=0;i<niveles.length;i++){
		if(niveles[i]=="Verde"){
		markers[i].setVisible(false);  		
		}
	} 	  
}

document.getElementById("calidad3").onclick = function(){
	for(var i=0;i<niveles.length;i++){
		if(niveles[i]=="Amarillo"){
		markers[i].setVisible(false);  		
		}
	} 
}

document.getElementById("calidad4").onclick = function(){
	for(var i=0;i<niveles.length;i++){
		if(niveles[i]=="Anaranjado"){
		markers[i].setVisible(false);  		
		}
	} 
}

document.getElementById("calidad5").onclick = function(){
  for(var i=0;i<niveles.length;i++){
  	if(niveles[i]=="Rojo"){
    	markers[i].setVisible(false);  		
  	}
  } 
}


document.getElementById("reset").onclick = function(){
  for(var i=0;i<markers.length;i++){
    markers[i].setVisible(true);
  } 
}


/*function placeMarkerAndPanTo(latLng, map) {
  filterMarker.setPosition(latLng);
  map.panTo(latLng);
}
*/

function aplicarFiltro(valor,flag){
  if (flag) {//caso filtro de radio de influencia
    var dist=0;
    for(var i =0;i<markers.length;i++){
      dist = distance(markers[i].position.lat(), markers[i].position.lng(), filterMarker.position.lat(),  filterMarker.position.lng(), 'K');
      if (dist>valor) {
        markers[i].setVisible(false);
      }
    }
  }else{//caso filtro de rios
  }
}


//calcula la distancia entre dos puntos, retorna en está situación un valor en KM
function distance(lat1, lon1, lat2, lon2, unit) {
    var radlat1 = Math.PI * lat1/180;
    var radlat2 = Math.PI * lat2/180;
    var radlon1 = Math.PI * lon1/180;
    var radlon2 = Math.PI * lon2/180;
    var theta = lon1-lon2;
    var radtheta = Math.PI * theta/180;
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    dist = Math.acos(dist);
    dist = dist * 180/Math.PI;
    dist = dist * 60 * 1.1515;
    if (unit=="K") { dist = dist * 1.609344; }
    if (unit=="N") { dist = dist * 0.8684; }
    return dist;
}


