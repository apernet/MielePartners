<?php
require_once('base.php');
class Frontend extends Base {	
	
	public function __construct() 
	{
		parent::__construct();    
	}

	public function get_productos_recientes()
	{
		$string=array(
				'p.id','p.modelo','p.descripcion','p.nombre',
				'fp.id AS fotografias_id',
		);
		$this->db->select($string);
		$this->db->limit('9');
		$this->db->order_by('p.created','DESC');
		$this->db->where('p.activo',1);
		$this->db->where('p.eliminado',0);
		$this->db->where('p.ocultar',0);
		$this->db->where('fp.activo',1);
		$this->db->where('fp.eliminado',0);
		$this->db->where('fp.tipos_id',1);
		$this->db->join('fotografias_productos AS fp','fp.productos_id = p.id');
		$query=$this->db->get('productos AS p')->result();
	
		return $query;
	}
	
	public function get_banner()
	{
		$this->db->select('id, productos_id,categorias_id,banner_principal,imagen_orden,url');
		$this->db->where('activo',1);
		$this->db->where('eliminado',0);
		$this->db->order_by('id','DESC');
		$banner=$this->db->get('banners')->result();

		return $banner;
	}
	
	public function get_productos($categorias_id)
    {
    	$this->db->select("
			p.id,
			p.modelo,
			p.nombre,
	    	p.descripcion,
    		p.precio,	
    		p.item,
    		p.categorias_id,
    		(SELECT fotografias_productos.id FROM fotografias_productos WHERE fotografias_productos.productos_id=p.id AND fotografias_productos.tipos_id=1 AND fotografias_productos.eliminado=0 LIMIT 1) as foto_id,
    		(SELECT productos_categorias.nombre FROM productos_categorias WHERE productos_categorias.id=p.categorias_id AND productos_categorias.activo=1 AND productos_categorias.eliminado=0 LIMIT 1) as categoria_nombre,
    		(SELECT productos_categorias.video_orden FROM productos_categorias WHERE productos_categorias.id=p.categorias_id AND productos_categorias.activo=1 AND productos_categorias.eliminado=0 LIMIT 1) as video_orden,
    		",FALSE);
		$this->db->from('productos as p');
    	$this->db->where('categorias_id',$categorias_id);
		$this->db->where('activo',1);
		$this->db->where('eliminado',0);
		$this->db->where('p.ocultar',0);
		$productos=$this->db->get()->result_array();
    	return $productos;	
    }
	
    /*public function get_datos_categoria($categorias_id)
    { 
    	$this->db->where('id',$categorias_id);
    	$this->db->from('productos_categorias');
    	$data['categoria']=$this->db->get()->row();
    	
    	$this->db->where('parent_id',$categorias_id);
    	$this->db->where('activo',1);
    	$this->db->where('eliminado',0);
    	$data['subcategorias']=$this->db->get('productos_categorias')->result_array();
 
    	return $data;   
    }
    
 	public function get_subcategorias($categorias_id)
    {
    	$this->db->where('parent_id',$categorias_id);
    	$this->db->where('activo',1);
    	$this->db->where('eliminado',0);
    	$r=$this->db->get('productos_categorias')->result_array();
 		if(empty($r))
 			return FALSE;
    	return $r;
    }
    
 	public function get_categorias()
    {
    	$this->db->where('activo',1);
    	$this->db->where('eliminado',0);
    	$r=$this->db->get('productos_categorias')->result_array();
    	$categorias=array();
   
    	foreach($r as $c)
    	{
    		foreach ($c as $k=>$v)
    		{
    			if (($k=='nombre') && empty($c['parent_id']))
    			{	
    				$d['id']=$c['id'];
    				$d['nombre']=$v;
    				$categorias[]=$d;
    			}
    		}
    	}
  
    	return $categorias;
    }*/
    
	/*public function get_datos_producto($productos_id)
    {
    	$this->db->where('id',$productos_id);
		$this->db->where('activo',1);
		$this->db->where('eliminado',0);
		$this->db->where('ocultar',0);
		$producto=$this->db->get('productos')->row();
		
    	return $producto;
    }*/
    
	public function get_fotografias_productos($productos_id)
    {
    	$this->db->order_by('tipos_id','ASC');
		$this->db->from('fotografias_productos');
    	$this->db->where('productos_id',$productos_id);
		$this->db->where('activo',1);
		//$this->db->where('extension','jpg');
		$this->db->where('eliminado',0);
		$fotografias_poductos=$this->db->get()->result_array();
		//debuglq();
		//debug($fotografias_poductos);
    	return $fotografias_poductos;	
    }
	
    public function get_categorias_id($productos_id)
    {
    	$this->db->select('categorias_id');
		$this->db->from('productos');
    	$this->db->where('id',$productos_id);
		$this->db->where('activo',1);
		$this->db->where('eliminado',0);
		$categorias_id=$this->db->get()->row('categorias_id');
    	return $categorias_id;	
    }
    
    public function random($length=8)
    {
    	$possible = "0123456789abcdfghjkmnpqrstvwxyz";
		$password = ""; 
		$i = 0;
		while ($i < $length) { 
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);     
			if (!strstr($password, $char)) {
				$password .= $char;
				$i++;
			}
		}
		return $password;
    }
    
