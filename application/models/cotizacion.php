<?php
require_once ('base.php');

class Cotizacion extends Base
{
	var $status = array (
			1 => 'Cotizaci&oacute;n',
			2 => 'Orden de compra - pendiente',
			3 => 'Orden de compra - revisi&oacute;n',
			4 => 'Orden de compra - autorizada',
			5 => 'Orden de compra - cancelada',
			6 => 'Orden de compra - rechazada',
			7 => 'Cotizaci&oacute;n - cancelada',
	);
	
    //var $table = 'cotizaciones';
    
    var $acciones = array (
        1 => 'Agregar' 
    );
    
    var $forma_pago = array (
        1 => 'Depósito / Transferencia',
        2 => 'Cheque',
        3 => 'Tarjeta de Crédito / Débito' 
    );
    
    var $condiciones_pago = array (
        1 => '100%',
        2 => '50% - 50%' 
    );

    public function __construct()
    {
        parent::__construct();
    }

    private function pconditions($cond)
    {
        $externos_ids=$this->base->usuarios_externos_ids_get();

        $usuarios_cuentas_ids=$this->base->usuarios_cuentas_ids_get();
        $cotizaciones_cuenta = $this->base->tiene_permiso('cotizaciones_cuenta');
        $cotizaciones_todas = $this->base->tiene_permiso('cotizaciones_todas');
        
        // FILTRO PRODUCTO
        if (!empty($cond['productos_id']))
        {
            $this->db->select('cotizaciones_id');
            $this->db->from('cotizaciones_productos');
            $this->db->where('productos_id', $cond['productos_id']);
            $this->db->where('eliminado', 0);

            $r = $this->db->get()->result_object();

            $ids_cotizaciones[0] = 0;
            if(!empty($r))
            {
                $i=0;
                foreach($r as $k=>$v)
                {
                    $ids_cotizaciones[$i] = $v->cotizaciones_id;
                    $i++;
                }
            }

            $this->db->where_in('id', $ids_cotizaciones);
        }

        if ($cotizaciones_cuenta && empty($usuarios_cuentas_ids)) // COTIZACIONES DE LA CUENTA DEL USUARIO EN SESION
            $this->db->where('cuentas_id', $this->session->userdata('cuentas_id'));
        elseif($cotizaciones_cuenta && !empty($usuarios_cuentas_ids)) //COTIZACIONES DE LAS CUENTAS ASIGNADAS AL USUARIO INCLUYENDO LA DE LA SESSION
            $this->db->where_in('cuentas_id', $usuarios_cuentas_ids);
        elseif (!$cotizaciones_todas) // COTIZACIONES PROPIAS DEL USUARIO EN SESION
            $this->db->where('usuario_id', $this->session->userdata('id'));

        

        // FILTRO CUENTA
        if (!empty($cond['cuentas_id']))
            $this->db->where('cuentas_id', $cond['cuentas_id']);

        // FILTRO FOLIO CUENTA
        if (!empty($cond['folio_cuentas']))
            $this->db->like('folio_cuentas', $cond['folio_cuentas']);

        // FILTRO COMPRA
        if (!empty($cond['folio_compra']))
            $this->db->like('folio_compra', $cond['folio_compra']);
        
        // FILTRO FECHA
        if (!empty($cond['fecha_inicial']) || !empty($cond['fecha_final']))
        {
            if (!empty($cond['fecha_inicial']) && empty($cond['fecha_final']))
                $this->db->where("DATE_FORMAT(created,'%Y-%m-%d') >= '{$cond['fecha_inicial']}'");
            if (empty($cond['fecha_inicial']) && !empty($cond['fecha_final']))
                $this->db->where("DATE_FORMAT(created,'%Y-%m-%d') <= '{$cond['fecha_final']}'");
            if (!empty($cond['fecha_inicial']) && !empty($cond['fecha_final']))
            {
                $this->db->where("DATE_FORMAT(created,'%Y-%m-%d') >= '{$cond['fecha_inicial']}'");
                $this->db->where("DATE_FORMAT(created,'%Y-%m-%d') <= '{$cond['fecha_final']}'");
            }
        }

        // FILTRO STATUS
        if (!empty($cond['status']))
            $this->db->where('status_id', $cond['status']);
        
        // FILTRO STATUS
        if (!empty($cond['status_externo_id']))
        {
        	$this->db->where_in('usuario_id', $externos_ids);
        	
        	if($cond['status_externo_id']==1)
        		$this->db->where('pago_realizado', 1);
        	elseif($cond['status_externo_id']==2)
        		$this->db->where('pago_realizado IS NULL');
        }
        
        // FILTRO NOMBRE VENDEDOR
        if (!empty($cond['nombre_vendedor']))
            $this->db->where("CONCAT_WS(' ',vendedor_nombre,vendedor_paterno,vendedor_materno) like '%{$cond['nombre_vendedor']}%'");

        // FILTRO NOMBRE CLIENTE
        if (!empty($cond['nombre_comprador']))
        	$this->db->where("CONCAT_WS(' ',nombre_comprador,paterno_comprador,materno_comprador) like '%{$cond['nombre_comprador']}%'");

        // FILTRO IBS
        if (!empty($cond['ibs']))
        	$this->db->like("ibs", $cond['ibs']);

        // FILTRO ID
        if (!empty($cond['id']))
        	$this->db->where("id", $cond['id']);
    }
    
