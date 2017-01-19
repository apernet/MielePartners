#!/bin/sh

# RECIBE LA ABREVIACION DE LA UNIDAD COMO PRIMER ARGUMENTO
# IMV
# ANEP
# GMS
# BASA
# AGAL

fecha=$(date '+%Y-%m-%d_%H%M%S')
HOST="localhost"

echo "$1 - INICIO BACKUP $fecha"

if [ $1 = "IMV" ]; then
	REMOTEHOST="ftp.emkolabs.com"
	REMOTEUSER="saxbk_imv"
	REMOTEPASS="!vSCK#*s"

	path="/var/www/vhosts/sax.imv-valuadores.com.mx"
	DB="DS24018_sax2_imv"
	USER="codero2_imv_db"
	PASS="zvqGl6CrEM"
fi

if [ $1 = "ANEP" ]; then
	REMOTEHOST="ftp.emkolabs.com"
	REMOTEUSER="saxbk_anep"
	REMOTEPASS="CKmGv3^A"

	path="/var/www/vhosts/sax.anep.com.mx"
	DB="sax2_anep"
	USER="codero1_anep_db"
	PASS="8Kn32c4PMX"	
fi

if [ $1 = "GMS" ]; then
	REMOTEHOST="ftp.emkolabs.com"
	REMOTEUSER="saxbk_gms"
	REMOTEPASS="Ut47u*Wn"

	path="/var/www/vhosts/sax.gmsavaluos.com.mx"
	DB="sax2_gms"
	USER="codero3_gms_db"
	PASS="9yHc2TKDV7"	
fi

if [ $1 = "BASA" ]; then
	REMOTEHOST="ftp.emkolabs.com"
	REMOTEUSER="saxbk_basa"
	REMOTEPASS="9F?rgjMj"

	path="/var/www/vhosts/sax.bufetedeavaluos.com"
	DB="sax2_basa"
	USER="codero3_basa_db"
	PASS="9NQsI2mQlm"	
fi

if [ $1 = "AGAL" ]; then
	REMOTEHOST="ftp.emkolabs.com"
	REMOTEUSER="saxbk_agal"
	REMOTEPASS="q645U-Uw"

	path="/var/www/vhosts/sax.agal.com.mx"
	DB="sax2_agal"
	USER="codero3_agal_db"
	PASS="spKKocy7ja"	
fi


# Rutas de origen y destino
BKPATH="$path/backups/"
FCPATH="$path/system/aplication/files/"
APPPATH="$path/html/files/"

# Si no existe el destino lo crea
if [ ! -d $BKPATH ]; then
	mkdir "$BKPATH"
	chmod 775 "$BKPATH"
fi

DESPATH="$BKPATH$fecha"
echo $DESPATH
if [ ! -d $DESPATH ]; then
	mkdir "$DESPATH"
	chmod 775 "$DESPATH"
fi

# VARDUMP BASE DE DATOS
DBFILENAME="$DB-bk-$fecha.sql.gz"
DBFILE="$DESPATH/$DBFILENAME"
/usr/bin/nice -n 19 /usr/bin/mysqldump -u "$USER" --password="$PASS" --host="$HOST" $DB -c | /usr/bin/nice -n 19 /bin/gzip -9 > $DBFILE

echo $DBFILE
echo $DBFILENAME

echo "SUBE A $REMOTEHOST"
ftp -nvp "$REMOTEHOST" << END_SCRIPT
    quote USER "$REMOTEUSER"
    quote PASS "$REMOTEPASS"
    ascii
    put $DBFILE $DBFILENAME     
    quit
END_SCRIPT

# ELIMINA EL ARCHIVO
rm $DBFILE

echo "FIN BACKUP $(date '+%Y-%m-%d_%H%M%S')"
exit 0