	public function busca_resultados($str)
    {
    	$query=array(); 
    	$CI =& get_instance();
      	$CI->load->model('Producto');
    	foreach($str as $r)
    	{
    		$this->db->like('nombre',$r);
    		$this->db->where('activo',1);
    		$this->db->where('eliminado',0);
    		$query['categorias']=$this->db->get('productos_categorias')->result();
    	
    		
    		
    		$this->db->like('p.modelo',$r);
    		$this->db->or_like('p.item',$r);
    		$this->db->or_like('p.nombre',$r);
    		
    		$this->db->where('p.activo',1);
    		$this->db->where('p.eliminado',0);
    		
    		$p=$this->db->get('productos AS p')->result();
    		
    		foreach($p as &$po)
    		{
    			$po->imagen_producto=$CI->Producto->get_imagen_principal($po->id);
    		}
    		
    		$query['productos']=$p;
    	}

    	return $query;
    }
    
    public function busca_productos($str)
    {
    	$query=array();
    	foreach($str as $r)
    	{
    		$this->db->like('nombre',$r);
    		$this->db->where('activo',1);
    		$this->db->where('eliminado',0);
			$this->db->where('ocultar',0);
    		$query['productos']=$this->db->get('productos')->result();
    		
    		$this->db->select('p.*,fp.id AS imagen_producto');
    		$this->db->like('p.modelo',$r);
    		$this->db->or_like('p.item',$r);
    		
    		$this->db->where('p.activo',1);
    		$this->db->where('p.eliminado',0);
    		
    		$this->db->where('fp.activo',1);
    		$this->db->where('fp.eliminado',0);
    		
    		$this->db->join('fotografias_productos AS fp','fp.productos_id = p.id');
    		$query['productos']=$this->db->get('productos AS p')->result();
	
    	}

    	return $query;
    }
  
	public function get_documentacion($productos_id)
    {
    	$this->db->order_by('created','DESC');
    	$this->db->limit(1);
		$this->db->from('fotografias_productos');
    	$this->db->where('productos_id',$productos_id);
		$this->db->where('activo',1);
		$this->db->where('extension','pdf');
		$this->db->where('eliminado',0);
		$documentacion_poductos=$this->db->get()->result_array();
		
    	return $documentacion_poductos;	
    }
    
    public function breadcumb_categorias($categorias_id, &$categorias = array())
    {
    	$categoria = $this->base->read('productos_categorias', $categorias_id);
    	$categorias[] = $categoria;
    	if(!empty($categoria->parent_id))
    	{
    		return $this->breadcumb_categorias($categoria->parent_id, $categorias);
    	} else
    	{
    	    return array_reverse($categorias);
    	}
    }
    
