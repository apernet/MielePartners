<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Validation Class
 *
 * Extends Form_validation library
 *
 * REGLAS DE VALIDACION ADICIONALES
 * 
 */
class MY_Form_validation extends CI_Form_validation {

	/*
	 * USUARIOS DISTINTOS
	 * CONTROLADOR Y VALUADOR DIFERENTES 
	 * 
	 */
	function usuarios_distintos()
	{
		if($_POST['controlador_id']==$_POST['valuador_id'])
		{
			$this->CI->form_validation->set_message('usuarios_distintos', "El controlador y el valuador deben ser diferentes.");
			return FALSE;
		}
		return TRUE;
	}
	
	/*
	 * USUARIOS TESORERIA DISTINTOS
	 * RESPONSABLE TECNICO Y VALUADOR DE TESORERIA DIFERENTES 
	 * 
	 */
	function usuarios_tesoreria_distintos()
	{
		if($_POST['responsable_tecnico_id']==$_POST['valuador_tesoreria_id'])
		{
			$this->CI->form_validation->set_message('usuarios_tesoreria_distintos', "El responsable t&eacute;cnico y el valuador de Tesorer&iacute;a deben ser diferentes.");
			return FALSE;
		}
		return TRUE;
	}
	
	/*
	 * REQUIRED IF
	 * 
	 * Requerido si el valor del campo en el primer parametro es igual al segundo parametro.
	 * EJEMPLO required_if[tipo_inmueble_id,6]
	 * Se requiere modificar el core Form_validation lineas 494 y 532
	 * por: if (!preg_match("/required_if\[.+\]/", implode(' ', $rules))) return;
	 */	
	function required_if($str, $parms)
	{		
		list($campo, $valor,$label,$desc)=explode(',',$parms,4);		
		if($_POST[$campo]==$valor && $str=='')
		{
			$campo=str_replace(array('_id','_'),array('',' '),$campo);
			$this->CI->form_validation->set_message('required_if', "El campo %s debe contener un dato si {$label} es '{$desc}'.");
			return FALSE;
		}
		return TRUE;
	}
	
	
	/*
	 * REQUIRED IF NOT
	 * 
	 * Requerido si el valor del campo en el primer parametro es diferente al segundo parametro.
	 * EJEMPLO required_if[tipo_inmueble_id,6]
	 * Se requiere modificar el core Form_validation lineas 494 y 532
	 * por: if (!preg_match("/required_if\[.+\]/", implode(' ', $rules))) return;
	 */	
	function required_if_not($str, $parms)
	{		
		list($campo, $valor,$label,$desc)=explode(',',$parms,4);
				
		if($_POST[$campo]!=$valor && $str=='')
		{
			$campo=str_replace(array('_id','_'),array('',' '),$campo);
			$this->CI->form_validation->set_message('required_if_not', "El campo %s debe contener un dato si {$label} es diferente de '{$desc}'.");
			return FALSE;
		}
		elseif($_POST[$campo]==$valor && $str!='')
		{
			return '';
		}		
		return TRUE;
	}
	
	
	/**
	 * RANGE
	 *
	 * VALIDA EL RANGO RECIBE min-max   
	 * EJEMPLO range[1,1000]
	 *
	 * @access	public
	 * @param	string min-max
	 * @return	bool
	 */
	function range($valor,$parms)
	{		
		list($min, $max) = explode(",", $parms, 2);
		if($valor<$min||$valor>$max)
		{
			$this->CI->form_validation->set_message('range', "El campo %s debe contener un valor entre {$min} y {$max}.");
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * FACTOR
	 *
	 * VALIDA EL RANGO de los factores en config   
	 * EJEMPLO rango[1,1000]
	 *
	 * @access	public
	 * @param	string min-max
	 * @return	bool
	 */
	function factor($valor)
	{		
		$min=$this->CI->config->item('factor_min');
		$max=$this->CI->config->item('factor_max');
		if($valor<$min||$valor>$max)
		{
			$this->CI->form_validation->set_message('factor', "El campo %s debe contener un valor entre {$min} y {$max}.");
			return FALSE;
		}
		return TRUE;
	}
	
	
	/*
	 * IMAGE VALIDATION
	 *  Se requiere modificar el core Form_validation lineas 494 y 532
	 * if (!preg_match("/valid_image/", implode(' ', $rules))) return;
	 */
	function valid_image($str,$campo) {
		
		if($_FILES[$campo]['tmp_name']):		
			/*
	   		//Check for image upload
		    if(!isset($_FILES['fuente_pantalla'])) {
		        $this->validation->set_message('valid_image', 'Imagen requerida.');
		        return false;
		    }
		    
		    //Check for file size
		    if($_FILES['image']['size'] == 0) {
		        $this->validation->set_message('valid_image', 'Imagen requerida.');
		        return false;
		    }
		    */		
		    //Check for upload errors
		    if($_FILES[$campo]['error'] != UPLOAD_ERR_OK) {
		        $this->CI->form_validation->set_message('valid_image', 'Error al subir el archivo, intente nuevamente.');
		        return false;
		    }
		    
		    //Check for valid image upload
		    $imginfo = getimagesize($_FILES[$campo]['tmp_name']);
		    if(!$imginfo) {
		        $this->CI->form_validation->set_message('valid_image', '&Uacute;nicamente se permiten archivos de imagen.');
		        return false;
		    }
		    
		    //Check for valid image types
		    //if( !($imginfo[2] == 1 || $imginfo[2] == 2 || $imginfo[2] == 3) ) // JPG PNG Y GIF
		    if( !($imginfo['mime'] == 'image/jpeg' ))
		    {
		        $this->CI->form_validation->set_message('valid_image', 'Solo se permiten archivos en formato JPG.');
		        return false;
		    }
		    
		    //Check for existing image
		    /*
		    if(file_exists(SITEPATH.'uploads/images/'.$_FILES['image']['name'])) {
		        $this->validation->set_message('valid_image', 'Image by this name already exists');
		        return false;
		    }*/
	    endif;
	    return true;
} 
	
	
	/**
	 * CATALOGO
	 *
	 * DEBE CONTENER UN ENTERO DENTRO DEL RANGO NO EXCLUYENTE QUE SE RECIBE EN minimo-maximo   
	 * EJEMPLO catalogo[1-7]
	 *
	 * @access	public
	 * @param	string min-max
	 * @return	bool
	 */
	function catalogo($str, $parms)
	{		
		//$CI =& get_instance();
		list($min, $max) = explode("-", $parms, 2);
		$this->CI->form_validation->set_message('catalogo', "El campo %s debe contener un valor del cat&aacute;logo entre {$min} y {$max}.");
		return (bool) preg_match( "/(^[{$min}-{$max}]$)|^$/", $str);
	}
	
	
	/**
	 * DECIMALES
	 *
	 * VALIDA EL NUMERO DE ENTEROS Y DECIMALES RECIBE enteros.decimales   
	 * EJEMPLO decimales[7.2]
	 *
	 * @access	public
	 * @param	string min-max
	 * @return	bool
	 */
	function decimales($str, $parms)
	{		
		list($enteros, $decimales) = explode(".", $parms, 2);
		$this->CI->form_validation->set_message('decimales', "El campo %s debe contener un valor num&eacute;rico con un m&aacute;ximo de {$enteros} enteros y {$decimales} decimales.");
		return (bool) preg_match( "/(^\d{1,$enteros}(\.\d{1,$decimales})?$)|^$/", $str);
	}
	
	/*
	 * FECHA VALIDA
	 * 
	 * VALIDA QUE LA FECHA VENGA EN FORMATO dd/mm/aaaa
	 * 
	 * @access	public
	 * @return	bool
	 * 
	 */
	function fecha($str)
	{
		$this->CI->form_validation->set_message('fecha', "El campo %s debe contener una fecha en formato dd/mm/aaaa.");
		return (bool) preg_match("/(^([0]?[1-9]|[1|2][0-9]|[3][0|1])(\/)([0]?[1-9]|[1][0-2])(\/)([0-9]{4}|[0-9]{2})$)|^$/", $str);
	}
	
	/*
	 * FECHA NO POSTERIOR
	 * 
	 * VALIDA QUE LA FECHA SEA ANTERIOR O IGUAL A LA ACTUAL
	 * 
	 * @access	public
	 * @return	bool
	 * 
	 */
	function no_posterior($str)
	{
		list($dia, $mes, $anio) = explode("/", $str, 3);
		$fecha = date('Y-m-d', mktime(0,0,0,$mes,$dia,$anio));
		if(strtotime($fecha)>strtotime(date('Y-m-d')))
		{
			$this->CI->form_validation->set_message('no_posterior', 'El campo %s no puede ser posterior a la fecha actual.');
			return FALSE;
		}
		else
		{
			return $fecha;
		}
	}
	
	/*
	 * AÑO NO POSTERIOR
	 * 
	 * VALIDA QUE EL AÑO NO SEA POSTERIOR O IGUAL A LA ACTUAL
	 * 
	 * @access	public
	 * @return	bool
	 * 
	 */
	function anio_no_posterior($str)
	{
		if(strtotime($str)>strtotime(date('Y')))
		{
			$this->CI->form_validation->set_message('anio_no_posterior', 'El campo %s no puede ser posterior al a&ntilde;o actual.');
			return FALSE;
		}
		return TRUE;
	}
	
	/*
	 * ALFANUMERICO GUION MEDIO, DIAGONAL Y DIAGONAL INVERTIDA 
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	function alpha_dash_diagonales($str)
	{		
		$this->CI->form_validation->set_message('alpha_dash_diagonales', "El campo %s debe contener una combinaci&oacute;n de caracteres alfanum&eacute;ricos, guiones (-) y diagonales (/ \).");
		return (bool) preg_match("/(^[a-zA-Z0-9\xF1\xD1\-\/\\\\ñÑ]+$)|^$/", $str);
	}
	
	/*
	 * ALFANUMERICO GUION MEDIO, GUION BAJO, DIAGONAL  
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	function alpha_dashes_diagonal($str)
	{		
		$this->CI->form_validation->set_message('alpha_dashes_diagonal', "El campo %s debe contener una combinaci&oacute;n de caracteres alfanum&eacute;ricos, guiones (-), guiones bajos (_) y diagonal (/).");
		return (bool) preg_match("/(^[a-zA-Z0-9-_\xF1\xD1\/ñÑ]+$)|^$/", $str);
	}

	
	
	
	/*
	 * ALFANUMERICO ESPACIO, PUNTO
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	function texto_simple($str)
	{		
		$this->CI->form_validation->set_message('texto_simple', "El campo %s debe contener una combinaci&oacute;n de caracteres alfanum&eacute;ricos, espacios, comas (,) y puntos (.), guión medio (-).");
		return (bool) preg_match("/(^[a-zA-Z0-9\xF1\xD1\s.,-ñÑ]+$)|^$/", $str);
	}
	
	/*
	 * ALFANUMERICO Y PUNTO
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	
	function alphanumeric_dot($str)
	{
		$this->CI->form_validation->set_message('alphanumeric_dot', "El campo %s solo puede contener caracteres alfanum&eacute;ricos y puntos.");
		return (bool) preg_match("/^([a-z0-9.])+$/i", $str);
	}
	
	
	/*
	 * ALFANUMERICO ESPACIO, PUNTO, COMA, GUIONES, PUNTO Y COMA, DOS PUNTOS
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	function alphanumeric_space($str)
	{		
		$this->CI->form_validation->set_message('alphanumeric_space', "El campo %s debe contener una combinaci&oacute;n de caracteres alfanum&eacute;ricos y espacios.");
		return (bool) preg_match("/(^[a-zA-Z0-9\xF1\xD1\sñÑ]+$)|^$/", $str);
	}
	
	
	/*
	 * ALFANUMERICO ESPACIO, PUNTO, COMA, GUIONES, PUNTO Y COMA, DOS PUNTOS
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	function texto($str)
	{		
		$this->CI->form_validation->set_message('texto', "El campo %s debe contener una combinaci&oacute;n de caracteres alfanum&eacute;ricos, espacios y (. , _ -).");
		return (bool) preg_match("/(^[a-zA-Z0-9\xF1\xD1\s.,-_ñÑ]+$)|^$/", $str);
	}
	

	function mayor_que($str,$parm)
	{
		if($str<=$parm)
		{
			$this->CI->form_validation->set_message('mayor_que', "El campo %s debe contener un valor mayor que {$parm}.");
			return FALSE;
		}
		return TRUE;
	}
	
	function cero_solo_si($str,$parms)
	{
		list($campo, $valor) = explode(",", $parms, 2);
		if($_POST[$campo]==$valor && $str==0)
		{
			return TRUE;
		}	
		elseif(($_POST[$campo]==$valor && $str!=0)||($_POST[$campo]!=$valor && $str==0))
		{
			$desc_campo=str_replace('_',' ',$campo);
			$this->CI->form_validation->set_message('cero_solo_si', "El campo %s debe ser cero solo si $desc_campo es $valor.");
			return FALSE;
		}
		return TRUE;
	}
	
	/*
	 * A B C
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	function abc($str)
	{		
		$this->CI->form_validation->set_message('abc', "El campo %s debe contener uno de los siguientes valores A,B,C,a,b,c");
		return (bool) preg_match("/(^[abcABC]$)|^$/", $str);
	}

	/*
	 * A C
	 *  
	 * @access	public
	 * @return	bool
	 * 
	 */
	function ac($str)
	{		
		$this->CI->form_validation->set_message('ac', "El campo %s debe contener uno de los siguientes valores A,C,a,c");
		return (bool) preg_match("/(^[acAC]$)|^$/", $str);
	}
	
	function fecha_entrega($fecha_entrega)
	{
		//VALIDA QUE LA FECHA DE ENTREGA SEA MAYOR A 3 DIAS HABILES A LA FECHA DE LA ORDEN DE COMPRA
		$hoy = date('Y-m-d');
		$fecha_valida = date('Y-m-d',strtotime('+3 day',strtotime($hoy)));
		if($fecha_entrega < $fecha_valida)
		{
			$this->CI->form_validation->set_message('fecha_entrega', "El campo %s debe de ser por lo menos 3 d&iacute;as h&aacute;biles despu&eacute;s de la fecha de la orden de compra.");
			return FALSE;
		}
		return TRUE;
	}
	
	function fecha_instalacion($fecha_instalacion)
	{
		//VALIDA QUE LA FECHA DE INSTALACION SEA MAYOR A 3 DIAS HABILES DE LA FECHA DE LA ORDEN DE COMPRA Y MENOR A 6 MESES DE LA MISMA
		$hoy = date('Y-m-d');
		$fecha_valida = date('Y-m-d',strtotime('+3 day',strtotime($hoy)));
		$fecha_maxima = date('Y-m-d',strtotime('+6 month',strtotime($hoy)));
		if(($fecha_instalacion < $fecha_valida) || ($fecha_instalacion > $fecha_maxima))
		{
			$this->CI->form_validation->set_message('fecha_instalacion', "El campo %s debe de ser por lo menos 3 d&iacute;as h&aacute;biles despu&eacute;s de la fecha de la orden de compra y menor a 6 meses de la misma.");
			return FALSE;
		}
		return TRUE;
	}

	/*
     * date_equal_or_less_than
     * Revisa que la diferencia en meses entre dos fechas sea menor o igual al último parametro
     * EJEMPLO date_diff_less_than[fecha_inicial, fecha_final, 1]
     */
	function date_equal_or_less_than($str, $parms)
	{
		$campos=explode(',',$parms,3);
		$meses=array_pop($campos);

		$fechainicial = new DateTime($_POST[$campos[0]]);
		$fechafinal = new DateTime($_POST[$campos[1]]);

		$diferencia = $fechainicial->diff($fechafinal);
		$meses_transcurridos = ($diferencia->m + ($diferencia->y * 12));

		if($meses_transcurridos>=$meses)
		{
			$this->CI->form_validation->set_message('date_equal_or_less_than', 'El rango de fechas para exportar no puede ser mayor a '.$meses.' mes(es).');
			return FALSE;
		}

		return TRUE;
	}

	/*
	 * VALIDA QUE EL RFC TENGA LA ESTRUCTURA ESTABLECIDA CON HOMOCLAVE
	*/
	public function validarRFC($rfc)
	{
		$this->CI->form_validation->set_message('validarRFC', "El campo %s no cuenta con la estructura establecida.");
		if(preg_match( "/^[A-Z&Ñ]{3,4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([ -]?)([A-Z0-9]{3,4})$/", $rfc))
			return TRUE;
		else
			return FALSE;
	}
}