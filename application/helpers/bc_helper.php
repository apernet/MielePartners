<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		BlackCore
 * @author		Sistemas BlackCore de Mexico S.A. de C.V.
 * @copyright	Copyright (c) 2011, Sistemas BlackCore de Mexico S.A. de C.V.
 * @license		
 * @link		http://www.blackcore.com.mx
 * @since		Version 1.01
 * @filesource
 */

// ------------------------------------------------------------------------
if ( ! function_exists('debug'))
{
	function debug($var = FALSE, $showHtml = FALSE, $exit = FALSE) {
			$CI =& get_instance();
			if(!$CI->config->item('debug') || (($CI->session->userdata('grupos_id')!=1 && isset($_SERVER['REMOTE_ADDR'])) && ENVIRONMENT!='development'))
				return;
			$calledFrom = debug_backtrace();
			echo '<strong>' . $calledFrom[0]['file'] . '</strong>';
			echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
			echo "\n<pre style=\"background: #f0f0f0; padding: 1em;\">\n";
			$var = print_r($var, TRUE);
			if ($showHtml) {
				$var = str_replace('<', '&lt;', str_replace('>', '&gt;', $var));
			}
			echo $var . "\n</pre>\n";
			if($exit)
				exit();
	}
}

if ( ! function_exists('debuglq'))
{
	// DEVUELVE EL ULTIMO QUERY REALIZADO A LA BASE DE DATOS
	function debuglq($exit = FALSE) {
			$CI =& get_instance();
			if(!$CI->config->item('debug') || ($CI->session->userdata('grupos_id')!=1 && isset($_SERVER['REMOTE_ADDR'])))
				return;
			$var=$CI->db->last_query();			
			$calledFrom = debug_backtrace();
			echo '<strong>' . $calledFrom[0]['file'] . '</strong>';
			echo ' (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
			echo "\n<pre style=\"background: #f0f0f0; padding: 1em;\">\n";
			$var = print_r($var, TRUE);
			echo $var . "\n</pre>\n";
			if($exit)
				exit();
	}
}

if ( ! function_exists('get_profiler'))
{
	function get_profiler() {

		$CI =& get_instance();
		$CI->load->library('profiler');	
		$p= $CI->profiler->run();
		return $p;
	}
}		 

if ( ! function_exists('bc_buscador'))
{
	function bc_buscador() 
	{	
		$CI =& get_instance();
		if(!empty($_POST))
		{
			$basepath=$CI->uri->segment(1).'/'.$CI->uri->segment(2);	
			foreach($_POST as $label => $request){
				if($request !== '')
				{
					$uri[$label] = $request;
				}
			}		
			$offset=0;
			$url = $CI->uri->assoc_to_uri($uri);
			if(empty($url))
				redirect($basepath.'/'.$offset); // SI NO TRAE CONDICIONES ELIMINA EL OFFSET
			redirect($basepath.'/'.$url.'/'.$offset);
		}
		else
		{	
			$cond=$CI->uri->uri_to_assoc(3);
			if($CI->uri->total_segments()%2!=0)
				array_pop($cond);
			$bc_buscador['base_url']=$CI->uri->segment(1).'/'.$CI->uri->segment(2).'/'.$CI->uri->assoc_to_uri($cond);
			
			foreach($cond as $k=>$v)
			{				
				$cond[$k]=urldecode($v);
			}
			
			$bc_buscador['cond']=$cond;
			$bc_buscador['uri_segment'] = $CI->uri->total_segments();
			$bc_buscador['offset']=0;
			if($CI->uri->total_segments()>2 && $CI->uri->total_segments()%2!=0)	
				$bc_buscador['offset']=$CI->uri->segment($CI->uri->total_segments());
				
			return $bc_buscador;
		}			
	}
}

if ( ! function_exists('get_catalogos_id'))
{
	function get_catalogos_id($nombre) 
	{
		$CI =& get_instance();
		$CI->db->where('nombre',$nombre);
		$CI->db->select('id');
		$catalogos_id=$CI->db->get('catalogos')->row();
		if(!empty($catalogos_id))
			return $catalogos_id->id;
		else
			return FALSE;	
	}
}


if ( ! function_exists('catalogo'))
{
	function catalogo($catalogo, $mostrar_id=true, $alfabetico=false) 
	{
		$catalogos_id=get_catalogos_id($catalogo);
		if(empty($catalogos_id))
			return array();
			
		$CI =& get_instance();	
		$CI->db->where('catalogos_id',$catalogos_id);
		if($alfabetico)
			$CI->db->order_by('valor','ASC');
		else
			$CI->db->order_by('clave','ASC');
			
		$datos=$CI->db->get('elementos')->result_object();
		$res=array();
	
		foreach($datos as $v)
		{
			if($CI->config->item('mostrar_catalogo_id') && $mostrar_id)
				$res[$v->clave]=$v->clave.' - '.$v->valor;
			else
				$res[$v->clave]=$v->valor;
		}
		if(!$alfabetico)
			ksort($res);
		return $res;
	}
}