    public function distribuidor_descuentos_get($categorias_ids)
    {
    	$descuentos=array();
    	
    	/*
    	 * DESCUENTOS DISTRIBUIDOR
    	 */
    	
    	//OBTENEMOS EL ID DE LA CUENTA
    	$cuentas_id = $this->session->userdata('cuentas_id');//$this->base->value_get('productos',$productos_id,'cuentas_id');
    	$descuento_distribuidor=array();
    	$descuento_distribuidor['total']=0;
    	$descuento_distribuidor['distribuidor_producto']=array();
    	
    	// 1.- DESCEUNTO BASE POR VENTA DE CATEGORÍA DE PRODUCTOS
    	if($categorias_ids)
    	{
	    	$suma=0;
	    	$descuentos_base=array();
	    	foreach($categorias_ids as $p_id=>$cat_id)
	    		$suma += $descuentos_base[$p_id] = $this->base->value_get('productos_categorias',$cat_id,'descuento_base');
	    	
	    	$descuento_distribuidor['base'] = $descuentos_base;
	    	$descuento_distribuidor['base_total']=$suma;
	    	$descuento_distribuidor['total'] += $suma;
	    	$descuento_distribuidor['distribuidor_producto']=$descuentos_base;
    	}
    	// 2.- DESCEUNTO DE VENTA ANUAL PARA EL DISTRIBUIDOR
    	$descuento_distribuidor['total'] += $descuento_distribuidor['monto_anual'] = $this->base->value_get('cuentas',$cuentas_id,'descuento_monto');
    	foreach($descuento_distribuidor['distribuidor_producto'] as $k=>$v)
    		$descuento_distribuidor['distribuidor_producto'][$k]=$descuento_distribuidor['distribuidor_producto'][$k] + $descuento_distribuidor['monto_anual'];
    	
    	// 3.- DESCUENTO POR ESPACIO DE EXHIBICIÓN DE CATEGORÍA DE PRODUCTOS
    	$this->db->select('categorias_id');
    	$this->db->where('cuentas_id',$cuentas_id);
    	$categorias=$this->db->get('cuentas_categorias')->result();
    	
    	if($categorias)
    	{
	    	$suma=0;
	    	$descuentos_categorias=array();
	    	foreach ($categorias as $categoria_id)
	    		$suma += $descuentos_categorias[$categoria_id->categorias_id] = $this->base->value_get('productos_categorias',$categoria_id->categorias_id,'descuento_exhibicion');
	    	
	    	$descuento_distribuidor['exhibicion_categoria'] = $descuentos_categorias;
	    	$descuento_distribuidor['exhibicion_categoria_total']=$suma;
	    	$descuento_distribuidor['total'] += $suma;
	    	
	    	foreach($descuento_distribuidor['distribuidor_producto'] as $k=>$v)
	    	{
	    			$ids=array();
	    			$categoria_producto=$this->base->value_get('productos',$k,'categorias_id');
	    			$ids[]=$categoria_producto;
	    			//VERIFICO SI TIENE PADRENT
	    			$ids=$this->categorias_lista($categoria_producto, $ids);
	    			foreach($ids as $i) {
	    				if(isset($descuentos_categorias[$i])) {
	    					$descuento_distribuidor['distribuidor_producto'][$k] += $descuentos_categorias[$i];
	    				}
	    			}

	    	}
    	}
    	
    	// 4.- DESCUENTO POR EXHIBICIÓN DE PAQUETE
    	$this->db->select('paquetes_id');
    	$this->db->where('cuentas_id',$cuentas_id);
    	$paquetes_ids=$this->db->get('cuentas_paquetes')->result();
    	
    	if($paquetes_ids)
    	{
	    	$suma=0;
	    	$descuentos_paquetes=array();
	    	foreach ($paquetes_ids as $paquete_id)
	    		$suma += $descuentos_paquetes[$paquete_id->paquetes_id] = $this->base->value_get('paquetes',$paquete_id->paquetes_id,'descuento_exhibicion');
	    	 
	    	$descuento_distribuidor['exhibicion_paquete'] = $descuentos_paquetes;
	    	$descuento_distribuidor['exhibicion_paquete_total']=$suma;
	    	$descuento_distribuidor['total'] += $suma;
	    	
    	}
    	
    	// 5.- DESCUENTO POR ESPACIO DE EXHIBICION
    	$descuento_distribuidor['total'] += $descuento_distribuidor['espacio'] = $this->base->value_get('cuentas',$cuentas_id,'descuento_espacio');
    	foreach($descuento_distribuidor['distribuidor_producto'] as $k=>$v)
    		$descuento_distribuidor['distribuidor_producto'][$k] += $descuento_distribuidor['espacio'];
    	
    	// 6.- DESCUENTO POR COOPERACION
    	$descuento_distribuidor['total'] += $descuento_distribuidor['cooperacion'] = $this->base->value_get('cuentas',$cuentas_id,'descuento_cooperacion');
    	foreach($descuento_distribuidor['distribuidor_producto'] as $k=>$v)
    		$descuento_distribuidor['distribuidor_producto'][$k] += $descuento_distribuidor['cooperacion'];
    	
    	// 7.- DESCUENTO POR TRANSICION
    	$descuento_distribuidor['total'] += $descuento_distribuidor['transicion'] = $this->base->value_get('cuentas',$cuentas_id,'descuento_transicion');
    	foreach($descuento_distribuidor['distribuidor_producto'] as $k=>$v)
    		$descuento_distribuidor['distribuidor_producto'][$k] += $descuento_distribuidor['transicion'];
    	
    	return $descuento_distribuidor;
    }
    
