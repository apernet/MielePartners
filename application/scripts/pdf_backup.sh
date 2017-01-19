#!/bin/sh

# RESPALDO DE PDFS (Sax 2.0)

# VARIABLES
FECHA_INICIAL=$1
FECHA_FINAL=$2
# EJECUTA SCRIPT
DIRACTUAL="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
SCRIPT="php $DIRACTUAL/cron.php /cron/respaldo_pdfs/$FECHA_INICIAL/$FECHA_FINAL > /dev/null 2>&1 &"

exit 0