    public function find($cond, $limit, $offset)
    {
        $this->pconditions($cond);

        $this->db->select("c.id,
                           c.folio_cuentas,
                           c.folio_compra,
                           c.cuentas_id,
                           CONCAT_WS(' ',c.nombre_comprador, c.paterno_comprador, c.materno_comprador) as cliente_nombre_completo,
                           c.fecha,
                           CONCAT_WS(' ',c.vendedor_nombre, c.vendedor_paterno, c.vendedor_materno) as vendedor_nombre_completo,
                           CONCAT_WS(' ',c.referido_vendedor_nombre, c.referido_vendedor_paterno, c.referido_vendedor_materno) as referido_vendedor_nombre_completo,
                           c.referido_distribuidor_id,
                           c.ofrecio_evento,
                           c.status_id,
                           c.total_cliente,
                           c.total_distribuidor,
                           c.usuario_id,
                           c.pago_realizado,
                           c.email_comprador,
                           c.telefono_comprador,
                           c.ibs,
                           c.entrega_estado,
                           c.entrega_municipio,
                           c.entrega_asentamiento,
                           c.entrega_codigo_postal,
                           c.entrega_calle,
                           c.entrega_numero_exterior,
                           c.entrega_numero_interior,
                           c.instalacion_estado,
                           c.instalacion_municipio,
                           c.instalacion_asentamiento,
                           c.instalacion_codigo_postal,
                           c.instalacion_calle,
                           c.instalacion_numero_exterior,
                           c.instalacion_numero_interior,
                           c.estado,
                           c.municipio,
                           c.asentamiento,
                           c.codigo_postal,
                           c.calle,
                           c.numero_exterior,
                           c.numero_interior",FALSE);
        $this->db->from('cotizaciones as c');
        $this->db->where('c.eliminado', 0);
        $this->db->order_by('id', 'DESC');
        
        if($limit)
            $this->db->limit($limit, $offset);
        
        $r = $this->db->get()->result_object();

        $externos_ids = $this->base->usuarios_externos_ids_get();
        $cuentas = $this->base->lista('cuentas','id','nombre',FALSE,'nombre','ASC');
        $si_no = catalogo('si_no');
        $status = $this->status;
        foreach ($r as &$res)
        {
	        //REVISO SI ES ADMINISTRADOR O VENDEDOR PARA PERMISO DE IMPRESION
            $res->venta_directa = $this->es_venta_directa($res->cuentas_id);
	        $roles = $this->puede_imprimir($res->id, $res->venta_directa);
            $res->rol = $roles['rol'];

            $res->cuenta = ($res->cuentas_id)?$cuentas[$res->cuentas_id]:'NO APLICA';

            $res->fecha = get_fecha($res->fecha);

            $res->referido = 'NO APLICA';
            if($res->venta_directa && $res->referido_distribuidor_id && $res->referido_vendedor_nombre_completo)
                $res->referido = $cuentas[$res->referido_distribuidor_id].' / '.$res->referido_vendedor_nombre_completo;

            $res->valor = ($res->status_id==1)?moneda($res->total_cliente):($res->venta_directa?moneda($res->total_cliente):moneda($res->total_distribuidor));

            $res->productos_total = $this->productos_total_get($res->id);

            $res->ofrecio_evento = ($res->ofrecio_evento)?$si_no[$res->ofrecio_evento]:'';

            $res->status = $status[$res->status_id];

            if(in_array($res->usuario_id,$externos_ids) && $res->pago_realizado==1)
                $res->status_pago = 'PAGADO';
            elseif(in_array($res->usuario_id,$externos_ids) && $res->pago_realizado!=1)
                $res->status_pago = 'PAGO EN PROCESO';
            elseif(!in_array($res->usuario_id,$externos_ids))
                $res->status_pago = 'NO APLICA';

            $res->ibs = ($res->ibs)?$res->ibs:'NO DISPONIBLE';

            // PARA SABER SI ES UNA COTIZACION INTERNA O EXTERNA
			$res->externo = in_array($res->usuario_id,$externos_ids)?TRUE:FALSE;

            $res->puede_imprimir = $roles['puede_imprimir'];

            $res->cotizaciones_validar_pago = FALSE;
            if(in_array($res->usuario_id,$externos_ids) && $res->pago_realizado!=1 )
                $res->cotizaciones_validar_pago = TRUE;

            $res->cliente_email = (!empty($res->email_comprador))?$res->email_comprador:'';
            $res->cliente_telefono = (!empty($res->telefono_comprador))?$res->telefono_comprador:'';

            $res->cliente_direccion = $this->direccion_formato_get($res->estado, $res->municipio, $res->asentamiento, $res->codigo_postal, $res->calle, $res->numero_exterior, $res->numero_interior);
            $res->cliente_direccion_entrega = $this->direccion_formato_get($res->entrega_estado, $res->entrega_municipio, $res->entrega_asentamiento, $res->entrega_codigo_postal, $res->entrega_calle, $res->entrega_numero_exterior, $res->entrega_numero_interior);
            $res->cliente_direccion_instalacion = $this->direccion_formato_get($res->instalacion_estado, $res->instalacion_municipio, $res->instalacion_asentamiento, $res->instalacion_codigo_postal, $res->instalacion_calle, $res->instalacion_numero_exterior, $res->instalacion_numero_interior);
        }
        
        return $r;
    }

    public function puede_imprimir($cotizaciones_id, $venta_directa)
    {
        //REVISO SI ES ADMINISTRADOR O VENDEDOR PARA PERMISO DE IMPRESION
        $this->db->select('vendedor,admin');
        $this->db->where('id',$this->session->userdata('id'));
        $roles = $this->db->get('usuarios')->row();

        $data = array();
        if($roles->admin==1)//ADMINISTRADOR
            $data['rol']=1;
        elseif($roles->vendedor==1)//VENDEDOR
            $data['rol']=2;

        $data['puede_imprimir'] = TRUE;
        if((in_array($this->get_status($cotizaciones_id), array(2,3,4,5)) && @$data['rol']!=1) && !$this->session->userdata('cliente_externo') && !$venta_directa)
            $data['puede_imprimir']=FALSE;

        return $data;
    }

    public function count($cond)
    {
        $usuarios_cuentas_ids=$this->base->usuarios_cuentas_ids_get();
        $cotizaciones_cuenta = $this->base->tiene_permiso('cotizaciones_cuenta');
        $cotizaciones_todas = $this->base->tiene_permiso('cotizaciones_todas');

        //$this->db->select("c.*,CONCAT(u.nombre,' ',u.apellido_paterno,' ',u.apellido_materno) as nombre_completo_vendedor", FALSE);
        $this->pconditions($cond);

        if ($cotizaciones_cuenta && empty($usuarios_cuentas_ids)) // COTIZACIONES DE LA CUENTA DEL USUARIO EN SESION
            $this->db->where('cuentas_id', $this->session->userdata('cuentas_id'));
        elseif($cotizaciones_cuenta && !empty($usuarios_cuentas_ids)) //COTIZACIONES DE LAS CUENTAS ASIGNADAS AL USUARIO INCLUYENDO LA DE LA SESSION
            $this->db->where_in('cuentas_id', $usuarios_cuentas_ids);
        elseif (!$cotizaciones_todas) // COTIZACIONES PROPIAS DEL USUARIO EN SESION
            $this->db->where('usuario_id', $this->session->userdata('id'));

        $this->db->where('eliminado', 0);
        $this->db->from('cotizaciones as c');
        $r = $this->db->count_all_results();
        
        return $r;
    }

    public function get_vendedores()
    {
    	// OBTIENE LOS VENDEDORES SEGÚN LOS PERMISOS QUE SE TENGAN
    	$cotizaciones_todas=$this->tiene_permiso('cotizaciones_todas');
    	$cotizaciones_cuenta=$this->tiene_permiso('cotizaciones_cuenta');
    	$cotizaciones_individuales=$this->tiene_permiso('cotizaciones_individuales');
    	$usuarios_cuentas_ids=$this->base->usuarios_cuentas_ids_get();
    	
    	$this->db->select("u.id,CONCAT(u.nombre,' ',u.apellido_paterno,' ',u.apellido_materno) AS nombre_vendedor");
    	$this->db->where('u.vendedor', 1);
    
    	if(!$cotizaciones_todas)
    	{
    		if($cotizaciones_cuenta && empty($usuarios_cuentas_ids))
    		{
    			$this->db->join('cuentas as c','c.id=u.cuentas_id','left');
    			$this->db->where('c.id',$this->session->userdata('cuentas_id'));
    		}
    		elseif($cotizaciones_cuenta && !empty($usuarios_cuentas_ids))
    		{
    			$this->db->join('cuentas as c','c.id=u.cuentas_id','left');
    			$this->db->where_in('c.id',$usuarios_cuentas_ids);
    		}
    		elseif($cotizaciones_individuales)
    			$this->db->where('u.id',$this->session->userdata('id'));
    	}
    	$this->db->order_by('nombre_vendedor','ASC');
    	$res = $this->db->get('usuarios as u')->result_array();
    
    	$vendedores=array();
    	if($res)
    	{
	    	foreach($res as $r)
	    		$vendedores[$r['id']]=$r['nombre_vendedor'];
    	}
    	return $vendedores;
    }
    
    public function es_venta_directa($cuentas_id)
    {
    	// DETERMINA SI UNA COTIZACIÓN ES VENTA DIRECTA O DE DISTRIBUIDOR
    	$this->db->select('venta_directa');
    	$this->db->where('id',$cuentas_id);
    	$venta_directa=$this->db->get('cuentas')->row('venta_directa');
    
    	return $venta_directa;
    }

    public function productos_find($cond, $limit, $offset)
    {
        $CI = &get_instance();
        $CI->load->Model('Producto');
        $cond['no_ids'] = $this->session->userdata('cotizacion_productos'); // NO LOS QUE ESTAN EN SESION
        if (! isset($conditions ['cuentas_id']))
            $cond['cuentas_id'] = $this->session->userdata('cuentas_id');
        $r = $CI->Producto->find($cond, $limit, $offset);
        return $r;
    }

    public function productos_count($cond)
    {
        $CI = &get_instance();
        $CI->load->Model('Producto');
        $cond['no_ids'] = $this->session->userdata('cotizacion_productos'); // NO LOS QUE ESTAN EN SESION
        
        $r = $CI->Producto->count($cond);
        
        return $r;
    }

    public function get_productos_ids($cotizaciones_id)
    {
        // OBTIENE LOS IDS DE LOS PRODUCTOS DE UNA COTIZACION
        $this->db->select('productos_id');
        $this->db->where('cotizaciones_id', $cotizaciones_id);
        $this->db->where('eliminado', 0);
        $this->db->where('(promocion IS NULL OR promocion=0)');
        $r = $this->db->get('cotizaciones_productos')->result_object();
        
        $productos_ids = array ();
        foreach ( $r as $ro )
            $productos_ids [] = $ro->productos_id;

        if (empty($productos_ids))
            return array ();
        
        return $productos_ids;
    }
    
    public function get_accesorios_ids($cotizaciones_id)
    {
    	// OBTIENE LOS IDS DE LOS ACCESORIOS DE UNA COTIZACION
    	$this->db->select('accesorios_id');
    	$this->db->where('cotizaciones_id', $cotizaciones_id);
    	$this->db->where('eliminado', 0);
        $this->db->where('(promocion IS NULL OR promocion=0)');
    	$r = $this->db->get('cotizaciones_accesorios')->result_object();
    	
    	$accesorios_ids = array ();
    	foreach ( $r as $ro )
    		$accesorios_ids [] = $ro->accesorios_id;
    
    	if (empty($accesorios_ids))
    		return array ();
    
    	return $accesorios_ids;
    }

    public function get_producto($productos_id, $accesorios = array(),$array=FALSE)
    {
        $CI = & get_instance();
        $CI->load->model('Producto');

        $data = parent::read('productos', $productos_id, $array);

        if($array)
        {
	        $data['cantidad'] = 1;
	        $data['img_id'] = $CI->Producto->get_imagen_principal($productos_id);
	        $data['accesorios'] = array ();
        }
        else
        {
        	$data->cantidad = 1;
        	$data->img_id = $CI->Producto->get_imagen_principal($productos_id);
        	$data->accesorios = array ();
        }

        if($accesorios)
        {
            foreach ( $accesorios as $k => $a )
            {
                $acc = $this->base->read('accesorios', $k,$array);
                if($array)
                {
                    $acc['cantidad'] = $a['cantidad'];
                    $acc['unidad'] = isset($a['unidad'])?$a['unidad']:(elemento('Unidades', $acc['unidad_id']));
                    $acc['descuento_cliente']   = isset($a['descuento_cliente'])?$a['descuento_cliente']:0;
                    $acc['importe_cliente']   = isset($a['importe_cliente'])?$a['importe_cliente']:0;
                    $acc['descuento_distribuidor']   = isset($a['descuento_distribuidor'])?$a['descuento_distribuidor']:0;
                    $acc['importe_distribuidor']   = isset($a['importe_distribuidor'])?$a['importe_distribuidor']:0;
                    $acc['obligatorio'] = $a['obligatorio'];
                    $data['accesorios'] [] = $acc;
                }
                else
                {
                    $acc->cantidad = $a['cantidad'];
                    $acc->unidad = isset($a['unidad'])?$a['unidad']:(elemento('Unidades', $acc->unidad_id));
                    $acc->descuento_cliente   = isset($a['descuento_cliente'])?$a['descuento_cliente']:0;
                    $acc->importe_cliente   = isset($a['importe_cliente'])?$a['importe_cliente']:0;
                    $acc->descuento_distribuidor   = isset($a['descuento_distribuidor'])?$a['descuento_distribuidor']:0;
                    $acc->importe_distribuidor   = isset($a['importe_distribuidor'])?$a['importe_distribuidor']:0;
                    $acc->obligatorio = $a['obligatorio'];
                    $data->accesorios [] = $acc;
                }
            }
        }

        return $data;
    }
    
    /*
     * public function get_productos_from_ids($productos_ids,$cotizaciones_id=FALSE) { if(!empty($productos['id'])) $productos=$productos['id']; // RECIBE UN ARREGLO DE IDS DE LA TABLA DE PRODUCTOS Y REGRESA EL CONTENIDO DE LOS MISMOS if(empty($productos['id'])) return array(); $CI=&get_instance(); $CI->load->Model('Producto'); $cond['ids']=$productos_ids; $productos=$CI->Producto->find($cond,NULL,NULL); return $productos; }
     */
    public function get_accesorios_productos($productos_id = FALSE, $cotizaciones_id = FALSE)
    {
        if (!empty($productos_id))
            $this->db->where('ca.productos_id', $productos_id);
        
        if (!empty($cotizaciones_id))
            $this->db->where('ca.cotizaciones_id', $cotizaciones_id);
        $this->db->select('ca.*,a.modelo,a.nombre,a.item,a.descripcion,a.imagen_orden,a.unidad_id');
        $this->db->where('ca.eliminado', 0);
        $this->db->where('a.eliminado', 0);
        $this->db->where('a.activo',1);
        $this->db->join('accesorios a', 'a.id=ca.accesorios_id','left outer');
        $this->db->from('cotizaciones_accesorios as ca');
        $r = $this->db->get()->result_array();
        
        return $r;
    }

    public function elimina_no_activos($cotizaciones_id)
    {
        //RECUPERO IDS DE PRODUCTOS DE COTIZACION
        $this->db->select('productos_id');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $res = $this->db->get('cotizaciones_productos')->result();

        $productos_ids=array();
        foreach($res as $k=>$v)
        {
            $activo = $this->base->get_dato('activo','productos',array('id'=>$v->productos_id));
            if(!$activo)
                $productos_ids[]=$v->productos_id;
        }

        if(!empty($productos_ids))
        {
            $data['eliminado']=1;
            $this->db->where('cotizaciones_id',$cotizaciones_id);
            $this->db->where_in('productos_id',$productos_ids);
            $this->db->update('cotizaciones_productos', $data);
        }


        //RECUPERO IDS DE ACCESORIOS Y CONSUMIBLES DE COTIZACION
        $this->db->select('accesorios_id');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $res = $this->db->get('cotizaciones_accesorios')->result();

        $accesorios_ids=array();
        foreach($res as $k=>$v)
        {
            $activo = $this->base->get_dato('activo','accesorios',array('id'=>$v->accesorios_id));
            if(!$activo)
                $accesorios_ids[]=$v->accesorios_id;
        }

        if(!empty($accesorios_ids))
        {
            $data['eliminado']=1;
            $this->db->where('cotizaciones_id',$cotizaciones_id);
            $this->db->where_in('accesorios_id',$accesorios_ids);
            $this->db->update('cotizaciones_accesorios', $data);
        }

        return TRUE;
    }

    public function guarda($data, $productos = FALSE, $accesorios=FALSE)
    {
        // GUARDA LA COTIZACION
        $promociones_id =FALSE;
        if(!isset($data['id']))
        {
            $id_usuario = $data['usuario_id'];
            $cuentas = parent::read('usuarios',$id_usuario);
            $data['cuentas_id']= $data['cuentas_id']?$data['cuentas_id']:$cuentas->cuentas_id;
        }

        if(@$data['promociones_id'])
            $promociones_id=$data['promociones_id'];

        $cotizaciones_id = parent::guarda('cotizaciones', $data);

        // PONE COMO ELIMININADOS TODOS LOS PRODUCTOS, ACCESORIOS, CONSUMIBLES Y EVENTOS QUE NO ESTEN ACTIVOS
        if($cotizaciones_id)
        {
            $this->elimina_no_activos($cotizaciones_id);
        }

        if ($productos)
        {
            // GUARDA PRODUCTOS Y ACCESORIOS
            foreach ( $productos as $ts => $producto )
            {
                $data = array ();
                $id = $this->get_table_id($cotizaciones_id,$producto->id);
                if($id)
                    $data['id']=$id;
                $data ['timestamp'] = $ts;
                $data ['productos_id'] = $producto->id;
                $data ['unidad_id'] = $producto->unidad_id;
                $data ['cantidad'] = $producto->cantidad;
                $data ['precio'] = $producto->precio;
                $data ['sin_envio'] = $producto->sin_envio;
                $data ['cotizaciones_id'] = $cotizaciones_id;
                $id = parent::guarda('cotizaciones_productos', $data);
                if (!empty($producto->accesorios))
                {
                    foreach ( $producto->accesorios as $k => $v )
                    {
                        $accesorio = array ();
                        $id = $this->get_table_id($cotizaciones_id,$producto->id, $v->id);
                        if($id)
                            $accesorio['id']=$id;
                        $accesorio ['timestamp'] = $ts;
                        $accesorio ['productos_id'] = $producto->id;
                        $accesorio ['tipos_accesorios_id'] = $v->tipos_accesorios_id;
                        $accesorio ['accesorios_id'] = $v->id;
                        $accesorio ['cantidad'] = $v->cantidad;
                        $accesorio ['precio'] = $v->precio;
                        $accesorio ['consumible'] = $v->consumible;
                        $accesorio ['cotizaciones_id'] = $cotizaciones_id;
                        $accesorio ['cotizaciones_productos_id'] = $id;
                        parent::guarda('cotizaciones_accesorios', $accesorio);
                    }
                }
            }
        }

        if($accesorios)
        {
            foreach($accesorios as $k=>$acc)
            {
                $accesorio = array ();

                if($cotizaciones_id && isset($acc->accesorios_id) && $cotizaciones_id==$acc->cotizaciones_id)
                    $accesorio ['id'] = $acc->id;

                $accesorio ['timestamp'] = $k;
                $accesorio ['productos_id'] = NULL;
                $accesorio ['tipos_accesorios_id'] = $acc->tipos_accesorios_id;
                $accesorio ['accesorios_id'] = isset($acc->accesorios_id)?$acc->accesorios_id:$acc->id;
                $accesorio ['cantidad'] = $acc->cantidad;
                $accesorio ['precio'] = $acc->precio;
                $accesorio ['consumible'] = $acc->consumible;
                $accesorio ['cotizaciones_id'] = $cotizaciones_id;
                $accesorio ['importe_cliente'] = round($acc->cantidad*$acc->precio,2);
                $accesorio ['importe_distribuidor'] = round($acc->cantidad*$acc->precio,2);
                $accesorio ['cotizaciones_productos_id'] = NULL;
                parent::guarda('cotizaciones_accesorios', $accesorio);
            }
        }

        if($promociones_id)
        {
            $CI = &get_instance();
            $CI->load->Model('Promocion');
            $promociones=$CI->Promocion->regalos_get($promociones_id);
            $promocion_opcional_descuento = $this->session->userdata('promocion_opcional');

            $eliminar =array();
            $eliminar['eliminado']=1;

            if(isset($promociones['productos']) && !empty($promociones['productos']))
            {
                $this->db->where('cotizaciones_id',$cotizaciones_id);
                $this->db->where('promocion',1);
                $this->db->update('cotizaciones_productos',$eliminar);

                foreach($promociones['productos'] as $p)
                {
                    $producto=array();
                    $id = $this->get_table_id($cotizaciones_id,$p['id'],NULL,FALSE,TRUE);
                    if($id)
                        $producto['id']=$id;
                    $producto ['timestamp'] = $p['id'];
                    $producto ['productos_id'] = $p['id'];
                    $producto ['cantidad'] = 1;
                    $producto ['precio'] = $promocion_opcional_descuento?$p['precio']:0.00;
                    $producto ['sin_envio'] = 1;
                    $producto ['promocion'] = 1;
                    $producto ['cotizaciones_id'] = $cotizaciones_id;
                    $producto ['descuento_cliente'] = $promocion_opcional_descuento?round($p['precio']*($p['porcentaje_descuento']/100),2):0.00;
                    $producto ['descuento_distribuidor'] = $promocion_opcional_descuento?round($p['precio']*($p['porcentaje_descuento']/100),2):0.00;
                    $producto ['importe_cliente'] = $promocion_opcional_descuento?round($p['precio']-($p['precio']*($p['porcentaje_descuento']/100)),2):0.00;
                    $producto ['importe_distribuidor'] = $promocion_opcional_descuento?round($p['precio']-($p['precio']*($p['porcentaje_descuento']/100)),2):0.00;
                    $id = parent::guarda('cotizaciones_productos', $producto);
                }
            }

            if(isset($promociones['accesorios']) && !empty($promociones['accesorios']))
            {
                $this->db->where('cotizaciones_id',$cotizaciones_id);
                $this->db->where('promocion',1);
                $this->db->where('consumible',0);
                $this->db->update('cotizaciones_accesorios',$eliminar);

                foreach($promociones['accesorios'] as $p)
                {
                    $data=array();
                    $id = $this->get_table_id($cotizaciones_id,NULL,$p['id'],FALSE,TRUE);
                    if($id)
                        $data['id']=$id;
                    $data ['timestamp'] = $p['id'];
                    $data ['productos_id'] = NULL;
                    $data ['accesorios_id'] = $p['id'];
                    $data ['tipos_accesorios_id'] = $p['tipos_accesorios_id'];
                    $data ['cantidad'] = 1;
                    $data ['precio'] = $promocion_opcional_descuento?$p['precio']:0.00;
                    $data ['consumible'] = 0;
                    $data ['promocion'] = 1;
                    $data ['cotizaciones_id'] = $cotizaciones_id;
                    $data ['descuento_cliente'] = $promocion_opcional_descuento?round($p['precio']*($p['porcentaje_descuento']/100),2):0.00;
                    $data ['descuento_distribuidor'] = $promocion_opcional_descuento?round($p['precio']*($p['porcentaje_descuento']/100),2):0.00;
                    $data ['importe_cliente'] = $promocion_opcional_descuento?round($p['precio']-($p['precio']*($p['porcentaje_descuento']/100)),2):0.00;
                    $data ['importe_distribuidor'] = $promocion_opcional_descuento?round($p['precio']-($p['precio']*($p['porcentaje_descuento']/100)),2):0.00;
                    $id = parent::guarda('cotizaciones_accesorios', $data);
                }
            }

            if(isset($promociones['consumibles']) && !empty($promociones['consumibles']))
            {
                $this->db->where('cotizaciones_id',$cotizaciones_id);
                $this->db->where('promocion',1);
                $this->db->where('consumible',1);
                $this->db->update('cotizaciones_accesorios',$eliminar);

                foreach($promociones['consumibles'] as $p)
                {
                    $data=array();
                    $id = $this->get_table_id($cotizaciones_id,NULL,$p['id'],FALSE, TRUE);
                    if($id)
                        $data['id']=$id;
                    $data ['timestamp'] = $p['id'];
                    $data ['productos_id'] = NULL;
                    $data ['accesorios_id'] = $p['id'];
                    $data ['tipos_accesorios_id'] = $p['tipos_accesorios_id'];
                    $data ['cantidad'] = 1;
                    $data ['precio'] = 0.00;
                    $data ['consumible'] = 1;
                    $data ['promocion'] = 1;
                    $data ['cotizaciones_id'] = $cotizaciones_id;
                    $data ['descuento_cliente'] = 0.00;
                    $data ['descuento_distribuidor'] = 0.00;
                    $data ['importe_cliente'] = 0.00;
                    $data ['importe_distribuidor'] = 0.00;
                    $id = parent::guarda('cotizaciones_accesorios', $data);
                }
            }

            if(isset($promociones['alianzas']) && !empty($promociones['alianzas']))
            {
                $orden_pagada=$this->base->get_dato('recibo_pago_cdn','cotizaciones',array('id'=>$cotizaciones_id));

                foreach($promociones['alianzas'] as $p)
                {
                    if(INTERNO && $orden_pagada)
                    {
                        $data=array();
                        $id = $this->get_table_id($cotizaciones_id,NULL,NULL,FALSE, FALSE, $p['id']);
                        if($id)
                            $data['id']=$id;

                        $codigo = $this->Promocion->alianza_codigo_get($p['id'],$cotizaciones_id,TRUE);
                        if($codigo)
                            $data['codigo']=$codigo;

                        $alianza=$this->base->read('alianzas_promociones',$p['id']);
                        $data['nombre']=$alianza->nombre;
                        $data['descripcion']=$alianza->descripcion;
                        $data ['promociones_id'] = $p['promociones_id'];
                        $data ['alianzas_id'] = $p['id'];
                        $data ['cotizaciones_id'] = $cotizaciones_id;
                        $id = parent::guarda('cotizaciones_alianzas', $data);
                    }
                    else
                    {
                        $data=array();
                        $id = $this->get_table_id($cotizaciones_id,NULL,NULL,FALSE, FALSE, $p['id']);
                        if($id)
                            $data['id']=$id;

                        $alianza=$this->base->read('alianzas_promociones',$p['id']);
                        $data['nombre']=$alianza->nombre;
                        $data['descripcion']=$alianza->descripcion;
                        $data ['promociones_id'] = $p['promociones_id'];
                        $data ['alianzas_id'] = $p['id'];
                        $data ['cotizaciones_id'] = $cotizaciones_id;
                        $id = parent::guarda('cotizaciones_alianzas', $data);
                    }
                }
            }

            $data=array();

            if(isset($promociones['descuento']['porcentaje']) && !empty($promociones['descuento']['porcentaje']))
                $data ['promocion_porcentaje'] = $promociones['descuento']['porcentaje'];

            if(isset($promociones['descuento']['monto']) && !empty($promociones['descuento']['monto']))
                $data ['promocion_fija'] = $promociones['descuento']['monto'];

            if(!empty($data))
            {
                $data['id']=$cotizaciones_id;
                $id = parent::guarda('cotizaciones', $data);
            }
        }

        return $cotizaciones_id;
    }

    public function get_table_id($cotizaciones_id, $productos_id=NULL, $accesorios_id=NULL, $individuales=FALSE, $promocion=FALSE, $alianzas_id=NULL)
    {
        $this->db->select('id');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('eliminado',0);

        if($promocion)
            $this->db->where('promocion',$promocion);

        $tabla='';
        if($productos_id)
        {
            $this->db->where('productos_id',$productos_id);
            $tabla='cotizaciones_productos';
        }

        if($accesorios_id || $individuales)
        {
            $this->db->where('accesorios_id',$accesorios_id);
            $tabla='cotizaciones_accesorios';
        }

        if($alianzas_id)
        {
            $this->db->where('alianzas_id',$alianzas_id);
            $tabla='cotizaciones_alianzas';
        }

        $this->db->limit(1);
        $this->db->order_by('id','DESC');
        $id = $this->db->get($tabla)->row('id');

        return $id?$id:FALSE;
    }

    public function get_productos($cotizaciones_id)
    {
        $this->db->select('productos_id');
        $this->db->where('cotizaciones_id', $cotizaciones_id);
        $r = $this->db->get('cotizaciones_productos')->result_object();
        
        $productos_ids = array ();
        foreach ( $r as $ro )
            $productos_ids [] = $ro->productos_id;
        
        if (empty($productos_ids))
            return array ();
        
        $productos = $this->get_productos_from_ids($productos_ids);
        
        return $productos;
    }

    public function get_clave($cuentas_id)
    {
        $this->db->select('clave'); // as
        $this->db->where('id', $cuentas_id);
        $r = $this->db->get('cuentas')->row();
        if (!empty($r))
            return $r->clave;
        else
            return FALSE;
    }

    public function genera_folio($cotizaciones_id, $folio_existente = NULL)
    {
        $this->db->select('folio,cuentas_id');
        $this->db->where('id', $cotizaciones_id);
        $r = $this->db->get('cotizaciones')->row();
        
        if (empty($r->folio))
        {
            $this->db->select('consecutivo');
            $this->db->where('id', $r->cuentas_id);
            $cc = $this->db->get('cuentas')->row()->consecutivo;
            
            if (empty($cc))
            {
                $data ['consecutivo'] = 1;
                $this->db->where('id', $r->cuentas_id);
                $this->db->update('cuentas', $data);
                $cc = 1;
            } else
            {
                $this->db->query("UPDATE cuentas set consecutivo=consecutivo+1 WHERE id ='{$r->cuentas_id}';"); // INCREMENTA EL CONSECUTIVO DE LA CUENTA
                $this->db->select('consecutivo');
                $this->db->where('id', $r->cuentas_id);
                $cc = $this->db->get('cuentas')->row()->consecutivo;
            }
            
            if ($folio_existente)
            {
                $existente = explode('-', $folio_existente);
                
                $this->db->select('folio_cuentas');
                $this->db->like('folio_cuentas', $existente [0]);
                $this->db->where('eliminado', 0);
                $folios_existentes = $this->db->get('cotizaciones')->result();
                $con = count($folios_existentes);
                $con = $con > 1 ? $con ++ : 1;
                $folio_cuentas = $existente [0] . '-' . $con;
            } else
            {
                
                $this->db->select('clave');
                $this->db->where('id', $r->cuentas_id);
                $clave = $this->db->get('cuentas')->row()->clave;
                
                $i = strlen($cc);
                $aux = $clave;
                $y = date('y');
                $aux .= $y;
                while ( $i < 3 )
                {
                    $aux .= '0';
                    $i ++;
                }
                
                $folio_cuentas = $aux . $cc;
            }
            $folio = $cc;
            $this->db->query("UPDATE cotizaciones SET folio='{$folio}' WHERE id='{$cotizaciones_id}';");
            $this->db->query("UPDATE cotizaciones SET folio_cuentas='{$folio_cuentas}' WHERE id='{$cotizaciones_id}';");
        }
        return $folio;
    }

    public function genera_folio_compra($cotizaciones_id)
    {
        $this->db->select('folio_compra,cuentas_id');
        $this->db->where('id', $cotizaciones_id);
        $r = $this->db->get('cotizaciones')->row();
        if (empty($r->folio_compra))
        {
            $this->db->select('consecutivo_compra');
            $this->db->where('id', $r->cuentas_id);
            $cc = $this->db->get('cuentas')->row()->consecutivo_compra;
            
            if (empty($cc))
            {
                $data ['consecutivo_compra'] = 1;
                $this->db->where('id', $r->cuentas_id);
                $this->db->update('cuentas', $data);
                $cc = 1;
            } else
            {
                $this->db->query("UPDATE cuentas set consecutivo_compra=consecutivo_compra+1 WHERE id ='{$r->cuentas_id}';"); // INCREMENTA EL CONSECUTIVO DE LA CUENTA
                $this->db->select('consecutivo_compra');
                $this->db->where('id', $r->cuentas_id);
                $cc = $this->db->get('cuentas')->row()->consecutivo_compra;
            }
            
            $this->db->select('clave');
            $this->db->where('id', $r->cuentas_id);
            $clave = $this->db->get('cuentas')->row()->clave;
            
            $i = strlen($cc);
            $aux = $clave;
            $y = date('y');
            $aux .= $y;
            
            while ( $i < 3 )
            {
                $aux .= '0';
                $i ++;
            }
            
            $folio_compra = $aux . $cc;
            
            $this->db->query("UPDATE cotizaciones SET folio_compra='{$folio_compra}' WHERE id='{$cotizaciones_id}';");
        }
        
        return ;//$folio;
    }

    public function get_cotizacion($cotizaciones_id)
    {
    	$CI = & get_instance();
    	$CI->load->model('Producto');
    	$res ['cotizacion'] = $this->read('cotizaciones', $cotizaciones_id,TRUE);
    
    	$this->db->select("
    		c.usuario_id, u.cuentas_id,
	    	CONCAT(u.nombre,' ',u.apellido_paterno,' ',u.apellido_materno) as nombre_completo_vendedor
    	", FALSE);
    
    	$this->db->from('cotizaciones as c');
    	$this->db->join('usuarios as u', 'u.id=c.usuario_id', 'left outer');
    	$this->db->where('c.id', $cotizaciones_id);
    	$this->db->where('c.eliminado', 0);
    	$res ['vendedor'] = $this->db->get()->row();
   
    	$this->db->where('id', $res ['cotizacion']['created_by']);
    	$res ['ejecutivo'] = $this->db->get('usuarios')->row();
    
    	return $res;
    }
    
    public function get_productos_cotizacion($cotizaciones_id, $regalos=FALSE)
    {
    	$this->db->where('cotizaciones_id', $cotizaciones_id);
    	$this->db->where('eliminado', 0);
        if($regalos)
            $this->db->where('promocion',1);
        else
            $this->db->where('(promocion IS NULL OR promocion=0)');
    	$productos = $this->db->get('cotizaciones_productos')->result_array();
    	
    	$CI = & get_instance();
    	$CI->load->model('Producto');
        $evento_estado = $this->session->userdata('evento_estado');
    	
    	$r = array ();
    	foreach ($productos as $p)
    	{
    		//$productos_id = $p['productos_id'];
    		// OBTIENE LOS DATOS DEL PRODUCTO
    		$r[$p['productos_id']] = parent::read('productos',$p['productos_id'],TRUE);
    		$r[$p['productos_id']]['cantidad'] = $p['cantidad'];
            $r[$p['productos_id']]['unidad'] = elemento('Unidades',$p['unidad_id']);

            if(empty($p['unidad_id']) || $p['unidad_id']!=2)
                $r[$p['productos_id']]['precio'] =$p['precio'];
            else
            {
                $estado = (!empty($evento_estado)) ? $evento_estado : $this->base->get_dato('evento_estado','cotizaciones',array('id'=>$cotizaciones_id));
                $postfijo = strtolower(str_replace(' ','_',$estado));
                $evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$p['productos_id']));
                $r[$p['productos_id']]['precio'] = $evento->{'precio_inicial_'.$postfijo};
                $r[$p['productos_id']]['cantidad'] = (@$p['cantidad']!=1) ? $p['cantidad'] : $evento->{'horas_iniciales_'.$postfijo};
            }

    		$r[$p['productos_id']]['unidad_id'] = $p['unidad_id'];
    		$r[$p['productos_id']]['descuento_cliente'] = $p['descuento_cliente'];
    		$r[$p['productos_id']]['descuento_distribuidor'] = $p['descuento_distribuidor'];
    		$r[$p['productos_id']]['importe_cliente'] = $p['importe_cliente'];
            $r[$p['productos_id']]['importe_distribuidor'] = $p['importe_distribuidor'];
    		$r[$p['productos_id']]['img_id']=$CI->Producto->get_imagen_principal($p['productos_id']);
    		//Agregamos los accesorios
    		$acc=$this->get_accesorios_productos($p['productos_id'],$cotizaciones_id,TRUE);
    		foreach( $acc as $a)
            {
                $a['unidad'] = elemento('Unidades',$a['unidad_id']);
    			$r[$p['productos_id']]['accesorios'][$a['accesorios_id']] =$a;
            }
    	}
    	return $r;
    }