    public function categorias_lista($categoria_id, $cats = array())
    {
    	$this->db->select('parent_id');
    	$this->db->where('id',$categoria_id);
    	$id=$this->db->get('productos_categorias')->row('parent_id');
    	
    	if($id)
    	{
    		$cats[] = $id;
    		return $this->categorias_lista($id, $cats);
    	}
    	else
    		return $cats;
    }
    public function paquetes_productos_ids($productos,$paquetes_comprados=array())
    {
    	/*
    	 * VERIFICO QUE LOS PRODUCTOS DE LA COTIZACION
    	 * PERTENEZCAN A UN PAQUETE PARA APLICAR DESCUENTO AL CLIENTE
    	 */
    	
    	$this->db->select('id');
    	$paquetes_ids=$this->db->get('paquetes')->result();
    	
    	$indices_cantidades=array();
    	foreach ($paquetes_ids as $paquete_id)
    	{
    		$this->db->select('productos_id,cantidad');
    		$this->db->where('eliminado',0);
    		$this->db->where('paquetes_id',$paquete_id->id);
    		$paquete_productos_ids=$this->db->get('paquetes_productos')->result();
    		
    		$ids=array();
    		$existe=TRUE;
    		$i=0;
    		foreach($paquete_productos_ids as $id)
    		{
    			if(!in_array($id->productos_id,$productos['id']))
    			{
    				$existe=FALSE;
    				break;
    			}
    			else
    			{
    				if($productos['cantidad'][$i]==0)
    					unset($productos['id'][$i]);
    				else
    				{
    					if($productos['cantidad'][$i] >= $id->cantidad )
    						$productos['cantidad'][$i]=$productos['cantidad'][$i]-$id->cantidad;
    					else 
    					{
    						$existe=FALSE;
    						break;
    					}
    				}
    			}
    			$ids[]=$id->productos_id;
    			$indices_cantidades[$i]=$productos['cantidad'][$i];
    			$i++;
    		}
    		
    		if($existe)
    			$paquetes_comprados[][$paquete_id->id]=$ids;
    	}
    	
    	if(@min($indices_cantidades)!=0)
    		$paquetes_comprados = $this->paquetes_productos_ids($productos,$paquetes_comprados);
    	
    	return $paquetes_comprados;
    }
    
    public function get_cantidad_precio($id,$campo,$cotizaciones_id,$tabla)
    {
    	$this->db->select('precio,cantidad');
    	$this->db->where($campo,$id);
    	$this->db->where('cotizaciones_id',$cotizaciones_id);
    	$res=$this->db->get($tabla)->row();
    	return $res;
    }
    
    public function paquete_get_cantidad_producto($paquete_id,$producto_id)
    {
    	$this->db->select('cantidad');
    	$this->db->where('paquetes_id',$paquete_id);
    	$this->db->where('productos_id',$producto_id);
    	$res=$this->db->get('paquetes_productos')->row('cantidad');
    	return $res;
    }
    