if ( ! function_exists('elemento'))
{
	function elemento($catalogo, $clave) 
	{
		if(!empty($catalogo) && !empty($clave))
		{
			$catalogos_id=get_catalogos_id($catalogo);
			if(empty($catalogos_id))
			return FALSE;
			$CI =& get_instance();
			
			
			$CI->db->where('catalogos_id',$catalogos_id);
			$CI->db->where('clave',$clave);
			$datos=$CI->db->get('elementos')->row();
			if(empty($datos))
				return FALSE;
			return $datos->valor;
		}
		else
			return NULL;	
	}
}

if ( ! function_exists('indice_catalogo'))
{
	function indice_catalogo($valor, $catalogo) 
	{
		if(!empty($catalogo) && !empty($valor))
		{
			$catalogos_id=get_catalogos_id($catalogo);
			if(empty($catalogos_id))
				return FALSE;
				
			$CI =& get_instance();
			$CI->db->select('clave');
			$CI->db->where('catalogos_id',$catalogos_id);
			$CI->db->where('valor',$valor);
			$datos=$CI->db->get('elementos')->row();
			if(empty($datos))
				return FALSE;
			return $datos->clave;
		}
		else
			return NULL;	
	}
}

if ( ! function_exists('is_ajax'))
{
	function is_ajax() {
		$CI =& get_instance();
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH']=="XMLHttpRequest");
	}
}

if ( ! function_exists('ife'))
{
	function ife($original,$op) {
		return (isset($original) && $original!='' && !empty($original))?$original:$op;
	}
}

if ( ! function_exists('bc_null_empty'))
{
	function bc_null_empty($data){
		foreach($data as $k=>$v)
		{
			if($v==='')
				$data[$k]=NULL;
		}
		return $data;
	}
	
}

if ( ! function_exists('quitar_acentos'))
{
	function quitar_acentos($cadena){
		$acentos=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ");
		$no_acentos=array("a","e","i","o","u","A","E","I","O","U","Ñ");
		$cadena=str_replace($acentos,$no_acentos, $cadena);
		return $cadena;
	}
}
if ( ! function_exists('mayuscula'))
{
	function mayuscula($cadena){
		return strtoupper(quitar_acentos($cadena));
	}
}
if ( ! function_exists('mayuscula_array'))
{
	function mayuscula_array($array){
  		if (is_array($array)){
    		$result_array = array();
    		foreach($array as $key => $value){
      			if (array_type($array) == "map"){
        			// encode both key and value
        			if (is_array($value)){
          				// recursion
          				$result_array[$key] = mayuscula_array($value);
        			}else{
          			// no recursion
          				if (is_string($value)){
            				$result_array[$key] = mayuscula($value);
          				} else  {
            			// do not re-encode non-strings, just copy data
            			$result_array[$key] = $value;
          				}
        			}

      			} else if (array_type($array) == "vector"){
        			// encode value only
        			if (is_array($value)){
          				// recursion
          				$result_array[$key] = mayuscula_array($value);
        			} else {
          				// no recursion          
          				if (is_string($value)){
            				$result_array[$key] = mayuscula($value);
          				} else {
            				// do not re-encode non-strings, just copy data
            				$result_array[$key] = $value;
          				}
        			}	
      			}
    		}
    		return $result_array;
		}
  		return false;    //not array
	}
}	
if ( ! function_exists('array_type'))
{
	function array_type($array){
		if(is_array($array)){
			$next = 0;
    		$return_value = "vector";  // we have a vector until proved otherwise
			foreach ($array as $key => $value){
      			if($key != $next){
        			$return_value = "map";  // we have a map
        			break;
      			}
      		$next++;
    		}
    		return $return_value;
  		}
  		return false;    // not array
	}
}	

if ( ! function_exists('ver'))
{
	function ver($valor=null){
		if(isset($valor))
			echo $valor;
		else
			echo '';
	}
}

if ( ! function_exists('ver_num'))
{
	function ver_num($valor=null,$decimales=2){
		if(isset($valor))
			echo number_format($valor,$decimales);
		else
			echo '';
	}
}

if ( ! function_exists('num'))
{
	function num($valor=null,$decimales=2){
		if(isset($valor))
			return number_format($valor,$decimales);
		else
			return '';
	}
}

