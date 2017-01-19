<?php
/*
 * BASE USUARIOS, GRUPOS, FUNCIONES, PERMISOS
 * México 2010 Blackcore - reZorte
 * remery@blackcore.com.mx
 * 
 */
class Base extends Model {

	var $meses=array(
		'01'=>'ENERO',
		'02'=>'FEBRERO',
		'03'=>'MARZO',
		'04'=>'ABRIL',
		'05'=>'MAYO',
		'06'=>'JUNIO',
		'07'=>'JULIO',
		'08'=>'AGOSTO',
		'09'=>'SEPTIEMBRE',
		'10'=>'OCTUBRE',
		'11'=>'NOVIEMBRE',
		'12'=>'DICIEMBRE',
	);
	
	var $tipos_log=array(
		1=>'Comparables',
		2=>'Avaluos',
		3=>'Usuarios',
		4=>'Cuentas',
		5=>'Webservices',
		6=>'Duplicar avalúos',
		7=>'Impresiones'
	);
	
	var $usuario_select=array(
		"CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno,' ',usuarios.apellido_materno) as nombre_completo",
		'grupos.nombre as grupo',
		'cuentas.nombre as despacho',
	);
	
	var $usuario_join=
		array(
			array('cuentas', 'usuarios.cuentas_id = cuentas.id','LEFT'),
			array('grupos', 'usuarios.grupos_id = grupos.id','')
		);
	
	var $public=array(
		'main/login',
		'main/logout',
		'main/sesion_ajax',
		'main/recuperar_contrasena',
		//'avaluos/fotos_agregar'
	);	
	
    function Base()
    {
        parent::Model();
    }    
    
    function tiene_permiso($funcion,$grupos_id=null)
    {
    	if(empty($grupos_id))
    		$grupos_id = $this->session->userdata('grupos_id');
		$this->db->select('funciones.id');
		$this->db->where('funciones.funcion',$funcion);
		$this->db->join('permisos', 'permisos.funciones_id = funciones.id');
		$this->db->where('permisos.grupos_id',$grupos_id);
		$q = $this->db->get('funciones')->row();
		if(!empty($q))
			return TRUE;
		return FALSE;	
    }
    
	function verifica($funcion=NULL,$ajax=FALSE)
	{		
		// PARA EL MANEJO DE LA SESION CON SWFUPLOAD
		if(isset($_POST['cookie_session']))
		{
			$_COOKIE[$this->session->sess_cookie_name] = $_POST['cookie_session'];
			$this->session->sess_read();
		}
		
		if(in_array($this->uri->segment(1).'/'.$this->uri->segment(2),$this->public))
			return TRUE;
		
		if(($funcion===NULL && $this->session->userdata('logged')))
			return TRUE;
			
		if(!$this->session->userdata('logged'))
		{
			redirect('/main/login');		
		}
		else
		{
			$grupos_id = $this->session->userdata('grupos_id');
			$this->db->select('funciones.id');
			$this->db->where('funciones.funcion',$funcion);
			$this->db->join('permisos', 'permisos.funciones_id = funciones.id');
			$this->db->where('permisos.grupos_id',$grupos_id);
			$q = $this->db->get('funciones')->row();
			if(empty($q))
			{
				$this->session->set_flashdata('error', 'Su usuario no tiene los permisos necesarios para acceder a dicha funci&oacute;n.');
				//redirect('/main/index');
				$this->load->library('user_agent');
				redirect($this->agent->referrer());
			}
		}
		return TRUE;
	}
    