    public function get_regalos($cotizaciones_id)
    {
        $regalos_productos = $this->get_productos_cotizacion($cotizaciones_id,TRUE);
        $regalos_accesorios = $this->Accesorio->get_accesorios_individuales($cotizaciones_id, FALSE, TRUE);

        $regalos=array();
        foreach($regalos_productos as $productoi_id=>$producto)
        {
            $r=$producto;
            $r['path']=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/productos/{$r['id']}/{$r['img_id']}.jpg"):FCPATH."files/productos/{$r['id']}/{$r['img_id']}.jpg";
            $regalos[] = $r;
        }

        foreach($regalos_accesorios as $accesorio_id=>$accesorio)
        {
            $r=$accesorio;
            $orden = $r->imagen_orden?'_'.$r->imagen_orden:'';
            $r->path=$this->config->item('cloudfiles')?$this->cloud_files->img_tmp("files/accesorios/{$r->accesorios_id}{$orden}.jpg"):FCPATH."files/accesorios/{$r->accesorios_id}.jpg";
            $regalos[]=(array)$accesorio;
        }

        return $regalos;

    }

    public function get_cotizacion_alianzas($cotizaciones_id)
    {
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('eliminado',0);
        $alianzas=$this->db->get('cotizaciones_alianzas')->result_array();

        return $alianzas;
    }
    
