#!/bin/sh


echo "INICIO BACKUP IMAGENES $(date)"
fecha=$(date '+%Y%m%d')
ruta_imagenes="/Users/blackcore/Sites/saxv2/html/files/avaluos"
path=$(pwd)
directorio="$path/imagenesbk-$fecha"


if [ ! -d "$directorio" ]; then
    mkdir "$directorio"
fi
chmod 777 $directorio
touch -t `date +%Y%m%d0000` bc_backups
super_comando=$(find $ruta_imagenes -newer bc_backups -type f )
#echo "$super_comando"

for f in  $super_comando; do

	sub=`dirname $f`

	new_file=`basename $f`

    sub1=`basename $sub`

	sub2=`dirname $sub`
	new_dir=`basename $sub2`

    destino="$directorio/$new_dir/$sub1/$new_file"



    if [ ! -d "$directorio/$new_dir" ]; then
        mkdir "$directorio/$new_dir"
    fi
	if [ ! -d "$directorio/$new_dir/$sub1/" ]; then
		 mkdir "$directorio/$new_dir/$sub1/"
	fi
	
    cp -f "$f" "$destino"

done


#tar -cvzf "$directorio.tar.gz" "$directorio"
#rm -Rf "$directorio"

#FILE="$directorio.tar.gz"

#ftp -nvp $HOST <<END_SCRIPT
#    quote USER $USER
#    quote PASS $PASSWD
#    ascii
#    cd test
#    put $FILE
#    quit
#END_SCRIPT
echo "FIN BACKUP $(date)"
exit 0
