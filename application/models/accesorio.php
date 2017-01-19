<?php
require_once('base.php');
class Accesorio extends Base {

	var $table='accesorios';
	var $acciones_fotos=array(
		1=>'Eliminar'
	);

	public function __construct()
	{
		parent::__construct();
	}

	private function pconditions($cond)
    {

    	if(!empty($cond['nombre']))
    		$this->db->like('nombre',$cond['nombre']);

    	if(!empty($cond['item']))
    		$this->db->like('item',$cond['item']);

    	if(!empty($cond['tipos_accesorios_id']))
    		$this->db->where('tipos_accesorios_id',$cond['tipos_accesorios_id']);

    	if(!empty($cond['modelo']))
    		$this->db->like('modelo',$cond['modelo']);

    	if(isset($cond['consumible']))
    		$this->db->where('consumible',$cond['consumible']);

    	// CONDICION PARA TRAER VARIOS POR ID (UTILIZADA EN COTIZACIONES)
    	if(isset($cond['ids']) && !empty($cond['ids']))
    		$this->db->where_in('p.id',$cond['ids']);

    	// CONDICION NO TRAER LOS QUE ESTAN EN LA SESION (UTILIZADA EN PAGOS Y EN FACTURACION)
    	if(isset($cond['no_ids']) && !empty($cond['no_ids']))
    		$this->db->where_not_in('p.id',$cond['no_ids']);
  	 	if(!empty($cond['activo']))
    	{
    		if($cond['activo']==1)
    			$this->db->where('activo',1);
    		if($cond['activo']==2)
    			$this->db->where('activo',0);
    	}
    }

    public function find($cond,$limit,$offset)
    {
    	$this->pconditions($cond);
		$this->db->where('eliminado',0);
		$this->db->from('accesorios');
    	$this->db->order_by('id','DESC');

		if($limit)
			$this->db->limit($limit,$offset);

		$r=$this->db->get()->result();

	/*
		foreach($r as &$p)
		{
			$p->img_id=$this->get_imagen_principal($p->id);
		}
	*/
    	return $r;
    }

    public function count($conditions)
    {
    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('accesorios');
    	$r=$this->db->count_all_results();

    	return $r;
    }

 	public function get_fotografias($accesorios_id)
    {
    	$this->db->where('eliminado',0);
    	$this->db->where('accesorios_id',$accesorios_id);
    	$this->db->order_by('orden','ASC');
    	$q=$this->db->get('fotografias_accesorios')->result_object();
    	return $q;
    }

	function agregar_foto($accesorios_id,$file,$es_imagen)
    {
    	$this->db->where('accesorios_id',$accesorios_id);
    	$this->db->select('orden');
    	$this->db->order_by('orden','DESC');
    	$q=$this->db->get('fotografias_accesorios',1)->row();

    	$orden=1;
    	if(!empty($q))
    		$orden=$q->orden+1;
    	$data['orden']=$orden;
    	$data['accesorios_id']=$accesorios_id;

    	if($es_imagen)
    		$extension='jpg';
    	else
    		$extension='pdf';

    	$data['extension']=$extension;
    	$id=$this->guarda('fotografias_accesorios',$data);

  		$guarda=$this->guarda_imagen($file,'files/accesorios/'.$accesorios_id,$id,$this->config->item('avaluo_image_size'));


    	if(!$guarda)
    	{
    		$data=array();
    		$this->db->where('id',$id);
			$data['eliminado']=1;
			$this->db->update('accesorios_fotos',$data);
			return FALSE;
    	}else{
    	  	 return $id;
    	}
    }


	public function get_acciones_fotos()
    {
    	$acciones_fotos=$this->acciones_fotos;
    	return $acciones_fotos;
    }

 	public function fotos_eliminar($id)
    {
    	$data['id']=$id;
    	$data['eliminado']=1;
    	return parent::guarda('fotografias_accesorios', $data);
    }

    public function get_imagen_principal($accesorios_id)
    {
    	$this->db->where('accesorios_id',$accesorios_id);
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$this->db->where('tipos_id',1);
    	$this->db->order_by('orden','ASC');
    	$this->db->select('id');
    	$this->db->limit(1);
    	$r=$this->db->get('fotografias_accesorios')->row();
    	if(!empty($r))
    		return $r->id;
    	return FALSE;
    }

