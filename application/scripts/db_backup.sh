#!/bin/sh

# RECIBE LOS SIGUIENTES PARAMETROS:
# $1 DESTINATION (carpeta destino o abreviacion en minuscula como esta en el servidor de respaldos)
# $1 DBUSER
# $2 DBPASSWORD
# $3 DBNAME
# $4 DBHOST

fecha=$(date '+%Y-%m-%d_%H%M%S')
# DIA DE LA SEMANA PARA GENERAR UN ULTIMO RESPALDO DIARIO SE TENDRAN RESPALDOS DE HOY Y OCHO DIAS HACIA ATRAS CON LA FRECUENCIA DE HORAS
DIA=$(date '+%u-%H')   

# DATOS DEL SERVIDOR DE RESPALDOS
REMOTEHOST='blackcore.evbackup.com'
REMOTEUSER='blackcore'
REMOTEPASS='xI70wVTY'

# DATOS CONEXION CLOUDFILES
CLOUDFILESAPIKEY='15accd0268babac4ef317fb8c9ceb919'
CLOUDFILESUSER='rezorte'
CLOUDFILESREGION='dfw'
CLOUDFILESAUTH='https://identity.api.rackspacecloud.com/v2.0/'
CLOUDFILESCONTAINER='DB-BACKUP'


# CONTAINER=$1
DBUSER=$1
DBPASSWORD=$2
DBNAME=$3
DBHOST=$4

echo "$1 - INICIO BACKUP $fecha"

# Rutas de origen y destino
DIRACTUAL="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
FILEDESTINATION="$DIRACTUAL/db_backups"
echo $FILEDESTINATION

# Si no existe el destino lo crea
if [ ! -d $FILEDESTINATION ]; then
	mkdir "$FILEDESTINATION"
	chmod 775 "$FILEDESTINATION"
fi

#echo "DESTINO: $DESTINATION\n"
#echo "DBUSER: $DBUSER\n"
#echo "DBPASS: $DBPASSWORD\n"
#echo "DBNAME: $DBNAME\n"

# VARDUMP BASE DE DATOS
# DBFILENAME="$fecha.sql"
DBFILENAME="$DIA-sax-$DBNAME.sql"
DBFILE="$FILEDESTINATION/$DBFILENAME"
TARDBFILENAME="$DBFILENAME.tar.gz"
TARDBFILE="$FILEDESTINATION/$TARDBFILENAME"
TARDBOLDFILENAME="$DIA-sax_old.sql.tar.gz"

#/usr/bin/nice -n 19 /usr/bin/mysqldump  -ntv --user="$DBUSER" --password="$DBPASSWORD" --host="$DBHOST" $DBNAME -c | /usr/bin/nice -n 19 /bin/gzip -9 > $DBFILE

/usr/bin/mysqldump --single-transaction --quick --lock-tables=false -nv --user="$DBUSER" --password="$DBPASSWORD" --host="$DBHOST" $DBNAME > $DBFILE

# COMPRIMIR ARCHIVO
tar cvfz $TARDBFILE $DBFILE

echo $DBFILE
echo $DBFILENAME

echo "SUBE A CLOUDFILES EN $CLOUDFILESCONTAINER"



#ftp -nvp "$REMOTEHOST" << END_SCRIPT
#    quote USER $REMOTEUSER
#    quote PASS $REMOTEPASS
#    ascii
#    mkdir $REMOTEFOLDER/db_backups
#    del $REMOTEFOLDER/db_backups/$TARDBOLDFILENAME
#    rename $REMOTEFOLDER/db_backups/$TARDBFILENAME $REMOTEFOLDER/db_backups/$TARDBOLDFILENAME
#    put $TARDBFILE $REMOTEFOLDER/db_backups/$TARDBFILENAME
#    quit
#END_SCRIPT

echo '/usr/bin/turbolift -a $CLOUDFILESAPIKEY -u $CLOUDFILESUSER -r $CLOUDFILESREGION --os-auth-url=$CLOUDFILESAUTH upload -c $CLOUDFILESCONTAINER -s $TARDBFILE'

/usr/bin/turbolift -a $CLOUDFILESAPIKEY -u $CLOUDFILESUSER -r $CLOUDFILESREGION --os-auth-url=$CLOUDFILESAUTH upload -c $CLOUDFILESCONTAINER -s $TARDBFILE

rm $DBFILE
rm $TARDBFILE

echo "FIN BACKUP $(date '+%Y-%m-%d_%H%M%S')"
exit 0

