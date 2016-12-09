//mongoimport -d nombreBD -c NombreColeccion --type csv --file nombreArchivo.csv --headerline
//mongoimport -d PuntosMuestreo -c DatosCurri  --type csv --file datosCurri.csv --headerline
function(){
    collection = db.DatosCurri.find({},{"fecha":1,"lat":1,"long":1});
    collection.forEach(
    function(datos){
        //actualización de fecha
            var fechaCompleta=datos.fecha;
            db.DatosCurri.update({"_id":datos._id},{$unset:{fecha:""}});
            var fechaHora= fechaCompleta.split(' ');//separamos fecha de hora
            var fecha = fechaHora[0].split('/');//separamos fecha de hora
            var hora = fechaHora[1].split(':');//separamos fecha de hora
            //print(fecha[0]+" "+fecha[1]+" "+fecha[2]+" "+hora[0]+" "+hora[1]);
            var newDate = new Date(fecha[2], fecha[0], fecha[1], hora[0], hora[1]);
        //actualizacion de latlng
            var latit = datos.lat;
            var longit= datos.long;
            var newLat = latit.split(',');//separamos fecha de hora
            var newLng = longit.split(',');//separamos fecha de hora
            var lat = newLat[0]+"."+newLat[1];
            var lng = newLng[0]+"."+newLng[1];

            db.DatosCurri.update({"_id":datos._id},{$unset:{fecha:"",lat:"",long:""}});
            db.DatosCurri.update({"_id" :datos._id},{$set : {"fecha":newDate,"location":{"lat":lat,"lng":lng}}});
        }
    );
}