    public function get_productos($tipos_accesorios_id,$limit,$page)
    {
    	if(!$limit)
    		$limit=$this->config->item('front_por_pagina');
    	if(!$page)
    		$page=1;

    	$this->db->limit($limit,($page-1)*$limit);
    	$this->db->select('id, modelo, tipos_accesorios_id, precio');
    	$this->db->where('tipos_accesorios_id',$tipos_accesorios_id);
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$this->db->order_by('id','DESC');
    	$r=$this->db->get('accesorios')->result();
    	foreach($r as &$p)
    	{
    		$p->img_id=$this->get_imagen_principal($p->id);
    	}
    	return $r;
    }

    public function get_accesorios($tipos_accesorios_id=FALSE)
    {
    	if($tipos_accesorios_id)
    		$this->db->where('tipos_accesorios_id',$tipos_accesorios_id);
    	$this->db->where('eliminado',0);
        $this->db->order_by('id','DESC');
    	$r=$this->db->get('accesorios')->result();

    	return $r;
    }

    public function find_tipos_accesorios($cond,$limit,$offset)
    {
    	$this->pconditions($cond);
    	$this->db->where('eliminado',0);
    	$this->db->from('tipos_accesorios');
    	$this->db->order_by('id','DESC');

    	if($limit)
    		$this->db->limit($limit,$offset);

    	$r=$this->db->get()->result();

    	return $r;
    }

    public function count_tipos_accesorios($conditions)
    {

    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('tipos_accesorios');
    	$r=$this->db->count_all_results();

    	return $r;
    }

    public function es_requerido($productos_id,$tipos_accesorios_id)
    {
    	$this->db->select('obligatorio_id');
    	$this->db->where('eliminado',0);
    	$this->db->where('productos_id',$productos_id);
    	$this->db->where('tipos_accesorios_id',$tipos_accesorios_id);
    	$r=$this->db->get('productos_tipos_accesorios')->row()->obligatorio_id;
    	return $r;

    }

    public function get_accesorios_individuales($cotizaciones_id=NULL, $descuento_opcional=FALSE, $regalo=FALSE)
    {
        $accesorios = array();
        $accesorios_session = $this->session->userdata('accesorios');

        if($cotizaciones_id)
        {
			$status_id = $this->base->get_dato('status_id','cotizaciones',array('id'=>$cotizaciones_id));

            $this->db->where('cotizaciones_id',$cotizaciones_id);
            $this->db->where('productos_id',NULL);
            $this->db->where('eliminado',0);
			if($regalo)
				$this->db->where('promocion',1);
			else
				$this->db->where('(promocion IS NULL OR promocion=0)');
            $this->db->where('cotizaciones_productos_id',NULL);
            $res = $this->db->get('cotizaciones_accesorios')->result();

			if($regalo)
				$accesorios_session=array();

            if(!empty($res))
            {
                foreach ($res as $k => $acc)
                {
					//if(in_array($acc->accesorios_id,$accesorios_session))
					//{
						$accesorio = $this->base->read('accesorios', $acc->accesorios_id);
						$tipo_accesorio = $this->base->read('tipos_accesorios',$accesorio->tipos_accesorios_id);

						$descuento_distribuidor = $tipo_accesorio->descuento_base?$tipo_accesorio->descuento_base:0;

						if(!$accesorio->consumible)
							$descuento_distribuidor += $this->descuentos_distribuidor_accesorios_individuales($cotizaciones_id);

						$acc->consumible = $accesorio->consumible;
						$acc->obligatorio = 0;
						$acc->modelo = $accesorio->modelo;
						$acc->nombre = $accesorio->nombre;
						$acc->descuento_cliente = $acc->descuento_cliente?$acc->descuento_cliente:$tipo_accesorio->descuento_opcional;
						$acc->descuento_distribuidor = $acc->descuento_distribuidor?$acc->descuento_distribuidor:$descuento_distribuidor;
						$acc->item = $accesorio->item;
						$acc->imagen_orden = $accesorio->imagen_orden;
						$acc->unidad = elemento('Unidades',$accesorio->unidad_id);
						$acc->cantidad = @$acc->cantidad;
						$accesorios_session[$acc->accesorios_id] = $acc;

						if(!empty($status_id) && in_array($status_id,array(1,2,3,6)))
							$acc->precio = $accesorio->precio;
					//}
                }

				return $accesorios_session;
            }
            else
            {
                if($accesorios_session)
                {
                    foreach ($accesorios_session as $acc_id => $acc)
                    {
						$this->db->where('eliminado', 0);
						$accesorio = $this->base->read('accesorios', $acc_id);
						$tipo_accesorio = $this->base->read('tipos_accesorios',$accesorio->tipos_accesorios_id);
						$accesorio->obligatorio = 0;
						$accesorio->accesorios_id = $acc_id;
						$accesorio->cantidad = $accesorios_session[$acc_id]->cantidad ? $accesorios_session[$acc_id]->cantidad : 1;
						$accesorio->descuento_cliente = $tipo_accesorio->descuento_opcional;
						$accesorio->descuento_distribuidor = $tipo_accesorio->descuento_base;
						$accesorio->unidad = elemento('Unidades',$accesorio->unidad_id);
						$accesorios[] = $accesorio;
                    }
                }
            }
        }
        else
        {
            if($accesorios_session)
            {
                foreach ($accesorios_session as $acc_id => $acc)
                {
                    $this->db->where('eliminado', 0);
                    $accesorio = $this->base->read('accesorios', $acc_id);
					$tipo_accesorio = $this->base->read('tipos_accesorios',$accesorio->tipos_accesorios_id);

					$accesorio->obligatorio = 0;
					$accesorio->cantidad = $accesorios_session[$acc_id]->cantidad ? $accesorios_session[$acc_id]->cantidad : 1;
					$accesorio->unidad = elemento('Unidades',$accesorios_session[$acc_id]->unidad_id);
					$accesorio->descuento_cliente = $tipo_accesorio->descuento_opcional;
					$accesorio->descuento_distribuidor = $tipo_accesorio->descuento_base;

                    $accesorios[] = $accesorio;
                }
            }
        }

        return $accesorios;
    }