    public function imprimir($cotizaciones_id, $output='I',$output_path=FALSE,$status_id=FALSE)
    {
    	//GENERA IMPRESION DE UNA COTIZACIÓN O DE UNA ORDEN DE COMPRA

        $datos['r'] = $this->get_cotizacion($cotizaciones_id);
        $datos['venta_directa'] = $venta_directa = $this->es_venta_directa($datos['r']['cotizacion']['cuentas_id']);
        $datos['impresion'] = $puede_imprimir = $this->puede_imprimir($cotizaciones_id, $venta_directa);
        if(!$this->session->userdata('cliente_externo') && (!$puede_imprimir['puede_imprimir'] || !$this->base->tiene_permiso('cotizaciones_imprimir')))
        {
            $this->session->set_flashdata('error', 'Su usuario no tiene los permisos necesarios para acceder a dicha funci&oacute;n.');
            redirect('cotizaciones/index');
        }

        if(!$status_id)
        	$status_id = $this->get_status($cotizaciones_id);

        if ($status_id == 1 || $status_id==7) // COTIZACIÓN
        {
            $view = 'cotizacion';
            $this->load->library("pdf/cotizacion_pdf");
            $pdf = new cotizacion_pdf('P', 'mm', 'LETTER', true, 'UTF-8');
        }
        
        if ($status_id == 2 || $status_id == 3 || $status_id == 4 || $status_id==5) // ORDEN DE COMPRA
        {
            $view = 'orden_compra';
            $this->load->library("pdf/orden_compra_pdf");
            $pdf = new orden_compra_pdf('P', 'mm', 'LETTER', true, 'UTF-8');
        }
        
        $CI =&get_instance();
        $CI->load->model('Descuento');
        $CI->load->model('Accesorio');
        $CI->load->model('Producto');
        $this->load->library('CNumeroaLetra');
        $datos ['letanum'] = new CNumeroaLetra();

        $datos['cuenta']= $this->base->read('cuentas', $datos['r']['cotizacion']['cuentas_id']);
        $datos['productos'] = $this->get_productos_cotizacion($cotizaciones_id);//debug($datos['productos']);

        $producto_regalo_id = $datos['r']['cotizacion']['producto_regalo_id'];
        if(!empty($producto_regalo_id))
        {
            $datos['cupones_regalos'][$producto_regalo_id] = parent::read('productos',$producto_regalo_id,TRUE);
            $datos['cupones_regalos'][$producto_regalo_id]['cantidad'] = 1;
            $datos['cupones_regalos'][$producto_regalo_id]['unidad'] = elemento('Unidades',$datos['cupones_regalos'][$producto_regalo_id]['unidad_id']);
            $datos['cupones_regalos'][$producto_regalo_id]['precio'] = $this->base->get_dato('precio','productos',array('id'=>$producto_regalo_id));
            $datos['cupones_regalos'][$producto_regalo_id]['descuento_cliente'] = 100;
            $datos['cupones_regalos'][$producto_regalo_id]['descuento_distribuidor'] = 100;
            $datos['cupones_regalos'][$producto_regalo_id]['importe_cliente'] = 0;
            $datos['cupones_regalos'][$producto_regalo_id]['importe_distribuidor'] = 0;
            $datos['cupones_regalos'][$producto_regalo_id]['img_id']=$CI->Producto->get_imagen_principal($producto_regalo_id);
        }

        $datos['accesorios_individuales'] = $this->Accesorio->get_accesorios_individuales($cotizaciones_id);
        if($datos['r']['cotizacion']['promociones_id'])
        {
            $datos['regalos'] = $this->get_regalos($cotizaciones_id);
            $datos['alianzas'] = $this->get_cotizacion_alianzas($cotizaciones_id);
        }
        // PDF DATA
        $pdf->SetCreator($this->config->item('proyecto'));
        $pdf->SetAuthor($this->config->item('autor'));
        $pdf->SetTitle($datos['r']['cotizacion']['folio_cuentas'].'_'.$view);
        
        $datos ['pdf'] = $pdf;
        //Obtenemos el numero  de clases otorgado por cada $100,000 es una clase;
        $datos['num_clases'] = FALSE;
        if(($venta_directa && $datos['r']['cotizacion']['status_id'] == 1) || $datos['r']['cotizacion']['status_id'] == 1)
        {
	       //$num_clases =$valores['total_cliente']>100000;//intval($valores['total_cliente']);
	       	if($datos['r']['cotizacion']['total_cliente']>100000)
       			$datos['num_clases'] =TRUE;
        }

        if($output_path)
        {
            $folio = $datos['r']['cotizacion']['folio_cuentas']?$datos['r']['cotizacion']['folio_cuentas']:$datos['r']['cotizacion']['folio_compra'];
            $path = APPPATH . "files/cotizaciones_pdfs/{$folio}.pdf";
        }
        else
        {
            $nombre=($datos['r']['cotizacion']['folio_compra']?$datos['r']['cotizacion']['folio_compra']:$datos['r']['cotizacion']['folio_cuentas']);
            $path = TMPPATH.'/'.$nombre.'.pdf';
            $output = 'F';
        }
        //$path = $datos['r']['cotizacion']['folio_compra']?$datos['r']['cotizacion']['folio_compra']:$datos['r']['cotizacion']['folio'];
        
        $datos ['id'] = $cotizaciones_id;
     	$datos['accs'] = $this->base->lista('productos_categorias','id','nombre',FALSE);
     	$this->load->view("pdfs/{$view}", $datos);

        $pdf->Output($path,$output);

        if(!$output_path)
        {
            // MOSTRAR A USUARIO PARA QUE SALGA CON LA EXTENSION QUE DEBE
            header('Content-type: application/pdf');
            header("Content-Disposition: filename='{$nombre}.pdf'");
            readfile($path);
            exit;
        }
    }