	function login($data)
    {    	
		$valido = FALSE;
		$select=array('usuarios.id', 'usuarios.grupos_id', 'usuarios.cuentas_id as cuentas_id', 'usuarios.usuario', 'usuarios.email', 'usuarios.activo', 'cuentas.activo as cuenta_activo','cuentas.suspendido_pago as cuenta_suspendido');
    	$this->db->select($select);
    	$this->db->select("CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno) as nombre",FALSE);
		$this->db->where('usuarios.usuario', $data['usuario']);
		$this->db->where('usuarios.contrasena', $data['contrasena']);
		$this->db->join('cuentas','cuentas.id=usuarios.cuentas_id','LEFT');
		//$this->db->where('activo', 1);
		$this->db->where('usuarios.eliminado', 0);				
		$q = $this->db->get('usuarios')->row();
		if(empty($q))
			return 'ERROR';
		if((!$q->activo || !$q->cuenta_activo) && $q->grupos_id!=1)
			return 'DESACTIVADO';
		if($q->cuenta_suspendido)
			return 'SUSPENDIDO';
		if($this->config->item('mantenimiento') && $q->grupos_id!=1)
			return 'MANTENIMIENTO';	
		else
		{
			$this->db->like('user_data',$q->usuario);
			$this->db->delete('sesiones');
			
			$q->logged=true;
			$q->mostrar_detalles=$this->config->item('mostrar_detalles');
			$q->busqueda_avanzada=$this->config->item('busqueda_avanzada');
			$q->admin=$this->tiene_permiso('admin',$q->grupos_id);
			$q->mostrar_menu=1;
			$this->session->set_userdata($q);
			return TRUE;
		}
    }
    
    function set_user_data($usuarios_id)
    {
    	$select=array('usuarios.id', 'usuarios.grupos_id', 'usuarios.cuentas_id as cuentas_id', 'usuarios.usuario', 'usuarios.email', 'usuarios.activo', 'cuentas.activo as cuenta_activo','cuentas.suspendido_pago as cuenta_suspendido');
    	$this->db->select($select);
    	$this->db->select("CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno) as nombre",FALSE);
		$this->db->where('usuarios.id', $usuarios_id);
		$this->db->join('cuentas','cuentas.id=usuarios.cuentas_id','LEFT');
		//$this->db->where('activo', 1);
		$this->db->where('usuarios.eliminado', 0);				
		$q = $this->db->get('usuarios')->row();
		$q->logged=true;
		$q->mostrar_detalles=$this->config->item('mostrar_detalles');
		$q->busqueda_avanzada=$this->config->item('busqueda_avanzada');
		$q->admin=$this->tiene_permiso('admin',$q->grupos_id);
		$q->mostrar_menu=1;
		$this->session->set_userdata($q);
    	return TRUE;
    }
    
    function unico($table,$field,$str,$id)
    {
    	$result=TRUE;
    	$this->db->from($table);
    	$this->db->where($field,$str);
    	if($id)
    		$this->db->where_not_in('id',$id);
    	$q=$this->db->count_all_results();
    	if($q!=0)
    		$result=FALSE;
    	return $result;
    }
    
    function guarda($table,$data)
    {
    	$data=bc_null_empty($data);
    	if(!empty($data['id']))
    	{    	
    		if($this->db->field_exists('modified',$table))
    			$data['modified']=date('Y-m-d H:i:s');
    		if($this->db->field_exists('modified_by',$table))
    			$data['modified_by']=$this->session->userdata('id');
   			$this->db->where(array('id'=>$data['id']));
   			$this->db->update($table, $data);
   			$id=$data['id'];
   		}
   		else
   		{   
   			if($this->db->field_exists('created',$table))
   				$data['created']=date('Y-m-d H:i:s');
   			if($this->db->field_exists('created_by',$table))
    			$data['created_by']=$this->session->userdata('id');
    		if($this->db->field_exists('eliminado',$table))
    			$data['eliminado']=0;
    		if($this->db->field_exists('activo',$table))
    			$data['activo']=1;		
   			$this->db->insert($table, $data);
   			$id=$this->db->insert_id();
   		}  
   		//debug($this->db->last_query());
   		//exit;
   		return $id;
    }
    
    function toggle($table,$campo,$id,$val)
    {
    	$data[$campo]=!$val;
    	$this->db->where('id',$id);
   		$this->db->update($table, $data);
   		//debug($this->db->last_query());
    }
        