    public function descuento_calcular($cotizaciones_id=FALSE)
    {
    	$CI = & get_instance();
    	$CI ->load->model('Cotizacion');
    	//OBTENGO LOS PRODUCTOS DE LA SESION
    	$productos_cotizacion=$this->session->userdata('productos');
    	$productos_ids=array_keys($productos_cotizacion);//SOLO IDS DE PRODUCTOS

    	//RECUPERO STATUS DE LA COTIZACION
    	$status=1;
    	if($cotizaciones_id)
    		$status=$CI->Cotizacion->get_status($cotizaciones_id);
    	
    	if($productos_cotizacion)
    	{
    		$descuentos=array();
    		$categorias_ids=array();
    		$producto_catidad=array();
    		foreach($productos_ids as $producto_id)
    		{
    			//LLENO ARREGLO DE CATEGORIAS A LAS QUE PERTENECE CADA PRODUCTO DE LA COTIZACIÓN
    			$categorias_ids[$producto_id] = $this->base->value_get('productos',$producto_id,'categorias_id');
    	
    			//ARRAY PARA CALCULAR DESCUENTO POR VENTA DE PAQUETES SEGUN CANTIDAD
    			$cantidad = $productos_cotizacion[$producto_id]['cantidad'];
    			$producto_catidad['id'][]=$producto_id;
    			$producto_catidad['cantidad'][]=$cantidad;
    		}
    		
    		//OBTENGO LOS PORCENTAJES DE DESCUENTO DEL DISTRIBUIDOR
    		if($categorias_ids )
    			$descuentos['distribuidor']=$this->distribuidor_descuentos_get($categorias_ids);
    		
    		//REVISO QUE PAQUETES SE COMPRARON
    		$paquetes_comprados = $this->paquetes_productos_ids($producto_catidad);
    		
    		//SI COMPRÓ PAQUETES RECUPERO PORCENTAJE DE DESCUENTOS
    		$pkg=array();
    		if(!empty($paquetes_comprados))
    		{
    			foreach($paquetes_comprados as $pc=>$paquete)
    			{
    				foreach($paquete as $k=>$v)
    				{
    					//OBTENGO PORCENTAJES DE DESCUENTO
    					$descuentos['cliente']['paquetes'][$k] = $this->base->value_get('paquetes',$k,array('descuento','descuento_distribuidor'));
    	
    					//RECUPERO LA CANTIDAD DE PRODUCTOS QUE SE REQUIEREN PARA FORMAR EL PAQUETE
    					foreach($v as $paq)
    					{
    						$cantidad_paq=$this->Frontend->paquete_get_cantidad_producto($k,$paq);
    						if(!isset($pkg[$k][$paq]))
    							$pkg[$k][$paq]=$cantidad_paq;
    						else
    							$pkg[$k][$paq]+=$cantidad_paq;
    					}
    				}
    			}
    	
    			//CALCULO DESCUENTO POR PRODUCTO DE CADA PAQUETE
    			$descuentos_cliente['total']=0;
    			$descuento_miele=0;
    			foreach ($descuentos['cliente']['paquetes'] as $dp=>$v)
    			{
    				foreach($paquetes_comprados as $pc=>$paq)
    				{
    					foreach($paq as $p=>$id)
    					{
    						foreach($id as $d)
    						{
    							if(isset($descuentos['distribuidor']))
    								$descuentos['distribuidor']['distribuidor_producto'][$d]+=$descuentos['distribuidor']['exhibicion_paquete'][$p];//+$v->descuento;
    							$descuentos['productos']['descuentos'][$d]= $v->descuento_distribuidor;// + $v->descuento;
    						}
    						
    					}
    				}
    			}
    			//$descuentos['distribuidor']['distribuidor_producto'][$d]+=$descuento_miele;
    		}
    		
    		//APLICO DESCUENTOS DE PAQUETE Y CALCULO IMPORTE DE CADA PRODUCTO
    		$subtotal=0;
    		
    		foreach($productos_ids as $p_id)
    		{
    			$descuento=0;
    			if(isset($descuentos['productos']['descuentos'][$p_id]))
    			{
    				//if($status==3 && $this->session->userdata('cuentas_id'))
    					//$descuentos['productos']['descuentos'][$p_id] += $descuentos['distribuidor']['distribuidor_producto'][$p_id];
    				$descuento=$descuentos['productos']['descuentos'][$p_id];
    			}
    			
    			//$descuento += $descuentos['distribuidor']['distribuidor_producto'][$p_id];
    			
    			$cantidad = $productos_cotizacion[$p_id]['cantidad'];
    			$precio = $productos_cotizacion[$p_id]['precio'];
    				
    			$cantidad_paquete=0;
    			foreach($pkg as $p=>$v)
    			{
    				if(isset($v[$p_id]))
    					$cantidad_paquete=$v[$p_id];
    			}
    	
    			$importe = $precio*$cantidad_paquete;
    			
    			$importe_distribuidor=$precio*$cantidad;
    			
    			if($descuento)
    				$importe = $importe - ($importe *($descuento/100));
    				
    			$importe += $precio*($cantidad-$cantidad_paquete);
    				
    			
    			$descuentos['productos']['importe'][$p_id]= $importe;
    			
    			$descuento=$descuentos['distribuidor']['distribuidor_producto'][$p_id];
    			if($descuento)
    				$importe_distribuidor = $importe_distribuidor - ($importe_distribuidor *($descuento/100));
    			
    			$importe_distribuidor += $precio*($cantidad);
    			
    			$descuentos['productos']['importe_distribuidor'][$p_id]= $importe_distribuidor;
    			
    			$subtotal += $importe;
    		}
    		
    		//RECUPERO IDS DE LOS ACCESORIOS PARA SUMARLOS AL SUBTOTAL
    		$accesorios_ids=array();
    		foreach($productos_cotizacion as $k=>$v)
    		{
    			if(!empty($v['accesorios']))
    			{
    				$ids=array_keys($v['accesorios']);
    				foreach ($ids as $id)
    					$accesorios_ids[$k][]=$id;
    			}
    		}
    		
    		//CALCULO IMPORTE DE ACCESORIOS
    		foreach($accesorios_ids as $k=>$v)
    		{
    			foreach($v as $acc)
    			{
	    			$cantidad = $productos_cotizacion[$k]['accesorios'][$acc]['cantidad'];
	    			$precio = $productos_cotizacion[$k]['accesorios'][$acc]['precio'];
	    			$importe = $precio*$cantidad;
	    			$descuentos['accesorios']['importe'][$acc]= $importe;
	    			$subtotal += $importe;
    			}
    		}
    			
    		$envio=0;
    		if(count($productos_ids)<3)
    		{
    			$estados_envio=catalogo('estados_envio');
    			$estado=$this->base->value_get('cotizaciones',$cotizaciones_id,'entrega_estado');
    			if(!in_array($estado,$estados_envio))
    				$envio=( ( $subtotal * 0.08 ) + ( ( $subtotal * 0.08 ) * 0.16 ) );
    		}
    			
    		$descuentos['subtotal']=$subtotal;
    		if($envio)
    			$subtotal += $envio;
    			
    		$iva=16;
    		$total = $subtotal + ($subtotal * ($iva/100));
    			
    		$descuentos['envio']=$envio;
    		$descuentos['iva']=$iva;
    		$descuentos['total']=$total;
    		return $descuentos;
    	}
    }
    