    public function get_status($cotizaciones_id)
    {
        $this->db->select('status_id');
        $this->db->where('id', $cotizaciones_id);
        $status_id = $this->db->get('cotizaciones')->row()->status_id;
        
        return $status_id;
    }

    public function cambia_status($cotizaciones_id, $siguiente)
    {
        $status_id = $siguiente;
        $data = array ();
        $data ['status_id'] = $status_id;
        $data ['id'] = $cotizaciones_id;
        $this->db->where('id', $cotizaciones_id);
        $this->base->guarda('cotizaciones', $data);

        if(in_array($status_id, array(2,5)))//if($status_id!=1 && $status_id!=4)
            $this->mail_cambio_status($cotizaciones_id, $status_id);

        return TRUE;
    }
    

    public function get_accesorios_cotizacion($cotizaciones_id)
    {
        $this->db->where('cotizaciones_id', $cotizaciones_id);
        $this->db->where('eliminado', 0);
        $this->db->from('cotizaciones_accesorios');
        $r = $this->db->get()->result_object();
        foreach($r as &$a )
            $a->detalle = $this->read('accesorios', $a->accesorios_id);
        return $r;
    }

    public function get_datos_accesorios($accesorios_id)
    {
        $this->db->where('id', $accesorios_id);
        $r = $this->db->get('accesorios')->row_array();
        
        return $r;
    }

