<?php
/*
 * BASE USUARIOS, GRUPOS, FUNCIONES, PERMISOS
 * México 2011 Blackcore - reZorte
 * remery@blackcore.com.mx
 * 
 */

define('TMPPATH', '/var/tmp');

class Base extends CI_Model {

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
		1=>'Sesiones',
		2=>'Fotografias',
		3=>'Productos',
		4=>'Accesorios',
		5=>'Promociones',
		6=>'Cuentas',
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
		'calificaciones/califica',
		'calificaciones/set_calificacion',
		'calificaciones/get_calificacion',
		'calificaciones/comentarios',
		'calificaciones/get_calificacion_comentario',
		//'frontends/*'
	);	
	
	var $public_controllers=array(
		'frontends'
	);
	
    public function __construct()
	{
		parent::__construct();    
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
		
		//SI LA VISTA ES PUBLICA O SI ES CLIENTE FINAL QUE NO SE LOGUEA Y EL CONTROLLER ES PUBLICO
		$controller = $this->uri->segment(1);
		if(in_array(CONTROLLER,$this->public_controllers) && empty($controller))
		{
			$url = $this->uri->assoc_to_uri(array('frontends' => 'index'));
			redirect($url);
		}

		if(in_array($this->uri->segment(1).'/'.$this->uri->segment(2),$this->public) || (!$this->session->userdata('logged') && in_array($this->uri->segment(1),$this->public_controllers)))
			return TRUE;
		
		if(($funcion===NULL && $this->session->userdata('logged')))
			return TRUE;
			
		if(!$this->session->userdata('logged'))
		{
			//if(in_array($this->uri->segment(1),$this->public_controllers))
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
		$select=array('usuarios.id', 'usuarios.usuario', 'usuarios.grupos_id', 'usuarios.activo', 'usuarios.cuentas_id', 'cuentas.activo as cuenta_activo');
    	$this->db->select($select);
		$this->db->where('usuarios.usuario', $data['usuario']);
		$this->db->where('usuarios.contrasena', MD5($data['contrasena'].$this->config->item('encryption_key')));
		$this->db->join('cuentas','cuentas.id=usuarios.cuentas_id','left outer');
		$this->db->where('usuarios.eliminado', 0);				
		$q = $this->db->get('usuarios')->row();
		
		if(empty($q))
			return 'ERROR';
		elseif((!$q->activo || (!empty($q->cuentas_id) && $q->cuenta_activo!=1)) && $q->grupos_id!=1)
			return 'DESACTIVADO';
		elseif($this->config->item('mantenimiento') && $q->grupos_id!=1)
			return 'MANTENIMIENTO';	
		else
		{
			//COMENTO LAS SIGUIENTES 2 LINEAS PARA QUE PUEDAN INICIAR SESION CON EL MISMO USUARIO VARIOS VENDEDORES
			//$this->db->like('user_data',$q->usuario);
			//$this->db->delete('sesiones');			
			$q->mostrar_detalles=FALSE;
			$this->set_user_data($q->id);
			return TRUE;
		}
    }
    
    function set_user_data($usuarios_id)
    {
    	$select=array('usuarios.id', 'usuarios.grupos_id', 'usuarios.cuentas_id as cuentas_id', 'usuarios.usuario', 'usuarios.email', 'usuarios.activo', 'cuentas.activo as cuenta_activo','usuarios.cliente_externo');
    	$this->db->select($select);
    	$this->db->select("CONCAT(usuarios.nombre,' ',usuarios.apellido_paterno) as nombre",FALSE);
		$this->db->where('usuarios.id', $usuarios_id);
		$this->db->join('cuentas','cuentas.id=usuarios.cuentas_id','LEFT');
		$this->db->where('usuarios.eliminado', 0);				
		$q = $this->db->get('usuarios')->row();
		$q->logged=TRUE;
		$q->mostrar_detalles=$this->config->item('mostrar_detalles');
		$q->busqueda_avanzada=$this->config->item('busqueda_avanzada');
		$q->mostrar_menu=TRUE;
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
		return $this->db->update($table, $data);
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
    
    function lista($table,$id,$campo,$filtros=TRUE,$order_by=NULL,$order='DESC', $conditions=FALSE)
    {
    	$campo_aux=$campo;
    	if(is_array($campo))
    		$campo_aux=implode(', ',$campo);
    	 
    	$this->db->select("{$id}, {$campo_aux}", FALSE);
    	 
    	if($conditions) // CONDICIONES ADICIONALES
    		foreach($conditions as $k=>$v)
    		$this->db->where($k,$v);
    	 
    	if($filtros)
    	{
    		if($this->db->field_exists('activo',$table))
    			$this->db->where('activo',1);
    		if($this->db->field_exists('eliminado',$table))
    			$this->db->where('eliminado',0);
    	}
    	 
    	//if($this->db->field_exists('eliminado',$table))
    		//$this->db->where('eliminado',0);
    	 
    	if($order_by)
    		$this->db->order_by($order_by,$order);
    	else
    		$this->db->order_by(is_array($campo)?$campo[0]:$campo,'ASC');
    	 
    	$q=$this->db->get($table)->result_array();
    
    	$lista=array();
    	foreach($q as $v)
    	{
    		if(is_array($campo))
    		{
    			$desc='';
    			foreach($v as $k=>$c)
    			{
    				if($k!=$id)
    					$desc.=$v[$k].' ';
    			}
    			$lista[$v[$id]]=$desc;
    		}
    		else
    			$lista[$v[$id]]=$v[$campo];
    
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
        
    /*var $usuarios_like=array(
    	'nombre', 	
    	'usuario'
    );
    var $usuarios_where=array(
    	'grupos_id',
    	'cuentas_id',
    );*/
    
    function usuarios_pconditions($cond)
    {
    	/*foreach($this->usuarios_like as $c)
			if(isset($conditions[$c]))
    			$this->db->like($c,$conditions[$c]);
    	
    	foreach($this->usuarios_where as $c)
    		if(isset($conditions[$c]))
    			$this->db->where($c,$conditions[$c]);*/
    	if(!empty($cond['nombre']))
    		$this->db->like('nombre',$cond['nombre']);
    	 
    	if(!empty($cond['usuario']))
    		$this->db->like('usuario',$cond['usuario']);
    	
    	if(!empty($cond['grupos_id']))
    		$this->db->where('grupos_id',$cond['grupos_id']);
    	 
    	if(!empty($cond['cuentas_id']))
    		$this->db->where('cuentas_id',$cond['cuentas_id']);
    }
    
	function usuarios($cond,$limit,$offset)
    {
    	$this->usuarios_pconditions($cond);
    	$this->db->where('eliminado',0);
    	$this->db->from('usuarios');
    	$this->db->order_by('id','DESC');
    	/*$this->db->select(array(
    		'id',
    		'cuentas_id',
    		'grupos_id',
    		'usuario',
    		'nombre',
    		'apellido_paterno',
    		'apellido_materno',
    		'email',
    		'activo',
    	));*/
    	if ($limit)
    	$this->db->limit($limit,$offset);
    	$r=$this->db->get()->result();
    	return $r;
    }
   
    function usuarios_count($conditions)
    {
    	$this->usuarios_pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('usuarios');
      	$r=$this->db->count_all_results();
    	
    	return $r;
    	//$this->usuarios_pconditions($conditions);
    	//return $this->db->count_all_results('usuarios');	
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
		
		$data['contrasena']=MD5($password.$this->config->item('encryption_key'));
		$this->db->where('id',$id);
		$this->db->update('usuarios', $data);
		return $password;
	}		
	
	function funciones()
	{
		if($this->session->userdata('grupos_id')!=1)
   			$this->db->where('publico',1);
   		$this->db->order_by('categorias_id', 'ASC');	
		$funciones=$this->db->get('funciones')->result();
		$res=array();
		foreach($funciones as $f)
		{
			$categorias_id=$f->categorias_id;
			$res[$f->categorias_id][]=$f;	
		}
		return $res;
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
    		
    	return $this->db->get('elementos',$limit,$offset);
    }
    
    function elementos_count($conditions)
    {
    	if(isset($conditions['valor']))
    		$this->db->like('valor',$conditions['valor']);
    	if(isset($conditions['catalogos_id']))
    		$this->db->where('catalogos_id',$conditions['catalogos_id']);	
    		
    	return $this->db->count_all_results('elementos');	
    } 
    
    
	function guarda_imagen($path,$destino,$name,$size=FALSE,$folder=NULL,$extension='jpg')
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
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;
		$this->image_lib->initialize($config);
	   	
	   	if(!$res=$this->image_lib->resize())
           	debug($this->image_lib->display_errors());

	   	$this->image_lib->clear();
		$new_name = !empty($extension)? $folder.$name.'.'.$extension : $folder.$name.'.jpg';
    	if($res)
    	{
    		if($this->config->item('cloudfiles'))
    		{
    			$CI =& get_instance();
    			$CI->load->model('cloud_files');
    			$guardo = $CI->cloud_files->subir($path, $new_name);
    			if($guardo)
    			{
    				unset($config);
    				return $guardo;
    			} 
    			else
    			{
    				unset($config);
    				return FALSE;
    			}
    		} 
    		else
    			return copy($path, $new_name);
    		
    		unset($config);
    		return TRUE;
    	}
    	return FALSE;
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
		/*
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
	    */
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
    
    function guarda_archivo($origen,$destino,$nombre,$folder=NULL,$cloud=TRUE)
    {
    	if($this->config->item('cloudfiles') && $cloud)
    	{
    		$destino=explode('/',$destino);
    		if(empty($folder))
    			$folder=FCPATH;
    		foreach($destino as $d)
    			$folder.=$d.'/';
    		$CI =& get_instance();
    		$CI->load->model('cloud_files');
    		$guardo = $CI->cloud_files->subir($origen, $folder.$nombre);
    		@unlink($folder.$nombre);
    		return $guardo;
    	} 
    	else
    	{
    		// Si esta apagado cloudfiles crea todo el path de la imagen
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
    		return copy($origen,$folder.$nombre);
    	}	
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
    
	function crear_carpeta($carpeta)
    {
        $carpeta=explode('/',$carpeta);
    	$folder='';
    	foreach($carpeta as $d)
    	{
	    	$folder.=$d.'/';
	    	if(!file_exists($folder))
	    	{	    		
				mkdir($folder);
				chmod($folder, 0775);
			}
    	}
    }
    
    public function value_get($table,$id,$field)
    {
    	// GETTER GENERAL
    	$this->db->select($field);
    	$this->db->where('id',$id);
    	$r=$this->db->get($table)->row();
    	if(empty($r))
    		return FALSE;
    	 
    	if(is_array($field))
    		return $r;
    	return $r->{$field};
    }
    
    public function usuarios_cuentas_ids_get()
    {
    	// REGRESA LOS IDS DE LA CUENTAS QUE TIENE ASIGNADO UN USUARIOS ADMINISTRADOR
    	$this->db->select('cuentas_id');
    	$this->db->where('usuarios_id',$this->session->userdata('id'));
    	$cuentas_ids = $this->db->get('usuarios_cuentas')->result();
    	
    	$ids=array();
    	foreach($cuentas_ids as $cuenta)
    		$ids[]=$cuenta->cuentas_id;
    	
    	if(!in_array($this->session->userdata('cuentas_id'),$ids))
    		$ids[]=$this->session->userdata('cuentas_id');
    	
    	return $ids;
    }

    function get_dato($campo,$tabla,$condicion)
    {
        $this->db->where($condicion);
        $this->db->select($campo,FALSE);
        $r=$this->db->get($tabla)->row();

        if(empty($r))
            return false;
        return $r->{$campo};
    }
    
    function usuarios_externos_ids_get()
    {
    	$this->db->select('id');
    	$this->db->where('cliente_externo',1);
    	$this->db->where('eliminado',0);
    	$ids=$this->db->get('usuarios')->result();

    	$externos_ids=array(0);
    	foreach($ids as $id)
    		$externos_ids[]=$id->id;
    	
    	return $externos_ids;
    }

    function get_datos($campos,$tabla,$condicion)
    {
        $this->db->where($condicion);
        $this->db->select($campos);
        $r=$this->db->get($tabla)->row();
        return $r;
    }

	public function random_string($length = 5)
	{

		$possible = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = "";
		$i = 0;
		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			if (!strstr($randomString, $char)) {
				$randomString .= $char;
				$i++;
			}
		}

		return $randomString;
	}
 }
/* End of file avaluos.php */
/* Location: ./system/application/models/base.php */