    public function existe_cliente($cliente_email)
    {
    	$this->db->select('email');
    	$this->db->where("(email='{$cliente_email}')");//OR usuario='{$cliente_email}'
    	$this->db->where('cliente_externo',1);
    	$this->db->where('eliminado',0);
    	$existe = $this->db->get('usuarios')->row('usuario');
    	return $existe;
    }
    
    public function grupo_externo_get()
    {
    	$this->db->select('id');
    	$this->db->where('nombre','Externos');
    	$this->db->where('activo',1);
    	$grupo_id=$this->db->get('grupos')->row()->id;
    	return $grupo_id;
    }
    
    public function cuenta_externo_get()
    {
    	$this->db->select('id');
    	$this->db->where('nombre','Externos');
    	$this->db->where('activo',1);
    	$cuenta_id=$this->db->get('cuentas')->row()->id;
    	return $cuenta_id;
    }
    
    /**
     * Limpiar cotizacion de la session
     */
    public function cotizacion_limpiar()
    {
    	$this->session->set_userdata('productos',array());
        $this->session->set_userdata('accesorios',array());
    	$this->session->set_userdata('cotizaciones_id',NULL);
    	$this->session->set_userdata('generar_cotizacion',FALSE);
    	$this->session->set_userdata('promociones_id',FALSE);
    	$this->session->set_userdata('promocion_opcional',FALSE);
    	$this->session->set_userdata('promociones',FALSE);
    	$this->session->set_userdata('promociones_descuento_porcentaje',FALSE);
    	$this->session->set_userdata('promociones_descuento_monto',FALSE);
    	$this->session->set_userdata('promociones_msi',FALSE);
    	$this->session->set_userdata('promociones_productos',FALSE);
    	$this->session->set_userdata('promociones_alianzas',FALSE);
    	$this->session->set_userdata('evento_estado',FALSE);
    }
    