    public function guarda_documentacion($cotizaciones_id, $files)
    {
        if (!empty($files ['recibo_pago']['name']))
        {
        	if(!$this->config->item('cloudfiles'))
        	{
                    if(file_exists(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/recibo_pago.pdf'))
	        	unlink(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/recibo_pago.pdf');
                    if(file_exists(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/recibo_pago.jpg'))
	        	unlink(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/recibo_pago.jpg');
        	}
        	
        	$orden='';
//        	if($this->config->item('cloudfiles'))
//        	{
        		$cotizacion = $this->base->read('cotizaciones',$cotizaciones_id);
        		$cotizacion->recibo_pago_orden++;
        		$orden = $cotizacion->recibo_pago_orden?'_'.$cotizacion->recibo_pago_orden:'';
//        	}
        	
//            $extension_recibo = explode(".", $files ['recibo_pago'] ['name']);
            $extension_recibo = pathinfo($files ['recibo_pago'] ['name'], PATHINFO_EXTENSION);
            $recibo_destino = "files/cotizaciones/$cotizaciones_id/recibo_pago{$orden}.$extension_recibo";             
            $guarda_recibo=FALSE;
            if ($extension_recibo ['1'] == 'pdf' || $extension_recibo ['1'] == 'PDF')
                $guarda_recibo = $this->base->guarda_archivo($files ['recibo_pago'] ['tmp_name'], 'files/cotizaciones/' . $cotizaciones_id, "recibo_pago{$orden}.pdf", FALSE, TRUE);
            else
                $guarda_recibo = $this->base->guarda_imagen($files ['recibo_pago'] ['tmp_name'], 'files/cotizaciones/' . $cotizaciones_id, "recibo_pago{$orden}", FALSE, NULL, $extension_recibo);
            
            if($guarda_recibo)
            {
            	$data=array(
            		'id'=>$cotizaciones_id,
            		'recibo_pago_cdn'=>$recibo_destino,
            		'recibo_pago_orden'=>$cotizacion->recibo_pago_orden
            	);
            	$this->base->guarda('cotizaciones',$data);
            }
        }
        
        if (!empty($files ['orden_firmada']['name']))
        {
        	if(!$this->config->item('cloudfiles'))
        	{
                    if(file_exists(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/orden_firmada.pdf'))
	        	unlink(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/orden_firmada.pdf');
                    if(file_exists(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/orden_firmada.jpg'))
	        	unlink(FCPATH.'files/cotizaciones/'.$cotizaciones_id.'/orden_firmada.jpg');
        	}
        	
        	$orden='';
//        	if($this->config->item('cloudfiles'))
//        	{
        		$cotizacion = $this->base->read('cotizaciones',$cotizaciones_id);
        		$cotizacion->orden_firmada_orden++;
        		$orden = $cotizacion->orden_firmada_orden?'_'.$cotizacion->orden_firmada_orden:'';
//        	}
        	
//            $extension_orden_firmada = explode(".", $files ['orden_firmada'] ['name']);
            $extension_orden_firmada = pathinfo($files ['orden_firmada'] ['name'], PATHINFO_EXTENSION);
            $guarda_orden_firmada=FALSE;
            $orden_firmada_destino = "files/cotizaciones/$cotizaciones_id/orden_firmada{$orden}.$extension_orden_firmada";
            if ($extension_orden_firmada ['1'] == 'pdf' || $extension_orden_firmada ['1'] == 'PDF')
                $guarda_orden_firmada = $this->base->guarda_archivo($files ['orden_firmada'] ['tmp_name'], 'files/cotizaciones/' . $cotizaciones_id, "orden_firmada{$orden}.pdf", FALSE, TRUE);
            else
                $guarda_orden_firmada = $this->base->guarda_imagen($files ['orden_firmada'] ['tmp_name'], 'files/cotizaciones/' . $cotizaciones_id, "orden_firmada{$orden}", FALSE, NULL, $extension_orden_firmada);
            
        	if($guarda_orden_firmada)
            {
            	$data=array(
            		'id'=>$cotizaciones_id,
            		'orden_firmada_cdn'=>$orden_firmada_destino,
            		'orden_firmada_orden'=>$cotizacion->orden_firmada_orden
            	);
            	$this->base->guarda('cotizaciones',$data);
            }
            
        }
        return TRUE;
    }

    public function mail_compra($cotizaciones_id, $status_id, $venta_directa=FALSE)
    {
        $this->db->select('cuentas_id,folio_compra');
        $this->db->where('id', $cotizaciones_id);
        $r = $this->db->get('cotizaciones')->row();
        $datos ['cuenta'] = $this->base->read('cuentas', $r->cuentas_id);
        $datos ['folio_compra'] = $r->folio_compra;
        $this->load->library('email');
        $email_sax = $this->config->item('email');
        $this->email->from($email_sax [0], $email_sax [1]);

        $email=$venta_directa?$this->config->item('mail_compra_venta_directa'):$this->config->item('mail_compra_venta_distribuidor');

        $this->email->to($email);
        
        $bcc = $this->config->item('mail_bcc');
        if (!empty($bcc))
            $this->email->bcc($bcc);
        
        if($status_id==4)
        {
        	$extensiones=array('jpg','pdf');
        	
        	foreach($extensiones as $e)
        	{
        		$orden_firmada=FCPATH."files/cotizaciones/{$cotizaciones_id}/orden_firmada.{$e}";
	        	if(file_exists($orden_firmada))
    	    		$this->email->attach($orden_firmada);
        	
        		$recibo_pago=FCPATH."files/cotizaciones/{$cotizaciones_id}/recibo_pago.{$e}";
        		if(file_exists($recibo_pago))
        			$this->email->attach($recibo_pago);
        	}
        }
        
        $this->email->subject('Compra nueva');
        $mensaje = $this->load->view('email/cambio_status', $datos, true);
        $this->email->message($mensaje);
        $this->email->send();
        if ($this->config->item('debug_mail')) // DEBUG DE MAIL
        {
            debug($this->email->print_debugger(), null, 1);
        }

        if($this->config->item('calificar_miele_partners'))
        {
            $this->load->Model('Calificacion');
            $this->db->where('id', $cotizaciones_id);
            $cotizacion = $this->db->get('cotizaciones')->row();

            $this->Calificacion->set_calificacion_mail_productos($cotizacion);
        }

        return TRUE;
    }

    public function recupera_productos($datos)
    {
        $CI = & get_instance();
        $CI->load->model('Producto');
        
        $r = array ();
        foreach ( $datos as $k => $v )
        {
            // OBTIENE LOS DATOS DEL PRODUCTO
            $r [$k] ['producto'] = parent::read('productos', $v ['productos_id']);
            $r [$k] ['producto']->cantidad = $v ['cantidad'];
            $r [$k] ['producto']->img_id = $CI->Producto->get_imagen_principal($v ['productos_id']);
            
            // OBTIENE LOS TIPOS DE ACCESORIO DEL PRODUCTO
            $tipos_accesorios = $CI->Producto->get_accesorios_producto($v ['productos_id']);
            if (isset($v ['tipo_accesorios_id']))
            {
                // ACCESORIO SELECCIONADO
                $sel = array ();
                foreach ( $v ['tipo_accesorios_id'] as $kta => $ta )
                {
                    $sel [$ta] = $v ['accesorio_seleccionado_id'] [$kta];
                }
                
                $i = 0;
                
                foreach ( $tipos_accesorios as &$a )
                {
                    $a->cantidad = $v ['accesorio_cantidad'] [$i];
                    $a->seleccionado = NULL;
                    if (!empty($sel [$a->tipos_accesorios_id]))
                        $a->seleccionado = parent::read('accesorios', $sel [$a->tipos_accesorios_id]);
                    $i ++;
                }
                
                $r [$k] ['tipos_accesorios'] = $tipos_accesorios;
            }
        }
        
        return $r;
    }

    public function get_precio_producto($tabla, $productos_id)
    {
        $this->db->select('precio');
        $this->db->where('id', $productos_id);
        $precio = $this->db->get($tabla)->row()->precio;
        return $precio;
    }

    public function guarda_productos($productos, $cotizaciones_id)
    {
        $subtotal = 0;
        foreach ( $productos as $ts => $producto )
        {
            $data = array ();
            $data ['timestamp'] = $ts;
            $data ['productos_id'] = $producto ['productos_id'];
            $data ['cantidad'] = $producto ['cantidad'];
            $data ['cotizaciones_id'] = $cotizaciones_id;
            $cotizaciones_productos = parent::guarda('cotizaciones_productos', $data);
            $precio_p = $this->get_precio_producto('productos', $producto ['productos_id']);
            $precio_p = $precio_p * $producto ['cantidad'];
            $subtotal += $precio_p;
            if (!empty($producto ['tipo_accesorios_id']))
            {
                foreach ( $producto ['tipo_accesorios_id'] as $k => $v )
                {
                    $accesorio = array ();
                    $accesorio ['timestamp'] = $ts;
                    $accesorio ['productos_id'] = $producto ['productos_id'];
                    $accesorio ['tipos_accesorios_id'] = $producto ['tipo_accesorios_id'] [$k];
                    $accesorio ['accesorios_id'] = $producto ['accesorio_seleccionado_id'] [$k];
                    $accesorio ['cantidad'] = $producto ['accesorio_cantidad'] [$k];
                    $accesorio ['cotizaciones_id'] = $cotizaciones_id;
                    $accesorio ['cotizaciones_productos_id'] = $cotizaciones_productos;
                    $precio_a = $this->get_precio_producto('accesorios', $producto ['accesorio_seleccionado_id'] [$k]);
                    $precio_a = $precio_a * $producto ['accesorio_cantidad'] [$k];
                    $subtotal = $subtotal + $precio_a;
                    parent::guarda('cotizaciones_accesorios', $accesorio);
                }
            }
        }
        
        return TRUE;
    }

    public function get_cotizacion_producto($cotizaciones_id, $productos_id, $recalcular=FALSE)
    {
        $this->db->where('cotizaciones_id', $cotizaciones_id);
        $this->db->where('productos_id', $productos_id);
        $this->db->where('eliminado', 0);
        $this->db->where('(promocion IS NULL OR promocion=0)');
        $this->db->order_by('id', 'DESC');
        $res = $this->db->get('cotizaciones_productos')->row();

        if($recalcular)
        	$res->precio = $this->base->get_dato('precio','productos',array('id'=>$res->productos_id));

        return $res;
    }

    public function get_cotizacion_accesorios($cotizaciones_id, $productos_id,$array=FALSE, $recalcular=FALSE)
    {
        $this->db->where('cotizaciones_id', $cotizaciones_id);
        $this->db->where('productos_id', $productos_id);
        $this->db->where('eliminado', 0);
        $this->db->where('(promocion IS NULL OR promocion=0)');
        $res='';

        if($array)
        {
        	$res=$this->db->get('cotizaciones_accesorios')->result_array();
        	if($recalcular)
        	{
        		foreach ($res as $k=>$v)
                {
                    $accesorio = $this->base->get_datos('precio, unidad_id','accesorios',array('id'=>$res[$k]['accesorios_id']));
        			$res[$k]['precio'] = $accesorio->precio;
        			$res[$k]['unidad_id'] = $accesorio->unidad_id;
                }
        	}
        }
        else
        {
        	$res=$this->db->get('cotizaciones_accesorios')->result();
        	if($recalcular)
        	{
        		foreach ($res as $k=>$v)
                {
                    $accesorio = $this->base->get_datos('precio, unidad_id','accesorios',array('id'=>$res[$k]->accesorios_id));
        			$res[$k]->precio = $accesorio->precio;
        			$res[$k]->unidad_id = $accesorio->unidad_id;
                }
        	}
        }

        return $res;
    }

    public function get_accesorio($accesorios_id)
    {
        $this->db->where('id', $accesorios_id);
        $accesorio = $this->db->get('accesorios')->row();
        
        return $accesorio;
    }

    public function accesorio_obligatorio($productos_id,$tipos_accesorios_id)
    {
    	$this->db->select('obligatorio_id');
    	$this->db->where('productos_id', $productos_id);
    	$this->db->where('tipos_accesorios_id', $tipos_accesorios_id);
    	$this->db->where('eliminado',0);
    	$this->db->order_by('id','DESC');
    	$obligatorio = $this->db->get('productos_tipos_accesorios')->row('obligatorio_id');
    
    	return $obligatorio;
    }
    
    public function ibs_agregar($cotizaciones_id)
    {
    	$data['id']=$cotizaciones_id;
    	$data['ibs']=$_POST['ibs'];
    	$res=$this->base->guarda('cotizaciones',$data);
    	
    	return $res;
    }

    public function mail_cambio_status($cotizaciones_id, $status_id)
    {
        $this->db->select('cuentas_id,folio_compra, usuario_id');
        $this->db->where('id', $cotizaciones_id);
        $r = $this->db->get('cotizaciones')->row();

        $datos ['cuenta'] = $this->base->read('cuentas', $r->cuentas_id);
        $datos ['folio_compra'] = $r->folio_compra;
        $datos ['cambio_status'] = TRUE;
        $status=$this->status;
        $datos ['status'] = $status[$status_id];

        $this->load->library('email');
        $email_sax = $this->config->item('email');
        $this->email->from($email_sax [0], $email_sax [1]);
        if($status_id==2)
            $email=$this->base->get_dato('email','usuarios',array('id'=>$r->usuario_id));//VENDEDOR
        else
            $email=$this->base->get_dato('email','usuarios',array('id'=>$this->session->userdata('id')));//REVISOR

        $this->email->to($email);

        $bcc = $this->config->item('mail_bcc');
        if (!empty($bcc))
            $this->email->bcc($bcc);

        $this->email->subject('Cambio Status - '.$r->folio_compra);
        $mensaje = $this->load->view('email/cambio_status', $datos, true);
        $this->email->message($mensaje);
        $this->email->send();
        if ($this->config->item('debug_mail')) // DEBUG DE MAIL
        {
            debug($this->email->print_debugger(), null, 1);
        }

        return TRUE;
    }

    public function regalos_get($cotizaciones_id)
    {
        $this->db->select('productos_id');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('promocion',1);
        $this->db->where('eliminado',0);
        $promociones_productos_ids = $this->db->get('cotizaciones_productos')->result();

        $this->db->select('accesorios_id');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('promocion',1);
        $this->db->where('consumible',0);
        $this->db->where('eliminado',0);
        $promociones_accesorios_ids = $this->db->get('cotizaciones_accesorios')->result();

        $this->db->select('accesorios_id');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('promocion',1);
        $this->db->where('consumible',1);
        $this->db->where('eliminado',0);
        $promociones_consumibles_ids = $this->db->get('cotizaciones_accesorios')->result();

        $this->db->select('alianzas_id');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('eliminado',0);
        $promociones_alianzas_ids = $this->db->get('cotizaciones_alianzas')->result();

        $data=array();
        if($promociones_productos_ids)
        {
            $data['productos']=array();
            $i=0;
            foreach($promociones_productos_ids as $p)
            {
                $this->db->select('fp.id as foto_id');
                $this->db->where('p.id',$p->productos_id);
                $this->db->where('fp.tipos_id',1);
                $this->db->where('fp.eliminado',0);
                $this->db->join('fotografias_productos as fp', 'fp.productos_id=p.id','left');
                $this->db->limit(1);
                $foto_id=$this->db->get('productos as p')->row('foto_id');

                $data['productos'][$i]=$this->base->read('productos', $p->productos_id, TRUE);
                $data['productos'][$i]['foto_id']=$foto_id?$foto_id:FALSE;
                $i++;
            }
        }

        if($promociones_accesorios_ids)
        {
            $data['accesorios']=array();
            foreach($promociones_accesorios_ids as $acc)
            {
                $data['accesorios'][]=$this->base->read('accesorios', $acc->accesorios_id, TRUE);
            }
        }

        if($promociones_consumibles_ids)
        {
            $data['consumibles']=array();
            foreach($promociones_consumibles_ids as $c)
            {
                $data['consumibles'][]=$this->base->read('accesorios', $c->accesorios_id, TRUE);
            }
        }

        if($promociones_alianzas_ids)
        {
            $data['alianzas']=array();
            foreach($promociones_alianzas_ids as $a)
            {
                $data['alianzas'][]['id']=$a->alianzas_id;
            }
        }

        $msi = $this->base->get_dato('promocion_msi','cotizaciones',array('id'=>$cotizaciones_id));
        if($msi)
            $data['msi'] = $msi;

        $descuento_porcentaje = $this->base->get_dato('promocion_fija','cotizaciones',array('id'=>$cotizaciones_id));
        $descuento_monto = $this->base->get_dato('promocion_porcentaje','cotizaciones',array('id'=>$cotizaciones_id));

        $descuento=array();
        if($descuento_porcentaje)
            $descuento['porcentaje']= $descuento_porcentaje;

        if($descuento_monto)
            $descuento['monto']= $descuento_monto;

        if(!empty($descuento))
        $data['descuento']=$descuento;

        return $data;
    }

    public function get_promociones_alianzas($cotizaciones_id)
    {
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('eliminado',0);
        $alianzas = $this->db->get('cotizaciones_alianzas')->result_array();

        return $alianzas;
    }

    public function alianza_codigo_get($cotizaciones_id, $alianzas_id)
    {
        $this->db->select('codigo');
        $this->db->where('cotizaciones_id',$cotizaciones_id);
        $this->db->where('eliminado',0);
        $this->db->where('alianzas_id',$alianzas_id);
        $this->db->limit(1);
        $codigo = $this->db->get('cotizaciones_alianzas')->row('codigo');

        return $codigo;
    }

    public function productos_total_get($cotizaciones_id)
    {
        // OBTIENE LOS IDS DE LOS PRODUCTOS DE UNA COTIZACION
        $this->db->select('SUM(cp.cantidad) as productos_total');
        $this->db->from('cotizaciones_productos as cp');
        $this->db->join('productos as p','cp.productos_id=p.id','left outer');
        $this->db->where('cp.cotizaciones_id', $cotizaciones_id);
        $this->db->where('p.unidad_id', 1);
        $this->db->where('cp.eliminado', 0);

        $r = $this->db->get()->row();

        return $r->productos_total;
    }

    public function exportar($cond)
    {
        $data = $this->find($cond,null,null);

        /** Generar Excel */
        require_once(APPPATH . 'libraries/Excel/PHPExcel.php');
        require_once(APPPATH . 'libraries/Excel/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator('Blackcore')
            ->setLastModifiedBy('Blackcore')
            ->setTitle('Cotizaciones')
            ->setSubject('Cotizaciones')
            ->setDescription('Cotizaciones')
            ->setKeywords('Cotizaciones')
            ->setCategory('Cotizaciones');

        $columnas = array(
            array(
                'columna' => 'A',
                'titulo' => 'Id',
                'campo' => 'id',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'B',
                'titulo' => 'Folio cotización',
                'campo' => 'folio_cuentas',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'C',
                'titulo' => 'Folio orden de compra',
                'campo' => 'folio_compra',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'D',
                'titulo' => 'Cuenta',
                'campo' => 'cuenta',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'E',
                'titulo' => 'Cliente',
                'campo' => 'cliente_nombre_completo',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'F',
                'titulo' => 'Fecha cotización',
                'campo' => 'fecha',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'G',
                'titulo' => 'Vendedor',
                'campo' => 'vendedor_nombre_completo',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'H',
                'titulo' => 'Referenciado por',
                'campo' => 'referido',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'I',
                'titulo' => 'Valor',
                'campo' => 'valor',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'J',
                'titulo' => 'Número de productos',
                'campo' => 'productos_total',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'K',
                'titulo' => 'Ofreció evento',
                'campo' => 'ofrecio_evento',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'L',
                'titulo' => 'Status',
                'campo' => 'status',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'L',
                'titulo' => 'Status Pago',
                'campo' => 'status_pago',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'M',
                'titulo' => 'Orden de Venta IBS',
                'campo' => 'ibs',
                'encabezado' => 'rojo_miele',
            ),
        );

        $tipo_encabezado = array(
            'rojo_miele' => array(
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '8c0014')),
                'font' => array('size' => 11, 'color' => array('rgb' => 'FFFFFF')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            )
        );

        /** Encabezado */
        foreach ($columnas as $c)
        {
            if (isset($tipo_encabezado[$c['encabezado']]))
            {
                $objPHPExcel->getActiveSheet()->getStyle($c['columna'] . '1')->applyFromArray(($tipo_encabezado[$c['encabezado']]));
            }
            $objPHPExcel->getActiveSheet()->setCellValue($c['columna'] . '1', $c['titulo']);
            $objPHPExcel->getActiveSheet()->setCellValue($c['columna'] . '1', $c['titulo']);
        }

        /** Contenido */
        if (!empty($data))
        {
            $row = 1;
            foreach ($data as $d)
            {
                $row++;
                foreach ($columnas as $c)
                {
                    $celda = $c['columna'] . $row;
                    if (isset($c['campo']) && $c['campo'] != '')
                    {
                        $celda_valor = $d->{$c['campo']};
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($celda, $celda_valor, PHPExcel_Cell_DataType::TYPE_STRING);

                    } elseif (isset($c['texto']) && $c['texto'] != '')
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($celda, $c['texto'], PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }
        } else
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'No existen datos')->mergeCells('A2:M2');

        /** Ajuste */
        foreach ($columnas as $c)
            $objPHPExcel->getActiveSheet()->getColumnDimension($c['columna'])->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->setSelectedCell('A2');

        /** Descarga Excel **/
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename=Cotizaciones.xlsx');
        header('Content-Transfer-Encoding: binary');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;
    }

    public function reporte($cond)
    {
        $cond['status'] = 4;
        $data = $this->find($cond,null,null);

        /** Generar Excel */
        require_once(APPPATH . 'libraries/Excel/PHPExcel.php');
        require_once(APPPATH . 'libraries/Excel/PHPExcel/IOFactory.php');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator('Blackcore')
            ->setLastModifiedBy('Blackcore')
            ->setTitle('OV_Autorizadas')
            ->setSubject('OV_Autorizadas')
            ->setDescription('OV_Autorizadas')
            ->setKeywords('OV_Autorizadas')
            ->setCategory('OV_Autorizadas');

        $columnas = array(
            array(
                'columna' => 'A',
                'titulo' => 'Id',
                'campo' => 'id',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'B',
                'titulo' => 'Nombre del cliente',
                'campo' => 'cliente_nombre_completo',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'C',
                'titulo' => 'Dirección de facturación',
                'campo' => 'cliente_direccion',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'D',
                'titulo' => 'Dirección de entrega',
                'campo' => 'cliente_direccion_entrega',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'E',
                'titulo' => 'Dirección de instalación',
                'campo' => 'cliente_direccion_instalacion',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'F',
                'titulo' => 'Teléfono del cliente',
                'campo' => 'cliente_telefono',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'G',
                'titulo' => 'Correo electrónico del cliente',
                'campo' => 'cliente_email',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'H',
                'titulo' => 'Número de orden',
                'campo' => 'folio_compra',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'I',
                'titulo' => 'IBS',
                'campo' => 'ibs',
                'encabezado' => 'rojo_miele',
            ),
            array(
                'columna' => 'J',
                'titulo' => 'Monto comprado',
                'campo' => 'valor',
                'encabezado' => 'rojo_miele',
            ),
        );

        $tipo_encabezado = array(
            'rojo_miele' => array(
                'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '8c0014')),
                'font' => array('size' => 11, 'color' => array('rgb' => 'FFFFFF')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            )
        );

        /** Encabezado */
        foreach ($columnas as $c)
        {
            if (isset($tipo_encabezado[$c['encabezado']]))
            {
                $objPHPExcel->getActiveSheet()->getStyle($c['columna'] . '1')->applyFromArray(($tipo_encabezado[$c['encabezado']]));
            }
            $objPHPExcel->getActiveSheet()->setCellValue($c['columna'] . '1', $c['titulo']);
            $objPHPExcel->getActiveSheet()->setCellValue($c['columna'] . '1', $c['titulo']);
        }

        /** Contenido */
        if (!empty($data))
        {
            $row = 1;
            foreach ($data as $d)
            {
                $row++;
                foreach ($columnas as $c)
                {
                    $celda = $c['columna'] . $row;
                    if (isset($c['campo']) && $c['campo'] != '')
                    {
                        $celda_valor = $d->{$c['campo']};
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($celda, $celda_valor, PHPExcel_Cell_DataType::TYPE_STRING);

                    } elseif (isset($c['texto']) && $c['texto'] != '')
                        $objPHPExcel->getActiveSheet()->setCellValueExplicit($celda, $c['texto'], PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }
        } else
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'No existen datos')->mergeCells('A2:M2');

        /** Ajuste */
        foreach ($columnas as $c)
            $objPHPExcel->getActiveSheet()->getColumnDimension($c['columna'])->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->setSelectedCell('A2');

        /** Descarga Excel **/
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename=OV_Autorizadas.xlsx');
        header('Content-Transfer-Encoding: binary');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
        exit;
    }

    public function direccion_formato_get($estado, $municipio, $asentamiento, $codigo_postal, $calle, $numero_exterior, $numero_interior)
    {
        $direccion = '';
        if(!empty($calle))
            $direccion.= 'CALLE: '.$calle.', ';
        if(!empty($numero_exterior))
            $direccion.= 'NÚMERO EXTERIOR: '.$numero_exterior.', ';
        if(!empty($numero_interior))
            $direccion.= 'NÚMERO INTERIOR: '.$numero_interior.', ';
        if(!empty($asentamiento))
            $direccion.= 'COLONIA: '.$asentamiento.', ';
        if(!empty($codigo_postal))
            $direccion.= 'C.P: '.$codigo_postal.', ';
        if(!empty($municipio))
            $direccion.= 'MUNICIPIO: '.$municipio.', ';
        if(!empty($estado))
            $direccion.= 'ESTADO: '.$estado.'.';

        return $direccion;
    }

}