	public function descuentos_distribuidor_accesorios_individuales($cotizaciones_id=NULL)
	{
		/*
         * DESCUENTOS DISTRIBUIDOR EN ACCESORIOS INDIVIDUALES
        */

		//OBTENEMOS EL ID DE LA CUENTA
		if($cotizaciones_id)
		{
			$cotizacion=$this->base->value_get('cotizaciones',$cotizaciones_id,array('cuentas_id','referido_distribuidor_id'));
			if($cotizacion->referido_distribuidor_id)
				$cuentas_id=$cotizacion->referido_distribuidor_id;
			else
				$cuentas_id=$cotizacion->cuentas_id;
		}
		else
			$cuentas_id = $this->session->userdata('cuentas_id');

		$descuento_total=0;

		// 1.- DESCUENTO DE VENTA ANUAL PARA EL DISTRIBUIDOR
		$descuento_total += $this->base->value_get('cuentas',$cuentas_id,'descuento_monto');

		$desc = $this->value_get('cuentas',$cuentas_id,array('descuento_cooperacion','descuento_transicion','descuento_espacio'));

		// 2.- DESCUENTO POR ESPACIO DE EXHIBICIÓN
		if(isset($desc->descuento_espacio) && !empty($desc->descuento_espacio))
			$descuento_total += $desc->descuento_espacio;

		// 3.- DESCUENTO POR COOPERACIÓN
		if(isset($desc->descuento_cooperacion) && !empty($desc->descuento_cooperacion))
			$descuento_total += $desc->descuento_cooperacion;

		// 4.- DESCUENTO POR TRANSICIÓN
		if(isset($desc->descuento_transicion) && !empty($desc->descuento_transicion))
			$descuento_total += $desc->descuento_transicion;

		return $descuento_total;
	}

	public function get_accesorios_tipos()
	{
		$this->db->select('DISTINCT tipos_accesorios.nombre, tipos_accesorios.id, tipos_accesorios.descripcion, tipos_accesorios.imagen_orden', FALSE);
		$this->db->from('accesorios');
		$this->db->join('tipos_accesorios', 'tipos_accesorios.id = accesorios.tipos_accesorios_id');
		$this->db->where('accesorios.activo',1);
		$this->db->where('consumible',1);
		$this->db->where('accesorios.eliminado',0);
		$this->db->order_by('tipos_accesorios.nombre','ASC');

		$r = $this->db->get()->result();

		return $r;
	}

}