    public function cotizaciones_cliente_conditions($cond)
    {
    	if(isset($cond['id']))
    		$this->db->where('id',$cond['id']);
    	
    	if(isset($cond['folio_compra']) && !empty($cond['folio_compra']))
    		$this->db->like('folio_compra',$cond['folio_compra']);

    	if(isset($cond['status_id']) && !empty($cond['status_id']))
    	{
    		if($cond['status_id']==1)
    			$this->db->where('pago_realizado',1);
    		elseif($cond['status_id']==2)
    		    $this->db->where('pago_realizado',NULL);
    	}
    }
    
    public function cotizaciones_cliente_externo_find($cond, $limit, $offset)
    {
    	$this->cotizaciones_cliente_conditions($cond);
    	$this->db->from('cotizaciones');
    	$this->db->where('eliminado', 0);
    	$this->db->where('usuario_id', $this->session->userdata('id'));
    	$this->db->order_by('id', 'DESC');
    
    	if ($limit)
    		$this->db->limit($limit, $offset);
    
    	$r = $this->db->get()->result_object();

    	return $r;
    }
    
    public function cotizaciones_cliente_externo_count($cond)
    {
    	$this->cotizaciones_cliente_conditions($cond);
    	$this->db->from('cotizaciones');
    	$this->db->where('eliminado', 0);
    	$this->db->where('usuario_id', $this->session->userdata('id'));
    	$r = $this->db->count_all_results();
    
    	return $r;
    }
    
    public function cotizacion_verifica_session()
    {
    	if(!$this->session->userdata('logged'))
    	{
    		$this->session->set_flashdata('error', 'Su sessi&oacute;n no est&aacute; activa, por favor ingrese nuevamente.');
			redirect('frontends/autenticacion');
    	}
    }

    public function cotizacion_verifica_carrito()
    {
    	if(!$this->session->userdata('productos') && !$this->session->userdata('accesorios'))
		{
			$this->session->set_flashdata('error', 'Su carrito est&aacute; vac&iacute;o, por favor elija alguno de nuestros productos.');
			redirect('frontends/index');
		}
    }
    
    public function cotizacion_verifica_pertenencia($cotizaciones_id)
    {
    	$pertenencia_id=$this->base->get_dato('usuario_id','cotizaciones',array('id'=>$cotizaciones_id));
		$puede_ver_todo = $this->base->tiene_permiso('cotizaciones_todas');
    	
	    if($pertenencia_id!=$this->session->userdata('id') && !$puede_ver_todo)
	    {
	    	$this->session->set_flashdata('error', 'Lo sentimos, la cotización a la que intenta acceder no es v&aacute;lida.');
	    	redirect('frontends/index');
	    }
    }
    
    public function cotizacion_verifica_pago($cotizaciones_id)
    {
    	$pagado=$this->base->get_dato('pago_realizado','cotizaciones',array('id'=>$cotizaciones_id));
    	 
    	if($pagado)
    	{
    		$this->session->set_flashdata('error', 'Esta cotizaci&oacute;n ya ha sido procesada anteriormente, por favor inicie una nueva cotizaci&oacute;n.');
    		redirect('frontends/index');
    	}
    }

