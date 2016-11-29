#!/bin/sh
//nombre de la carpeta del respaldo -> fecha del respaldo
DIR=`date +%m%d%y`
//direccion donde se va a hacer el respaldo
DEST=/db_backups/$DIR
//se crea la carpeta
mkdir $DEST
// ejecucion de programa de mongo para hacer el respaldo, se guarda en la carpeta que se creó
mongodump -h <your_database_host> -d <your_database_name> -u <username> -p <password> -o $DEST