    function read($table,$id,$array=FALSE)
    {
    	if($table=='usuarios')
    	{
    		$fields=$this->db->list_fields($table);
    		foreach($fields as &$v)
    		{
    			$v='usuarios.'.$v;
    		}
    		$this->db->select($fields);
    		$this->db->select($this->usuario_select);
	    	foreach($this->usuario_join as $j)
	    	{
	    		$this->db->join($j[0],$j[1],$j[2]?$j[2]:NULL);
	    	}
	    }	
    	$this->db->where($table.'.id',$id);
    	if($array)
    		$r=$this->db->get($table)->row_array();
    	else
    		$r=$this->db->get($table)->row();	
    	return $r;
    }
    
    function lista($table,$id,$campo,$filtros=TRUE,$order_by=NULL,$order='DESC')
    {
    	$this->db->select(array($id,$campo));
    	if($filtros)
    	{
	    	if($this->db->field_exists('activo',$table))
	    		$this->db->where('activo',1);
	    	if($this->db->field_exists('eliminado',$table))
	    		$this->db->where('eliminado',0);
    	}	
    	if($order_by)
    		$this->db->order_by($order_by,$order);
    	
    	$q=$this->db->get($table)->result_object();

    	$lista=array();
    	foreach($q as $v)
    	{
    		$lista[$v->{$id}]=$v->{$campo};
    	}
    	return $lista;
    }
        
    function grupos($conditions,$limit,$offset)
    {
    	if(isset($conditions['nombre']))
    		$this->db->like('nombre',$conditions['nombre']);
    		
    	return $this->db->get('grupos',$limit,$offset);
    }
    
    function grupos_count($conditions)
    {
    	if(isset($conditions['nombre']))
    		$this->db->like('nombre',$conditions['nombre']);
    	return $this->db->count_all_results('grupos');	
    } 
        
    var $usuarios_like=array(
    	'nombre_completo', 	
    	'usuario'
    );
    var $usuarios_where=array(
    	'grupos_id',
    	'cuentas_id',
    );
    
	function usuarios($conditions,$limit,$offset)
    {
    	foreach($this->usuarios_like as $c)
    	{
			if(isset($conditions[$c]))
    			$this->db->like($c,$conditions[$c]);
    	}
    	
    	foreach($this->usuarios_where as $c)
    	{
    		if(isset($conditions[$c]))
    			$this->db->where($c,$conditions[$c]);
    	}
    	
    	return $this->db->get('usuarios_view',$limit,$offset);
    }
    
    function usuarios_count($conditions)
    {
    	foreach($this->usuarios_like as $c)
    	{
    		if(isset($conditions[$c]))
    			$this->db->like($c,$conditions[$c]);
    	}
    	
    	foreach($this->usuarios_where as $c)
    	{
    		if(isset($conditions[$c]))
    			$this->db->where($c,$conditions[$c]);
    	}
    	return $this->db->count_all_results('usuarios_view');	
    }
    
