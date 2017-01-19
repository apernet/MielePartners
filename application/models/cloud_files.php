<?php

require_once('base.php');

class Cloud_Files extends Base {

    public function Cloud_Files() {
        parent::__construct();
    }

    /**
     * Sube un archivo, solo para archivos dentro de alguna carpeta files/, de lo contrario lo
     * guarda con el path completo
     *
     */
    public function subir($path, $pathDest) {
        $CI =& get_instance();
        $CI->load->library('CloudFiles');

        $pathExplode = explode('files/', $pathDest);

        if(count($pathExplode) > 1) {
          $pathDest = 'files/' . $pathExplode[1];
        }

        return $CI->cloudfiles->subir($path, $pathDest);
    }

    public function img_tmp($path) {
        $contenedor = $this->config->item('rackspace_container');
        $extension=substr ($path, - 3);
        $file = str_replace('/', '', $path);
        $destino = '/var/tmp/'.$contenedor.$file.'.'.$extension;
        if(!file_exists($destino)) {
            $CI =& get_instance();
            $CI->load->library('CloudFiles');
            if($CI->cloudfiles->obtener_en_archivo($contenedor, $path, $destino)) {
                return $destino;
            } else {
                false;
            }
        }
        return $destino;
    }


    public function url_publica($path) {
        $CI =& get_instance();
        $CI->load->library('CloudFiles');

        $ruta = explode('html/', $path);
        if(count($ruta) > 1) {
            $path = $ruta[1];
        } else {
            $ruta = explode('application/', $path);
            if(count($ruta) > 1) {
                $path = $ruta[1];
            }
        }

        return $CI->cloudfiles->url_publica($path);
    }

    public function existe_archivo($path) {
       $CI =& get_instance();
       $CI->load->library('CloudFiles');

       $ruta = explode('html/', $path);
        if(count($ruta) > 1) {
            $path = $ruta[1];
        } else {
            $ruta = explode('application/', $path);
            if(count($ruta) > 1) {
                $path = $ruta[1];
            }
        }
        return $CI->cloudfiles->existe_archivo($path);
    }

}