if ( ! function_exists('ver_moneda'))
{
	function ver_moneda($valor=null){
		if(isset($valor))
			echo '$ '.number_format($valor,2);
		else
			echo '';
	}
}

if ( ! function_exists('moneda'))
{
	function moneda($valor=null){
		if(isset($valor) && $valor!='')
		{
			return '$ '.number_format($valor,2);
		}
		else 
		{
		    return '$ 0.00';
		}
	}
}

if ( ! function_exists('si_no'))
{
	function si_no($valor){
		echo $valor?'Si':'No';
	}
}

if ( ! function_exists('ver_fecha'))
{
	function ver_fecha($valor){
		if(empty($valor) || $valor=='')
			return '';
		echo strftime("%d/%m/%Y",strtotime($valor));
	}
}

if ( ! function_exists('ver_fecha_hora'))
{
	function ver_fecha_hora($valor){
		if(empty($valor))
			return '';
		echo strftime("%d/%m/%Y %X",strtotime($valor));
	}
}

if ( ! function_exists('get_fecha'))
{
	function get_fecha($valor){
		if(empty($valor))
			return '';
		return strftime("%d/%m/%Y",strtotime($valor));
	}
}

if ( ! function_exists('humano'))
{
	function humano($valor){
		if(empty($valor))
			return '';
		return str_replace('_',' ',str_replace('_id','',$valor));
	}
}

if ( ! function_exists('bc_log'))
{
	function bc_log($tipos_id,$mensaje,$datos=FALSE){
		$CI =& get_instance();
		$data['created']=date('Y-m-d H:i:s');
		$data['usuarios_id']=$CI->session->userdata('id');
		$data['tipos_id']=$tipos_id;
		if($datos)
		{
			$var = '<pre>';
			$var .= print_r($datos, true);
			$var .= '</pre>';
			$data['datos']=$var;
		}
		$data['mensaje']=$mensaje;
		$CI->db->insert('logs', $data);
	}
}

if ( ! function_exists('xml2arrayclear'))
{
	function xml2arrayclear($xml_array)
	{
		foreach($xml_array as $k=>$v)
		{
			if(is_array($v))
			{
				if(empty($v) || $v==array(0))
				{
					$xml_array[$k]='';
				}	
				else
				{
					$xml_array[$k]=xml2arrayclear($v);
				}	
			}	
		}	
		return $xml_array; 
	}
}


if ( ! function_exists('xml2array'))
{
	function xml2array($stringXML)
	{
		$result=json_decode(json_encode((array) simplexml_load_string($stringXML)),1);
		$result=xml2arrayclear($result);
		return $result; 
	}
}

if ( ! function_exists('tiene_permiso'))
{
	function tiene_permiso($funcion,$grupos_id=null)
    {
	   	$CI =& get_instance();
    	if(empty($grupos_id))
    		$grupos_id = $CI->session->userdata('grupos_id');
		$CI->db->select('funciones.id');
		$CI->db->where('funciones.funcion',$funcion);
		$CI->db->join('permisos', 'permisos.funciones_id = funciones.id');
		$CI->db->where('permisos.grupos_id',$grupos_id);
		$q = $CI->db->get('funciones')->row();
		if(!empty($q))
			return TRUE;
		return FALSE;	
    }
}
    
if ( ! function_exists('quitar_simbolos'))
{
	function quitar_simbolos($cadena){
		$simbolos=array(',','/','\\','$','(',')','_','-','º','ª','&','@','#','*','+',';',':','%');
		$cadena=str_replace($simbolos,'', $cadena);
		return $cadena;
	}
}

if ( ! function_exists('xml2arrayfile'))
{
	function xml2arrayfile($stringXML)
	{
		$result=json_decode(json_encode((array) simplexml_load_file($stringXML)),1);
		$result=xml2arrayclear($result);
		return $result; 
	}
}

if ( ! function_exists('precio_con_iva'))
{
	function precio_con_iva($valor=null){

		$CI =& get_instance();
		$iva = ($CI->config->item('iva_porcentaje')/100);

		if(isset($valor))
		{
			$precio_con_iva = $valor + ($valor * $iva);
			return '$ ' . number_format($precio_con_iva, 2);
		}else
			return '';
	}
}

/**
 *  FORMATO A RFC, permite el caracter &
 */
if ( ! function_exists('rfc_formato'))
{
	function rfc_formato($rfc="")
	{
		if(!empty($rfc))
		{
			$rfc = preg_replace("/ +/","",$rfc);
			$rfc = str_replace(";","",$rfc);
			$rfc = strtoupper($rfc);
		}
		return $rfc;
	}
}