    function usuario_contrasena($id)
	{
		$possible = "0123456789abcdfghjkmnpqrstvwxyz";
		$password = ""; 
		$i = 0;
		while ($i < $this->config->item('pass_length')) { 
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);     
			if (!strstr($password, $char)) { 
				$password .= $char;
				$i++;
			}
		}
		$data['contrasena']=MD5($password);
		$this->db->where('id',$id);
		$this->db->update('usuarios', $data);
		return $password;
	}		
	
	function funciones()
	{
		$funciones=$this->db->get('funciones');
		return $funciones;
	}
	
	function permisos($grupos_id){
		$permisos = array();
   		$this->db->select('permisos.id, permisos.funciones_id, funciones.funcion')
   				->from('permisos')
   				->join('funciones', 'permisos.funciones_id = funciones.id')
   				->where('grupos_id',$grupos_id);
    	$query = $this->db->get();
    	foreach($query->result_array() as $permiso){
    		$permisos[$permiso['funciones_id']] = $permiso['funcion']; 
    	}
    	return $permisos;
   	}
   	
	function permisos_set($grupos_id,$permisos){
   		$this->db->delete('permisos', array('grupos_id' => $grupos_id));
   		foreach($permisos as $permiso){
   			$data['grupos_id'] = $grupos_id;
   			$data['funciones_id'] = $permiso;
   			$this->db->insert('permisos', $data);  
   		}
   	}
   	
   	function _condiciones_rango_fecha($conditions)
   	{
   	/*
    	 * RANGO DE FECHAS FILTRO ENVIAR ARREGLO COMO SIGUE:
    	 * $conditions['rango_fechas']=array(
    	 * 	'campo_a_filtrar'=>array(
    	 * 		'fecha_inicial'=>'valor_fecha_inicial',
    	 * 		'fecha_final'=>'valor_fecha_final'
    	 * 	 ),
    	 * );
    	 */
    	
    	if(isset($conditions['rango_fechas']))
    	{
    		foreach($conditions['rango_fechas'] as $campo=>$parms)
    		{
    			if(!empty($parms['fecha_inicial']) && empty($parms['fecha_final']))
    				$this->db->where("DATE_FORMAT({$campo},'%Y-%m-%d') >= '{$parms['fecha_inicial']}'");
    			if(empty($parms['fecha_inicial']) && !empty($parms['fecha_final']))
    				$this->db->where("DATE_FORMAT({$campo},'%Y-%m-%d') <= '{$parms['fecha_final']}'");
    			if(!empty($parms['fecha_inicial']) && !empty($parms['fecha_final']))
    			{
    				$this->db->where("DATE_FORMAT({$campo},'%Y-%m-%d') >= '{$parms['fecha_inicial']}'");
    				$this->db->where("DATE_FORMAT({$campo},'%Y-%m-%d') <= '{$parms['fecha_final']}'");
    			}	
    		}
    		unset($conditions['rango_fechas']);
    	}
    	return $conditions;
   	}
   	
   	
	function find($conditions,$limit,$offset)
    {    	
    	$conditions=$this->_condiciones_rango_fecha($conditions);
    	
    	foreach($this->like as $c)
    	{
    		if(isset($conditions[$c]))
    		{
    			$this->db->like($c,$conditions[$c]);
    			unset($conditions[$c]);
    		}	
    	}
    	
    	foreach($conditions as $k=>$v)
    	{
    		if($k=='id')
    			$this->db->where_in('id',explode(',',$v));
    		else
    			$this->db->where($k,$v);
    	}
    	
    	if($this->db->field_exists('eliminado',$this->table))
    		$this->db->where('eliminado',0);    	
    		
    	$q = $this->db->get($this->table,$limit,$offset);
    	return $q;
    }
    
    function count($conditions)
    {
    	if(isset($conditions['campo_id']) && isset($conditions['orden_id']))
	    	if($conditions['campo_id'] || $conditions['orden_id'])
	    	{
	    		unset($conditions['campo_id']);
				unset($conditions['orden_id']);
	    	}
    	
    	$conditions=$this->_condiciones_rango_fecha($conditions);
    	
    	foreach($this->like as $c)
    	{
    		if(isset($conditions[$c]))
    		{
    			$this->db->like($c,$conditions[$c]);
    			unset($conditions[$c]);
    		}	
    	}
    	
    	foreach($conditions as $k=>$v)
    	{
    		if($k=='id')
    			$this->db->where_in('id',explode(',',$v));
    		else
    			$this->db->where($k,$v);
    	}
    	
    	if($this->db->field_exists('eliminado',$this->table))
    		$this->db->where('eliminado',0);
    		
    	return $this->db->count_all_results($this->table);	
    }
    
    function catalogos($conditions,$limit,$offset)
    {
    	if(isset($conditions['nombre']))
    		$this->db->like('nombre',$conditions['nombre']);
    	if(isset($conditions['descripcion']))
    		$this->db->like('descripcion',$conditions['descripcion']);	
    		
    	return $this->db->get('catalogos',$limit,$offset);
    }
    
    function catalogos_count($conditions)
    {
    	if(isset($conditions['nombre']))
    		$this->db->like('nombre',$conditions['nombre']);
    	if(isset($conditions['descripcion']))
    		$this->db->like('descripcion',$conditions['descripcion']);
    		
    	return $this->db->count_all_results('catalogos');	
    } 
    
    function elementos($conditions,$limit,$offset)
    {
    	if(isset($conditions['valor']))
    		$this->db->like('valor',$conditions['valor']);
    	if(isset($conditions['catalogos_id']))
    		$this->db->where('catalogos_id',$conditions['catalogos_id']);	
    		
    	return $this->db->get('elementos_view',$limit,$offset);
    }
    
    function elementos_count($conditions)
    {
    	if(isset($conditions['valor']))
    		$this->db->like('valor',$conditions['valor']);
    	if(isset($conditions['catalogos_id']))
    		$this->db->where('catalogos_id',$conditions['catalogos_id']);	
    		
    	return $this->db->count_all_results('elementos_view');	
    } 
    
    
    function guarda_imagen($path,$destino,$name,$size=FALSE,$folder=NULL)
    {	
    	$destino=explode('/',$destino);
    	
    	if(empty($folder))
    		$folder=FCPATH;
    		
    	foreach($destino as $d)
    	{
	    	$folder.=$d.'/';
	    	if(!file_exists($folder))
	    	{
	    		
				mkdir($folder);
				chmod($folder, 0775);
			}
    	}
    	
    	
    	list($width, $height) = getimagesize($path);
    	
    	if($size)
    	{
	    	$imgratio=$width/$height;		
			if($imgratio>1)
			{
				$width = $size;
				$height = $size/$imgratio;
			}
			else
			{
				$height = $size;
				$width = $size*$imgratio;
			}
    	}	

		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $path;
		$config['new_image'] = $folder.$name.'.jpg';
		//$config['quality']='100%';
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$this->image_lib->initialize($config);
	   	if(!$this->image_lib->resize())
        {
           	debug($this->image_lib->display_errors());
        }
		$this->image_lib->clear();
		$guardo=file_exists($config['new_image']);
		unset($config);		
		if(!$guardo)
			return FALSE;
		else
			return TRUE;
    }
    
    function recuperar_contrasena($data)
    {
    	$this->db->select('id');
    	$this->db->where('usuario',$data['usuario']);
    	$this->db->where('email',$data['email']);
    	$q= $this->db->get('usuarios')->row();
    	if($q)
    		return $q->id;
    	else
    		return false;	
    }
    
    function logs_conditions($conditions)
    {
    	if(!empty($conditions['fecha_inicial']) || !empty($conditions['fecha_final']))
    	{
    		$conditions['rango_fechas']=array(
    			'created'=>array(
    				'fecha_inicial'=>@$conditions['fecha_inicial'],
    				'fecha_final'=>@$conditions['fecha_final'],
    			)
    		);
    	}
    	unset($conditions['fecha_inicial']);
    	unset($conditions['fecha_final']);
    	$this->db->order_by('id','DESC');
    	return $conditions;
    }
    
    function logs_find($conditions,$limit,$offset)
    {
    	$conditions=$this->logs_conditions($conditions);
    	return $this->find($conditions,$limit,$offset);
    }
    
	function logs_count($conditions)
    {
    	$conditions=$this->logs_conditions($conditions);
    	return $this->count($conditions);
    }
 
  	function cuentas($conditions,$limit,$offset)
    {
    	if(isset($conditions['nombre']))
    		$this->db->like('nombre',$conditions['nombre']);
    		
    	return $this->db->get('cuentas',$limit,$offset);
    }
    
	function _cuentas_condicion($conditions)
	{
		if(isset($conditions['rol']))
	    	{
	    		if($conditions['rol']==1)
	    		{
	    			$conditions['externo']=1;
	    		}
	    		elseif($conditions['rol'])
	    		{
	    			$conditions['asociado']=1;	
	    		}
	    		unset($conditions['rol']);
	    	}
	    return $conditions;
		
	}

	function cuentas_count($conditions)
    {
    	$conditions=$this->_cuentas_condicion($conditions);
    	return $this->count($conditions);
    } 
 
 	function find_cuentas($conditions,$limit,$offset)
    {
    	$conditions=$this->_cuentas_condicion($conditions);
    	return $this->find($conditions,$limit,$offset);
    }
    
    function get_datos_fiscales($cuentas_id)
    {
    	$this->db->where('id',$cuentas_id);
    	$r=$this->db->get('cuentas')->row_array();
    	return $r;
    }
    
 	function cus($m2_construccion,$m2_terreno)
    {
    	$res=NULL;
    	if($m2_terreno>0)
    		$res=round($m2_construccion/$m2_terreno,2);
    	return $res; 
    }
    
 	function factor_edad($edad,$vida_util)
    {
    	if(($vida_util=='' && $edad=='') || ($vida_util==0 && $edad==0))
    		return 1;    	
    	if($vida_util==0)
    		return 0;
    	$res=NULL;	
    	if($vida_util>0)
    	{		    	    	    		
    		$factor_edad=((0.1*$vida_util)+(0.9*($vida_util-$edad)))/$vida_util;    
    		$res=$this->_limite_factor($factor_edad);
    	}		
    	return $res;
    }
    
    function factor_edad_anep($edad_sujeto,$edad_comparable)
    {
    	$factor_edad = 1-(($edad_sujeto-$edad_comparable)/100);
    	$res = $this->_limite_factor($factor_edad);
    	return $res;
    }
    
    
    function factor_superficie($lote_tipo,$m2_terreno)
    {
    	$res=NULL;
    	if($lote_tipo>0)
    	{
	    	$rlt=$m2_terreno/$lote_tipo;
	    	if($rlt<2)
	    		$factor_superficie=1;
	    	else
	    		$factor_superficie=(100-((ceil($rlt)-2)*2))/100;	    		
	    	$res=$this->_limite_factor($factor_superficie,NULL,0.62);	
    	}		
    	return $res; 
    }
    
	function _limite_factor($factor, $max=FALSE, $min=FALSE)
    {
    	if(!$max)
    		$max=$this->config->item('factor_max');
    	if(!$min)
    		$min=$this->config->item('factor_min');	
    	if($factor>$max)
    		return $max;
    	elseif($factor<$min)
    		return $min;
    	else
    		return round($factor,2);		
    }
    
    function get_controladores($todos=TRUE,$opinion=FALSE,$infonavit=FALSE,$tesoreria=FALSE,$fovissste=FALSE,$shf=FALSE,$solo_activo=TRUE)
    {
    	
    	$this->db->select('id, nombre_completo');
    	$this->db->order_by('nombre_completo', 'asc');
    	$this->db->where('eliminado',0);
    	if($solo_activo)
    		$this->db->where('activo',1);
    	if(!$todos)
    	{
	    	if($opinion)
	    		$this->db->where('controlador_opinion',1);
	    	if($infonavit)
	    		$this->db->where('controlador_infonavit',1);	
	    	if($tesoreria)
	    		$this->db->where('responsable_tecnico',1);
	    	if($fovissste)
	    		$this->db->where('controlador_fovissste',1);
	    	if($shf)
	    		$this->db->where('controlador_shf',1);	
    	}	
    	else
    	{
    		$this->db->where('controlador_opinion', 1);
			$this->db->or_where('controlador_infonavit', 1);
			$this->db->or_where('responsable_tecnico', 1);
			$this->db->or_where('controlador_fovissste', 1);
			$this->db->or_where('controlador_shf', 1); 
    	}
    	$q=$this->db->get('usuarios_view')->result_object();
    	$res=array();
    	foreach($q as $r)
    	{
    		$res[$r->id]=$r->nombre_completo;	
    	}
    	return $res;
    }
    
	function get_valuadores($todos=TRUE,$opinion=FALSE,$infonavit=FALSE,$tesoreria=FALSE,$fovissste=FALSE,$shf=FALSE,$solo_activo=TRUE)
    {
    	$this->db->select('id, nombre_completo');
    	$this->db->order_by('nombre_completo', 'asc');
    	$this->db->where('eliminado',0);
    	if($solo_activo)
    		$this->db->where('activo',1);
    	if(!$todos)
    	{
	    	if($opinion)
	    		$this->db->where('valuador_opinion',1);
	    	if($infonavit)
	    		$this->db->where('valuador_infonavit',1);	
	    	if($tesoreria)
	    		$this->db->where('valuador_tesoreria',1);
	    	if($fovissste)
	    		$this->db->where('valuador_fovissste',1);
	    	if($shf)
	    		$this->db->where('valuador_shf',1);	
    	}
    	else
    	{
    		$this->db->where('valuador_opinion', 1);
			$this->db->or_where('valuador_infonavit', 1);
			$this->db->or_where('valuador_tesoreria', 1);
			$this->db->or_where('valuador_fovissste', 1);
			$this->db->or_where('valuador_shf', 1); 
    	}
    			
    	$q=$this->db->get('usuarios_view')->result_object();
    	
    	$res=array();
    	foreach($q as $r)
    	{
    		$res[$r->id]=$r->nombre_completo;	
    	}
    	return $res;
    }
    
    function get_responsables($todos=TRUE,$solo_activo=TRUE)
    {
    	if(!$todos)
    	{
	    	$asignar_responsables=$this->base->tiene_permiso('asignar_responsables');
	    	$asignar_responsables_cuenta=$this->base->tiene_permiso('asignar_responsables_cuenta');
	    	if(!$asignar_responsables && !$asignar_responsables_cuenta)
	    		$this->db->where('id',$this->session->userdata('id'));
	    	elseif($asignar_responsables_cuenta && !$asignar_responsables)
	    		$this->db->where('cuentas_id',$this->session->userdata('cuentas_id'));
    	}		
    	$this->db->select('id, nombre_completo');
    	$this->db->order_by('nombre_completo', 'asc');
    	$this->db->where('eliminado',0);
    	if($solo_activo)
    		$this->db->where('activo',1);
    	$this->db->where('responsable',1);
    	$q=$this->db->get('usuarios_view')->result_object();
    	$res=array();
    	foreach($q as $r)
    	{
    		$res[$r->id]=$r->nombre_completo;	
    	}
    	return $res;	
    }
    
	function get_promotores($todos=TRUE, $solo_activo=TRUE)
    {
    	$res=FALSE;
    	if($this->base->tiene_permiso('asignar_promotor') || $todos)
    	{	
	    	$this->db->select('id, nombre_completo');
	    	$this->db->order_by('nombre_completo', 'asc');
	    	$this->db->where('eliminado',0);
	    	if($solo_activo)
	    		$this->db->where('activo',1);
	    	$this->db->where('promotor',1);
	    	$q=$this->db->get('usuarios_view')->result_object();
	    	$res=array();
	    	foreach($q as $r)
	    	{
	    		$res[$r->id]=$r->nombre_completo;	
	    	}
    	}	
    	return $res;	
    }
    
	function get_representantes_legales()
    {
    	$this->db->select('id, nombre_completo');
    	$this->db->order_by('nombre_completo', 'asc');
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$this->db->where('representante_legal',1);
    	$q=$this->db->get('usuarios_view')->result_object();
    	$res=array();
    	foreach($q as $r)
    	{
    		$res[$r->id]=$r->nombre_completo;	
    	}    	
    	return $res;	
    }
    
	function get_responsables_cuentas()
    {
    	$this->db->select('id, nombre_completo');
    	$this->db->order_by('nombre_completo', 'asc');
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$this->db->where('responsable_cuenta',1);
    	$q=$this->db->get('usuarios_view')->result_object();
    	$res=array();
    	foreach($q as $r)
    	{
    		$res[$r->id]=$r->nombre_completo;	
    	}
    	return $res;	
    }
    
	function get_cuentas_externos($solo_activo=TRUE)
    {
    	$this->db->select('id, nombre');
    	$this->db->order_by('nombre', 'asc');
    	$this->db->where('eliminado',0);
    	$this->db->where('externo',1);
    	if($solo_activo)
    		$this->db->where('activo',1);
    	$this->db->or_where('id',$this->session->userdata('cuentas_id'));
    	$q=$this->db->get('cuentas')->result_object();
    	$res=array();
    	foreach($q as $r)
    	{
    		$res[$r->id]=$r->nombre;	
    	}
    	return $res;	
    }
    
	function get_cuentas_asociados($solo_activo=TRUE)
    {
    	$this->db->select('id, nombre');
    	$this->db->order_by('nombre', 'asc');
    	$this->db->where('eliminado',0);
    	$this->db->where('asociado',1);
    	$this->db->or_where('id',$this->session->userdata('cuentas_id'));
    	if($solo_activo)
    		$this->db->where('activo',1);
    	$q=$this->db->get('cuentas')->result_object();
    	$res=array();
    	foreach($q as $r)
    	{
    		$res[$r->id]=$r->nombre;	
    	}
    	return $res;	
    }
    
    var $imagenes_usuario=array(
    	'foto',
    	'firma',
    	'rubrica'
    );
    
    function usuarios_imagenes($usuarios_id,$data)
    {
    	foreach($this->imagenes_usuario as $i)
    	{
    		if($data['imagen_'.$i]['size']>0)
    			$this->guarda_imagen($data['imagen_'.$i]['tmp_name'],'files/usuarios/'.$usuarios_id,$i,FALSE);
    	}
    }
    
    function get_nombre_cuenta($cuentas_id)
    {
    	$this->db->select('nombre');
    	$this->db->where('id',$cuentas_id);
    	$nombre = $this->db->get('cuentas')->row()->nombre;
    	return $nombre;
    }

    function get_email_usuario()
    {
    	$this->db->select('email');
    	$this->db->where('id',$this->session->userdata('id'));
    	$q=$this->db->get('usuarios')->row()->email;
    	return $q;
    }
    
    function eliminar($tabla,$id)
    {
    	$data=array();
		$data['id']=$id;
    	$data['eliminado']=1;
    	/*
    	if($this->db->field_exists('deleted',$table))
    		$data['deleted']=date('Y-m-d H:i:s');
    	if($this->db->field_exists('deleted_by',$table))
    		$data['deleted_by']=$this->session->userdata('id');
    	*/
    	
    	$id = $this->guarda($tabla,$data);	
    	//$this->db->update($table,$data);
    	return $id; 
    }
    
    function guarda_archivo($origen,$destino,$nombre,$folder=NULL)
    {	     	    	
    	$destino=explode('/',$destino);
    	if(empty($folder))
    		$folder=FCPATH;
    		
    	foreach($destino as $d)
    	{
	    	$folder.=$d.'/';
	    	if(!file_exists($folder))
	    	{
				mkdir($folder);
				chmod($folder, 0775);				
			}
    	}    	
   		$sube=copy($origen,$folder.$nombre); 		    	
		
		return TRUE;
    }
    
    
    function get_usuarios_uid()
    {
    	//$base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ012345678901234567890123456789';
    	$base='0123456789';
	    $len = 3;
		$max=strlen($base)-1;
		$unique_id='';
		mt_srand((double)microtime()*1000000);
		while (strlen($unique_id)<$len)
			$unique_id.=$base{mt_rand(0,$max)};
		return $unique_id; 
    }
    
    function usuarios_uid($usuarios_id)
    {
    	$this->db->where('id',$usuarios_id);
    	$this->db->select('unique_id');
    	$unique_id=$this->db->get('usuarios')->row()->unique_id;
    	if(empty($unique_id))
    	{
    		$existe=1;	
	    	while($existe)
	    	{
	    		$unique_id=$this->get_usuarios_uid();
	    		// COMPRUEBA QUE NOE EXISTA EN OTRO USUARIO
	    		$this->db->where('unique_id',$unique_id);
    			$existe=$this->db->count_all_results('usuarios');
	    	}			  
			$this->db->where('id',$usuarios_id); 
			$this->db->update('usuarios',array('unique_id'=>$unique_id)); 
    	} 
		return $unique_id;
    }
    
    
    function get_cuenta_usuario($usuarios_id)
    {
    	$this->db->select('cuentas_id');
    	$this->db->where('id',$usuarios_id);
    	$cuentas_id = $this->db->get('usuarios')->row()->cuentas_id;
    	return $cuentas_id; 	
    }
    
    function navegador()
    {
    	$u_agent= $_SERVER['HTTP_USER_AGENT'];
    	if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    		return TRUE;		    
	    
	    return FALSE;
    }
 }
/* End of file avaluos.php */
/* Location: ./system/application/models/base.php */