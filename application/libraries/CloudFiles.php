<?php
define('RAXSDK_CREDENTIALS_PATH', dirname(FCPATH) . '/files/RSCF.txt');
define('RAXSDK_OBJSTORE_REGION', 'DFW');
define('RAXSDK_OBJSTORE_NAME', 'cloudFiles');
define('RAXSDK_TIMEZONE', 'America/Mexico_City');
define('RAXSDK_TIMEOUT', 0);

require_once (APPPATH . 'libraries/php-opencloud/php-opencloud.php');
use OpenCloud\Rackspace;

class CloudFiles
{
    private $cloud;
    private $swift;
    private $container_url;
    private $container;

    public function __construct()
    {
        $this->init();
    }

    public function init($public = false)
    {
        // INICIALIZA OPENCLOUD
        $CI = & get_instance();
    	$url_type = $CI->config->item('cloudfiles_interno')? 'internalURL' : 'publicURL';

        $CI->load->helper('file');
        $endpoint = $CI->config->item('rackspace_url');
        $credentials = $CI->config->item('rackspace');
        $cloud = new Rackspace($endpoint, $credentials);
        $cloud->SetDefaults('ObjectStore',RAXSDK_OBJSTORE_NAME, RAXSDK_OBJSTORE_REGION, $url_type);

        if (!file_exists(RAXSDK_CREDENTIALS_PATH))
        {
            $cloud->Authenticate();
            $data = $cloud->ExportCredentials();
            $wr = write_file(RAXSDK_CREDENTIALS_PATH, base64_encode(serialize($data)));
            if (!$wr)
            {
                $msg = 'CLOUDFILES - Error al crear archivo de credenciales.';
                log_message('error', $msg);
            }
        } else
        {
            $data = unserialize(base64_decode(read_file(RAXSDK_CREDENTIALS_PATH)));
            $cloud->ImportCredentials($data);
        }

        $swift = $cloud->ObjectStore();
        $this->swift = $swift;
    }

    public function container_set($name = FALSE)
    {
        // SELECCIONA EL CONTENEDOR
        if (! $name)
        {
        	$CI = & get_instance();
            $name = $CI->config->item('pdf_carpeta');
        }
        $name = strtoupper($name);

        try
        {
            $container = $this->swift->Container($name);
        } catch ( Exception $e )
        {
            // NO EXISTE EL CONTENEDOR
            try
            {
                // CREAR EL CONTENEDOR
                $container = $this->swift->Container()->Create(array (
                    'name' => $name
                ));
                $container = $this->swift->Container($name);
                //$container->PublishToCDN(); // HACERLO PUBLICO CDN
            } catch ( Exception $e )
            {
                // NO SE PUDO CREAR
                debug($e);
                return false;
            }
        }

        $this->container = $container;
        return $container;
    }

    private function pdata($path)
    {
        // OBTIENE EL NOMBRE DEL CONTENEDOR Y DEL ARCHIVO DE LA RUTA
        // LA PRIMERA CARPETA DE $path_destino ES EL CONTENEDOR
        $destino_aux = explode('/', $path);
        $container = array_shift($destino_aux);
        $filename = implode('/', $destino_aux);
        $res = new stdClass();
        $res->filename = $filename;
        $res->container = $container;
        return $res;
    }

    public function file_put($origen, $destino)
    {
        // SUBE ARCHIVO A CLOUDFILES
        $cf = $this->pdata($destino);
        $container = $this->container_set($cf->container);
        if ($container != FALSE)
        {
            try
            {
                $file = $container->DataObject();
                $res = $file->Create(array (
                    'name' => $cf->filename
                ), $origen);
                $this->init(true);
                return true;
            } catch ( Exception $e )
            {
                return FALSE;
            }
        } else
        {
            return FALSE;
        }
    }


    public function file_exists($path)
    {
        // REVISA SI EXISTE UN ARCHIVO EN CF
        // LA PRIMERA CARPETA DE $path_destino ES EL CONTENEDOR
        $cf = $this->pdata($path);
        $container = $this->container_set($cf->container);
        if(empty($this->container_url))
        {
            if ($container != FALSE)
            {
                try
                {
                    $this->container_url = $container->PublicURL();
//                     $file = $container->DataObject($cf->filename);
//                     $cf_path = $file->PublicURL();
//                     return $cf_path;
                    return $this->container_url . '/' . $cf->filename;

                } catch ( Exception $e )
                {
                    return false;
                }
            } else
            {
                return FALSE;
            }
        }
        return $this->container_url . '/' . $cf->filename;
    }

    public function file_get_string($path)
    {
        // DESCARGA UN ARCHIVO DE CLOUDFILES
        // LA PRIMERA CARPETA DE $path_destino ES EL CONTENEDOR
        $cf = $this->pdata($path);
        $container = $this->container_set($cf->container);
        if ($container != FALSE)
        {
            try
            {
                $file = $container->DataObject($cf->filename);
                return $file->SaveToString();
            } catch ( Exception $e )
            {
                return false;
            }
        } else
        {
            return FALSE;
        }
    }

    public function file_get($path, $dest)
    {
        // DESCARGA UN ARCHIVO DE CLOUDFILES
        // LA PRIMERA CARPETA DE $path_destino ES EL CONTENEDOR
        $cf = $this->pdata($path);
        $container = $this->container_set($cf->container);
        if ($container != FALSE)
        {
            try
            {
                $file = $container->DataObject($cf->filename);
                $file->SaveToFilename($dest);
                return true;
            } catch ( Exception $e )
            {
                return false;
            }
        } else
        {
            return FALSE;
        }
    }

    public function obtener_en_cadena($nombre_contenedor, $nombre_archivo)
    {
    	$CI = & get_instance();
    	$contenedor = $CI->config->item('rackspace_container');
        return $this->file_get_string($contenedor . '/' . $nombre_archivo);
    }

    public function obtener_en_archivo($nombre_contenedor, $nombre_archivo, $archivo_salida)
    {
    	$CI = & get_instance();
    	$contenedor = $CI->config->item('rackspace_container');
        return $this->file_get($contenedor . '/' . $nombre_archivo, $archivo_salida);
    }

    public function url_publica($archivo)
    {
    	$CI = & get_instance();
    	$cdn = $CI->config->item('cdn');
    	$cdn .= '/'. $archivo;
        return $cdn;
    }

    public function existe_archivo($archivo)
    {
    	$CI = & get_instance();
    	$cdn = $CI->config->item('cdn');
    	$cdn .= '/'. $archivo;
        return $cdn;
    }

    /**
     * Funcion para subir un archivo a CloudFiles
     *
     * @param string dest Nombre destino del archivo
     * @param string path Path completo del archivo
     */
    public function subir($path, $dest)
    {
    	$CI = & get_instance();
    	$container = $CI->config->item('rackspace_container');
      	if($this->file_put($path, $container .'/'. $dest))
      	{
          	$cdn = $CI->config->item('cdn');
          	$cdn .= '/'. $dest;
          	return $cdn;
      	}
      	else
        	return false;
    }
}