    public function pago_procesar($cotizaciones_id,$metodo_id)
    {
        $data = array();
        // VARIABLES PARA CALCULAR MONTOS
        $CI =& get_instance();
        $CI->load->model('Cotizacion');
		$CI->load->model('Promocion');

        $cotizacion_datos = $CI->Cotizacion->get_cotizacion($cotizaciones_id);
        $data['entrega_estado']=$cotizacion_datos['cotizacion']['entrega_estado'];
        $data['instalacion_estado']=$cotizacion_datos['cotizacion']['instalacion_estado'];
		$data['descuento_cupon']=$cotizacion_datos['cotizacion']['descuento_cupon'];
		$data['cupones_id']=$cotizacion_datos['cotizacion']['cupones_id'];
		$data['opcion_cupon_id']=$cotizacion_datos['cotizacion']['opcion_cupon_id'];
		$data['folio_cupon']=$cotizacion_datos['cotizacion']['folio_cupon'];
		$data['promociones_id']=$cotizacion_datos['cotizacion']['promociones_id'];

		$data['promocion_opcional'] = $this->session->userdata('promocion_opcional');
        // DESCUENTO Y REDIRECT
        switch($metodo_id)
        {
            case 1: // AMERICAN EXPRESS 1 PAGO
                $url = 'payments/ae_pay/'.$cotizaciones_id;
                $data['descuento_opcional']=1;
                $data['descuento_paquete']=1;
                break;
            case 2: // BANAMEX 1 PAGO
                $url = 'payments/banamex_payment_pay/'.$cotizaciones_id;
                $data['descuento_opcional']=1;
                $data['descuento_paquete']=1;
                break;
            case 3: // AMERICAN EXPRESS 12 MESES
                $url = 'payments/ae_pay/'.$cotizaciones_id.'/1';
                $data['descuento_opcional']=0;
                $data['descuento_paquete']=0;
                $data['promocion_msi']=12;
				$data['sin_descuento_promocion']=1;
				$data['promocion_opcional']=array();
                /*// GUARDA PAGO A 12 MESES
                $mensualidades = array(
                    'id' => $cotizaciones_id,
                    'mensualidades' => 12,
                    'promocion_msi' => 12,
                );
                $this->base->guarda('cotizaciones', $mensualidades);*/

                break;
			case 4: // BANAMEX 18 MESES
				$url = 'payments/banamex_payment_pay/'.$cotizaciones_id.'/1';
				$data['descuento_opcional']=0;
				$data['descuento_paquete']=0;
				$data['promocion_msi']=18;
				$data['sin_descuento_promocion']=1;
				$data['promocion_opcional']=array();
				break;
        }

        // CALCULAR MONTOS FINALES
        $CI->load->model('Descuento');
        $productos=$this->session->userdata('productos');

		$promocion_opcional_regalos=array();
		if($data['promocion_opcional'])
			$promocion_opcional_regalos = $this->Promocion->regalos_get($data['promociones_id']);
		$data['promocion_opcional']=$promocion_opcional_regalos?$promocion_opcional_regalos:array();

        $datos=$CI->Descuento->calcular($cotizaciones_id,$data,$productos,TRUE);

        // REDIRECT A LA FORMA DE PAGO
        redirect($url);
    }

    function mensualidades_datos_get($cotizaciones_id)
    {
        $rs = false;
        $mensualidades = $this->base->get_datos('id, total_cliente, mensualidades', 'cotizaciones' ,array('id'=>$cotizaciones_id));
        if(isset($mensualidades->mensualidades))
        {
            $mensualidad = $mensualidades->total_cliente / $mensualidades->mensualidades;
            $rs = array(
                'mensualidades' => $mensualidades->mensualidades,
                'monto_mensual' => $mensualidad
            );
        }

        return $rs;
    }

	/**
	 * VERIFICA SI LA CUENTA DEL USUARIO EN SESIÓN PARTICIPA EN ALGÚN CUPÓN
	 */
	public function cupones_puede()
	{
		$this->db->from('cupones_cuentas');
		$this->db->where('cuentas_id',$this->session->userdata('cuentas_id'));
		$r = $this->db->count_all_results();

		if($r)
			return TRUE;

		return FALSE;
	}
}