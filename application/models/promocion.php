<?php
require_once('base.php');
class Promocion extends Base {

	var $opciones = array(
		1 => array('Porcentaje de Descuento ( %s % )','porcentaje_descuento'),
		2 => array('Meses sin Intereses ( %s )','meses_sin_intereses')
	);

	public function __construct()
	{
		parent::__construct();    
	}
	
	private function pconditions($cond)
    {
    	if(!empty($cond['activo']))
    	{
    		if($cond['activo']==1)
    			$this->db->where('activo',1);
    		if($cond['activo']==2)
    			$this->db->where('activo',0);
    	}
    	
		if(!empty($cond['id']))
			$this->db->where('p.id',$cond['id']);
    	
    	if(!empty($cond['vigencia_desde']) && empty($cond['vigencia_hasta']))
    		$this->db->where("p.vigencia_desde >= '{$cond['vigencia_desde']}'");

    	if(!empty($cond['vigencia_hasta']) && empty($cond['vigencia_desde']))
    		$this->db->where("p.vigencia_hasta <= '{$cond['vigencia_hasta']}'");
    	
    	if(!empty($cond['vigencia_desde']) && !empty($cond['vigencia_hasta']))
    	{
    		$this->db->where("p.vigencia_desde >= '{$cond['vigencia_desde']}'");
    		$this->db->where("p.vigencia_hasta <= '{$cond['vigencia_hasta']}'");
    	}
    	
    	if(!empty($cond['porcentaje_descuento']))
    		$this->db->like('p.porcentaje_descuento',$cond['porcentaje_descuento']);

		if(!empty($cond['monto_descuento']))
			$this->db->like('p.monto_descuento',$cond['monto_descuento']);
  
    	if(!empty($cond['vigente']) && $cond['vigente']=="Sí")
    	{
    		$this->db->where("(p.vigencia_desde <= CURDATE() AND p.vigencia_hasta >= CURDATE())");
    	}elseif(!empty($cond['vigente']) && $cond['vigente']=="No")
    	{
    		$this->db->where("(p.vigencia_desde > CURDATE() OR p.vigencia_hasta < CURDATE())");
    	}

    }
    
    public function find($cond,$limit,$offset)
    {
    	$this->pconditions($cond);
		$this->db->select("
	    		p.id,
	    		p.nombre,
	    		DATE_FORMAT(p.vigencia_desde,'%d-%m-%Y') as vigencia_desde,
	   			DATE_FORMAT(p.vigencia_hasta,'%d-%m-%Y') as vigencia_hasta,
				(p.vigencia_desde <= CURDATE() && p.vigencia_hasta >= CURDATE()) as vigente,
		    	p.porcentaje_descuento,
		    	p.monto_descuento,
		    	p.meses_sin_intereses,
		    	p.activo",FALSE);
		$this->db->where('eliminado',0);
		$this->db->from('promociones as p');
    	$this->db->order_by('p.created','DESC');

		if($limit)
			$this->db->limit($limit,$offset);

		$r=$this->db->get()->result();

    	return $r;
    }
	
    public function count($conditions)
    {
    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('promociones as c');
    	$r=$this->db->count_all_results();	
    	
    	return $r;
    }

	public function get_alianzas($promocion_id)
	{
		$this->db->select('alianzas_id, cantidad');
		$this->db->where('promociones_id', $promocion_id);
		$q=$this->db->get('promociones_alianzas_regalo')->result();

		$alianzas_ids=array();
		foreach($q as $k=>$v)
		{
			$alianzas_ids[$v->alianzas_id]=$v->cantidad;
		}

		return $alianzas_ids;
	}

    public function get_accesorios($promocion_id, $consumible=0, $regalo=0)
    {
		$tabla = (!$regalo) ? 'promociones_accesorios' : 'promociones_accesorios_regalo';
		if($regalo)
			$this->db->select('accesorios_id, cantidad, porcentaje_regalo');
		else
			$this->db->select('accesorios_id, cantidad');

    	$this->db->where('promociones_id', $promocion_id);
    	$this->db->where('consumible', $consumible);
    	$q=$this->db->get($tabla)->result();

    	$accesorios_ids=array();
    	foreach($q as $k=>$v)
    	{
			if($regalo)
			{
				$accesorios_ids[$v->accesorios_id]['cantidad']=$v->cantidad;
				$accesorios_ids[$v->accesorios_id]['porcentaje']=$v->porcentaje_regalo;
			}
			else
				$accesorios_ids[$v->accesorios_id]=$v->cantidad;
    	}
    	
    	return $accesorios_ids;
    }
    
    public function get_categorias($promocion_id, $regalo=0)
	{
		$tabla = (!$regalo) ? 'promociones_categorias' : 'promociones_categorias_regalo';
    	$this->db->select('productos_categorias_id, cantidad');
    	$this->db->where('promociones_id',$promocion_id);
    	$q=$this->db->get($tabla)->result();
    	
    	$categorias_ids=array();
    	foreach($q as $k=>$v)
    	{
    		$categorias_ids[$v->productos_categorias_id]=$v->cantidad;
    	}
    	 
    	return $categorias_ids;
    }
    
    public function get_productos($promocion_id, $regalo=0)
	{
		$tabla = (!$regalo) ? 'promociones_productos' : 'promociones_productos_regalo';
		if($regalo)
    		$this->db->select('productos_id, cantidad, porcentaje_regalo');
		else
			$this->db->select('productos_id, cantidad');
    	$this->db->where('promociones_id',$promocion_id);
    	$q=$this->db->get($tabla)->result();

    	$productos_ids=array();
    	foreach($q as $k=>$v)
    	{
			if($regalo)
			{
				$productos_ids[$v->productos_id]['cantidad']=$v->cantidad;
				$productos_ids[$v->productos_id]['porcentaje']=$v->porcentaje_regalo;
			}
			else
				$productos_ids[$v->productos_id]=$v->cantidad;
    	}

    	return $productos_ids;
    }

	/**
	 * VERIFICA SI UNA CATEGORIA TIENE PROMOCION PARA OFRECERLA EN EL CATÁLOGO EN LÍNEA
	 */
	public function promocion_tiene($categorias_id=NULL, $productos_id=NULL, $accesorios_id=NULL,$debug=FALSE)
	{
		$promociones_lista=$this->base->lista('promociones','id','nombre');
		if($debug)debug($categorias_id);
		if($debug)debug($productos_id);
		if($promociones_lista)
		{
			foreach($promociones_lista as $k=>$v)
			{
				if($categorias_id)
				{
					$categorias_ids=array();
					$categorias_ids[]=$categorias_id;
					$parent_id=NULL;
					$cat_aux_id = $categorias_id;
					$i=0;
					do
					{
						$parent_id = $this->base->get_dato('parent_id','productos_categorias',array('id'=>$cat_aux_id));
						if($parent_id)
							$categorias_ids[]=$parent_id;
						$cat_aux_id=$parent_id;
					}while($parent_id);

					$this->db->where_in('productos_categorias_id',$categorias_ids);
					$this->db->where('promociones_id',$k);
					$categorias=$this->db->get('promociones_categorias')->result_array();if($debug)debug($categorias);

					$this->db->where('promociones_id',$k);
					$productos=$this->db->get('promociones_productos')->result_array();if($debug)debug($productos);

					$this->db->where('promociones_id',$k);
					$accesorios=$this->db->get('promociones_accesorios')->result_array();if($debug)debug($accesorios);

					if($categorias && !$productos && !$accesorios)
						return $k;
				}

				if($productos_id)
				{
					$this->db->where('promociones_id',$k);
					$this->db->where('productos_id',$productos_id);
					$productos=$this->db->get('promociones_productos')->result_array();if($debug)debug($productos);

					$this->db->where('promociones_id',$k);
					$accesorios=$this->db->get('promociones_accesorios')->result_array();if($debug)debug($accesorios);

					if(@$categorias || ($productos && !$accesorios))
						return $k;
				}

			}
		}

		return FALSE;
	}

	/**
	 * OBTIENE LAS CONDICIONES DE UNA PROMOCION ESPECIFICA
	 */
	public function get_promocion($promocion_id)
	{
		$promocion = $this->base->read('promociones', $promocion_id, TRUE);
		$promocion['promociones_categorias_ids']=$this->get_categorias($promocion_id, FALSE);
		$promocion['promociones_productos_ids']=$this->get_productos($promocion_id, FALSE);
		$promocion['promociones_accesorios_ids']=$this->get_accesorios($promocion_id, FALSE);
		$promocion['promociones_consumibles_ids']=$this->get_accesorios($promocion_id, TRUE, FALSE);

		return $promocion;
	}

	/**
	 * VERIFICA SI UNA COTIZACIÓN CUMPLE CON LAS CONDICIONES DE UNA PROMOCIÓN
	 */
	public function get_promociones($productos, $accesorios_individuales=array(), $monto_total, $msi_banamex=FALSE, $promocion_opcional=FALSE, $debug=FALSE)
	{
		// SI APLICA MSI CON BANAMEX NO HAY PROMOCION
		if($msi_banamex)
			return array();

		// ORDENO IDS DE CATEGORIAS, PRODUCTOS Y ACCESORIOS DE LA COTIZACION
		$categorias_ids=array();
		$productos_ids=array();
		$accesorios_ids=array();
		$consumibles_ids=array();
		if($debug)
			debug($productos);

		if($debug)
			debug($monto_total);
		if(!empty($productos))
		{
			foreach($productos as $producto_id=>$p)
			{
				$productos_ids[$producto_id]=$p['cantidad'];
				$cat_id=$this->base->get_dato('categorias_id','productos',array('id'=>$producto_id));
				$categorias_ids[$cat_id]=trim($p['cantidad']);
				$accesorios=@$p['accesorios'];
				if(!empty($accesorios))
				{
					foreach($accesorios as $accesorio_id=>$acc)
					{
						if($acc['consumible'])
							$consumibles_ids[$accesorio_id]=$acc['cantidad'];
						else
							$accesorios_ids[$accesorio_id]=$acc['cantidad'];
					}
				}
			}
		}

		if($debug)
			debug($accesorios_individuales);

		if(!empty($accesorios_individuales))
		{
			foreach($accesorios_individuales as $accesorio_id=>$acc)
			{
				if($acc->consumible)
					$consumibles_ids[$accesorio_id]=$acc->cantidad;
				else
					$accesorios_ids[$accesorio_id]=$acc->cantidad;
			}
		}


		$promociones_lista=$this->base->lista('promociones','id','nombre',TRUE);
		if($debug)
		{
			debug($promociones_lista);
			debug($categorias_ids);
			debug($productos_ids);
			debug($accesorios_ids);
			debug($consumibles_ids);
		}

		$promociones=array();
		foreach ($promociones_lista as $promocion_id=>$nombre)
		{
			$promociones_categorias_ids=$this->get_categorias($promocion_id, FALSE);if($debug)debug($promociones_categorias_ids);
			$promociones_productos_ids=$this->get_productos($promocion_id, FALSE);if($debug)debug($promociones_productos_ids);
			$promociones_accesorios_ids=$this->get_accesorios($promocion_id, FALSE);if($debug)debug($promociones_accesorios_ids);
			$promociones_consumibles_ids=$this->get_accesorios($promocion_id, TRUE, FALSE);if($debug)debug($promociones_consumibles_ids);

			$cantidad_promocion=1;
			$cantidades_promocion=array();
			$existen_categorias=TRUE;
			if($promociones_categorias_ids)
			{
				foreach($promociones_categorias_ids as $cat_id=>$cantidad)
				{
					if(!array_key_exists($cat_id,$categorias_ids) || (array_key_exists($cat_id,$categorias_ids) && $categorias_ids[$cat_id]<$cantidad))
						$existen_categorias=FALSE;
					elseif(array_key_exists($cat_id,$categorias_ids) && $categorias_ids[$cat_id]>$cantidad)
					{
						$cantidades_promocion[]=floor($categorias_ids[$cat_id]/$cantidad);
					}
				}
			}

			$existen_productos=TRUE;
			if($promociones_productos_ids)
			{
				foreach($promociones_productos_ids as $prod_id=>$cantidad)
				{
					if(!array_key_exists($prod_id,$productos_ids) || (array_key_exists($prod_id,$productos_ids) && $productos_ids[$prod_id]<$cantidad))
						$existen_productos=FALSE;
					elseif(array_key_exists($prod_id,$productos_ids) && $productos_ids[$prod_id]>$cantidad)
					{
						$cantidades_promocion[]=floor($productos_ids[$prod_id]/$cantidad);
					}
				}
			}

			$existen_accesorios=TRUE;
			if($promociones_accesorios_ids)
			{
				foreach($promociones_accesorios_ids as $acc_id=>$cantidad)
				{
					if(!array_key_exists($acc_id,$accesorios_ids) || (array_key_exists($acc_id,$accesorios_ids) && $accesorios_ids[$acc_id]<$cantidad))
						$existen_accesorios=FALSE;
					elseif(array_key_exists($acc_id,$accesorios_ids) && $accesorios_ids[$acc_id]>$cantidad)
					{
						$cantidades_promocion[]=floor($accesorios_ids[$acc_id]/$cantidad);
					}
				}
			}

			$existen_consumibles=TRUE;
			if($promociones_consumibles_ids)
			{
				foreach($promociones_consumibles_ids as $con_id)
				{
					if(!array_key_exists($con_id,$consumibles_ids) || (array_key_exists($con_id,$consumibles_ids) && $consumibles_ids[$con_id]<$cantidad))
						$existen_consumibles=FALSE;
					elseif(array_key_exists($con_id,$consumibles_ids) && $consumibles_ids[$con_id]>$cantidad)
					{
						$cantidades_promocion[]=floor($consumibles_ids[$con_id]/$cantidad);
					}
				}
			}

			// SI NO ESTA VIGENTE LA PROMOCIÓN NO SE APLICA
			$p = $this->base->read('promociones',$promocion_id,TRUE);
			$vigente = TRUE;
			if(strtotime(date('Y-m-d H:i:s')) < strtotime($p['vigencia_desde']) || strtotime(date('Y-m-d H:i:s'))>strtotime($p['vigencia_hasta']))
				$vigente=FALSE;

			$monto_minimo =TRUE;
			$promocion_monto_minimo=0+$p['monto_minimo'];

			if($debug)
				debug($promocion_monto_minimo);

			if($promocion_monto_minimo>$monto_total)
				$monto_minimo=FALSE;

			$cantidad_promocion = $cantidades_promocion && min($cantidades_promocion)>$cantidad_promocion?min($cantidades_promocion):$cantidad_promocion;

			// SI EN LA COTIZACION EXISTEN LAS CATEGORIAS DE LA PROMOCIÓN Y TODOS LOS PRODUCTOS Y ACCESORIOS y SE ALCANZÓ EL MONTO MÍNIMO DE COMPRA LA PROMOCIÓN SERÁ VÁLIDA
			if($debug)
			{
				debug($existen_categorias);
				debug($existen_productos);
				debug($existen_accesorios);
				debug($existen_consumibles);
				debug($vigente);
				debug($monto_minimo);
			}

			if($existen_categorias && $existen_productos && $existen_accesorios && $existen_consumibles && $vigente && $monto_minimo)
				$promociones[$promocion_id] = $cantidad_promocion;
		}

		/**
		 * @var $promocion
		 * SI LA PROMOCION TIENE UN MONTO MINIMO, SE APLICARÁ LA PROMOCION DEL MONTO MINIMO MAYOR QUE CUBRA LA COMPRA
		 * SI DOS PROMOCIONES CON EL MIMSO MONTO DEBE REGRESAR AMBAS PROMOCIONES Y PODER ELEGIR
		 */
		$promociones_ids_aux=array();
		$promocion_monto_mayor_id=0;
		$iguales=array();
		foreach($promociones as $promo_id=>$promo_cantidad)
		{
			$promo = $this->base->read('promociones',$promo_id,TRUE);
			if($promocion_monto_mayor_id)
			{
				$promo_mayor = $this->base->read('promociones',$promocion_monto_mayor_id,TRUE);
				if($promo['monto_minimo'] && $promo['monto_minimo']>$promo_mayor['monto_minimo'])
				{
					$promocion_monto_mayor_id = $promo_id;
					$iguales=array();
				}
				elseif($promo['monto_minimo'] && $promo['monto_minimo']==$promo_mayor['monto_minimo'])
				{
					$iguales[$promocion_monto_mayor_id]=$promociones[$promocion_monto_mayor_id];
                                        if(isset($promociones[$promo_cantidad]))
                                            $iguales[$promo_id]=$promociones[$promo_cantidad];
					$promocion_monto_mayor_id = $promo_id;
				}
			}
			else
			{
				if($promo['monto_minimo'])
				{
					$promocion_monto_mayor_id = $promo_id;
					continue;
				}
				else
					$promociones_ids_aux[$promo_id]=$promo_cantidad;
			}
		}

		if($promocion_monto_mayor_id && empty($iguales))
			$promociones_ids_aux[$promocion_monto_mayor_id]=$promociones[$promocion_monto_mayor_id];

		if($debug)
			debug($iguales);

		if($iguales)
		{
			foreach($iguales as $k=>$v)
				$promociones_ids_aux[$k]=$v;
		}

		if($debug)
			debug($promociones_ids_aux);

		$promociones = $promociones_ids_aux;

		if($promocion_opcional)
			$promociones[$promocion_opcional] = $promociones[$promocion_opcional];

		// VERIFICA SI LAS PROMOCIONES TIENEN REGALOS CON DESCUENTO, DE SER ASÍ NO APLICAN COMO PROMOCION AUTOMATICA
		if(!$promocion_opcional)
		{
			foreach($promociones_ids_aux as $k=>$v)
			{
				$regalos=$this->regalos_get($k);
				if(@$regalos['productos'])
				{
					foreach($regalos['productos'] as $p)
					{
						if(@$p['porcentaje_descuento']<100)
							unset($promociones[$k]);
					}
				}
			}
		}

		$this->session->set_userdata('promociones',array());
		//if($promocion_opcional)
		//	$this->session->set_userdata('promocion_opcional',FALSE);

		if($debug)
			debug($promociones,1);

		return $promociones;
	}

	function regalos_get($promocion_id=NULL, $debug=FALSE)
	{
		$promociones_ids = $this->session->userdata('promociones');

		if($promocion_id)
			$promociones_ids=array($promocion_id=>1);

		$promocion_aux=$this->session->userdata('promociones_id');
		if($promocion_aux && !$promocion_id)
			$promociones_ids=array($promocion_aux=>1);

		if(empty($promociones_ids))
			return FALSE;

		$data=array();
		$i_alianzas=0;
		$i_productos=0;
		$i_accesorios=0;
		foreach($promociones_ids as $promocion_id=>$cantidad_promocion)
		{
			$data['promocion'] = $promocion = $this->base->read('promociones', $promocion_id, TRUE);
			$promociones_productos_ids=$this->get_productos($promocion_id, TRUE);if($debug)debug($promociones_productos_ids);
			$promociones_accesorios_ids=$this->get_accesorios($promocion_id, FALSE, TRUE);if($debug)debug($promociones_accesorios_ids);
			$promociones_consumibles_ids=$this->get_accesorios($promocion_id, TRUE, TRUE);if($debug)debug($promociones_consumibles_ids);
			$promociones_alianzas_ids=$this->get_alianzas($promocion_id);if($debug)debug($promociones_alianzas_ids);

			if($promociones_productos_ids)
			{
				if(!isset($data['productos']))
					$data['productos']=array();
				foreach($promociones_productos_ids as $p=>$v)
				{
					$this->db->select('fp.id as foto_id');
					$this->db->where('p.id',$p);
					$this->db->where('fp.tipos_id',1);
					$this->db->where('fp.eliminado',0);
					$this->db->join('fotografias_productos as fp', 'fp.productos_id=p.id','left');
					$this->db->limit(1);
					$foto_id=$this->db->get('productos as p')->row('foto_id');

					$data['productos'][$i_productos]=$this->base->read('productos', $p, TRUE);
					$data['productos'][$i_productos]['foto_id']=$foto_id?$foto_id:FALSE;
					$data['productos'][$i_productos]['cantidad']=@$v['cantidad']?$v['cantidad']:$v;
					if(@$v['porcentaje'])
					{
						$data['productos'][$i_productos]['precio']=$precio=$this->base->get_dato('precio','productos',array('id'=>$p));
						$data['productos'][$i_productos]['porcentaje_descuento']=$v['porcentaje'];
						$data['productos'][$i_productos]['importe']=$precio - round($precio*($v['porcentaje']/100),2);
					}
					$i_productos++;
				}
			}

			if($promociones_accesorios_ids)
			{
				if(!isset($data['accesorios']))
					$data['accesorios']=array();
				foreach($promociones_accesorios_ids as $acc=>$v)
				{
					$data['accesorios'][$i_accesorios]=$this->base->read('accesorios', $acc, TRUE);
					$orden = $data['accesorios'][$i_accesorios]['imagen_orden']?'_'.$data['accesorios'][$i_accesorios]['imagen_orden']:'';
					$data['accesorios'][$i_accesorios]['path']=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$acc}{$orden}.jpg"):"files/accesorios/{$acc}.jpg";
					if(@$v['porcentaje'])
					{
						$data['accesorios'][$i_accesorios]['precio']=$precio=$this->base->get_dato('precio','accesorios',array('id'=>$acc));
						$data['accesorios'][$i_accesorios]['porcentaje_descuento']=$v['porcentaje'];
						$data['accesorios'][$i_accesorios]['importe']=$precio - round($precio*($v['porcentaje']/100),2);
					}
					$i_accesorios++;
				}
			}

			if($promociones_consumibles_ids)
			{
				if(!isset($data['consumibles']))
					$data['consumibles']=array();
				foreach($promociones_consumibles_ids as $c=>$cantidad)
				{
					$data['consumibles'][]=$this->base->read('accesorios', $c, TRUE);
				}
			}
			if($promociones_alianzas_ids)
			{
				if(!isset($data['alianzas']))
					$data['alianzas']=array();
				foreach($promociones_alianzas_ids as $a)
				{
					$data['alianzas'][$i_alianzas]['id']=$a;
					$data['alianzas'][$i_alianzas]['promociones_id']=$promocion_id;
					$data['alianzas'][$i_alianzas]['nombre']=$this->base->get_dato('nombre','alianzas_promociones',array('id'=>$a));
					$data['alianzas'][$i_alianzas]['descripcion']=$this->base->get_dato('descripcion','alianzas_promociones',array('id'=>$a));
					$i_alianzas++;
				}
			}

			if(@$promocion['meses_sin_intereses'])
			{
				if(!isset($data['msi']))
					$data['msi'] = NULL;
				$data['msi'] = $data['msi']>$promocion['meses_sin_intereses']?$data['msi']:$promocion['meses_sin_intereses'];
			}

			if(@$promocion['porcentaje_descuento'] || @$promocion['monto_descuento'])
			{
				if(!isset($data['descuento']))
					$data['descuento']=array();

				if($promocion['porcentaje_descuento'])
				{
					if(!isset($data['descuento']['porcentaje']))
						$data['descuento']['porcentaje']=0;
					$data['descuento']['porcentaje'] = $data['descuento']['porcentaje']>$promocion['porcentaje_descuento']?$data['descuento']['porcentaje']:$promocion['porcentaje_descuento'];
				}

				if($promocion['monto_descuento'])
				{
					if(!isset($data['descuento']['monto']))
						$data['descuento']['monto']=0;
					$data['descuento']['monto'] = $data['descuento']['monto']>$promocion['monto_descuento']?$data['descuento']['monto']:$promocion['monto_descuento'];
				}
			}
		}
		if($debug)debug($data);
		return $data;
	}

	function alianza_codigo_get($alianzas_id,$cotizaciones_id,$codigo=FALSE)
	{
		$this->db->where('cotizaciones_id',$cotizaciones_id);
		$this->db->where('eliminado',0);
		if($codigo)
			$this->db->where('codigo IS NOT NULL');
		$this->db->where('alianzas_id',$alianzas_id);
		$existe=$this->db->get('cotizaciones_alianzas')->row();

		// SI YA SE ASIGNO EL FOLIO DE LA ALIANZA, NO LO VUELVE A GENERAR
		if($existe)
			return FALSE;

		$this->db->where('alianzas_id',$alianzas_id);
		$this->db->where('usado',0);
		$this->db->limit(1);
		$alianza=$this->db->get('alianzas_folios')->row();

		if(empty($alianza))
			return FALSE;

		// MARCA COMO OCUPADO EL FOLIO
		$data['id']=$alianza->id;
		$data['usado']=1;
		$this->base->guarda('alianzas_folios',$data);

		return $alianza->folio;
	}

	function get_promocion_aplicada($cotizaciones_id)
	{
		$promociones=array();
		$promocion = $this->base->get_dato('promociones_id','cotizaciones',array('id'=>$cotizaciones_id));
		if($promocion)
		{
			$this->db->select('promocion_msi, promocion_msi_cliente,promocion_fija, promocion_porcentaje, promocion_porcentaje_monto');
			$this->db->where('id',$cotizaciones_id);
			$promociones_datos=$this->db->get('cotizaciones')->result_array();

			foreach($promociones_datos as $k=>$v)
			{
				foreach($v as $campo=>$valor)
				{
					$promociones[$campo]=$valor;
				}
			}

			$this->db->where('cotizaciones_id',$cotizaciones_id);
			$this->db->where('promocion',1);
			$this->db->where('eliminado',0);
			$promociones_productos=$this->db->get('cotizaciones_productos')->result_array();

			if($promociones_productos)
			{
				foreach($promociones_productos as $k=>$v)
				{
					$data=$v;
					$this->db->select('id');
					$this->db->where('tipos_id',1);
					$this->db->where('eliminado',0);
					$this->db->where('productos_id',$v['productos_id']);
					$this->db->limit(1);
					$foto_id=$this->db->get('fotografias_productos')->row('id');
					$data['nombre']=$this->base->get_dato('nombre','productos',array('id'=>$v['productos_id']));
					$data['modelo']=$this->base->get_dato('modelo','productos',array('id'=>$v['productos_id']));
					$data['path']=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$v['productos_id']}/{$foto_id}.jpg"):"files/productos/{$v['productos_id']}/{$foto_id}.jpg";
					$promociones['promociones'][]=$data;
				}
			}

			$this->db->where('cotizaciones_id',$cotizaciones_id);
			$this->db->where('promocion',1);
			$this->db->where('eliminado',0);
			$promociones_accesorios=$this->db->get('cotizaciones_accesorios')->result_array();

			if($promociones_accesorios)
			{
				foreach($promociones_accesorios as $k=>$v)
				{
					$data=$v;
					$this->db->select('imagen_orden,nombre,modelo');
					$this->db->where('eliminado',0);
					$this->db->where('id',$v['accesorios_id']);
					$this->db->limit(1);
					$acc=$this->db->get('accesorios')->row();
					$data['nombre']=$acc->nombre;
					$data['modelo']=$acc->modelo;
					$orden = $acc->imagen_orden?'_'.$acc->imagen_orden:'';
					$data['path']=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$v['accesorios_id']}{$orden}.jpg"):"files/accesorios/{$v['accesorios_id']}.jpg";
					$promociones['promociones'][]=$data;
				}
			}

			$this->db->where('cotizaciones_id',$cotizaciones_id);
			$this->db->where('eliminado',0);
			$promociones_alianzas=$this->db->get('cotizaciones_alianzas')->result_array();

			if($promociones_alianzas)
			{
				foreach($promociones_alianzas as $k=>$v)
					$promociones['alianzas'][]=$v;
			}
		}
		return $promociones;
	}

	function promociones_regalos_get($promociones_ids)
	{
		if(empty($promociones_ids))
			return FALSE;

		$regalos=array();
		foreach($promociones_ids as $promocion_id=>$cantidad)
		{
			$regalos[$promocion_id]=$this->regalos_get($promocion_id);
		}

		return $regalos;
	}

	function promociones_opcionales_get($productos_id,$categorias_id,$count=FALSE)
	{
		$this->db->select('p.id');
		$this->db->where('p.activo',1);
		$this->db->where('p.eliminado',0);
		$this->db->where("(pc.productos_categorias_id={$categorias_id} OR pp.productos_id={$productos_id})");
		$this->db->where('(p.vigencia_desde < DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00") AND p.vigencia_hasta > DATE_FORMAT(NOW(),"%Y-%m-%d 23:59:59"))');
		$this->db->join('promociones_productos as pp','pp.promociones_id=p.id','LEFT OUTER');
		$this->db->join('promociones_categorias as pc','pc.promociones_id=p.id','LEFT OUTER');
		$res=$this->db->get('promociones as p')->result_array();//debuglq();

		if(empty($res))
			return FALSE;

		$promociones_ids=array();
		foreach($res as $k=>$v)
			$promociones_ids[]=$v['id'];

		$this->db->select('promociones_id');
		$this->db->where('porcentaje_regalo < 100');
		$this->db->where_in('promociones_id',$promociones_ids);
		$promociones_regalos_ids=$this->db->get('promociones_productos_regalo')->result_array();

		if(empty($promociones_regalos_ids))
			return FALSE;

		$regalos=array();
		foreach($promociones_regalos_ids as $k=>$v)
			$regalos[$v['promociones_id']]=$this->regalos_get($v['promociones_id']);

		if($count)
			return array_keys($regalos);

		return $regalos;
	}

	function categorias_promociones_regalos($categorias_id, $productos_id=NULL)
	{
		$this->db->select('p.id');
		$this->db->where('(p.vigencia_desde < DATE_FORMAT(NOW(),"%Y-%m-%d 00:00:00") AND p.vigencia_hasta > DATE_FORMAT(NOW(),"%Y-%m-%d 23:59:59"))');
		$this->db->where('p.activo',1);
		$this->db->where('p.eliminado',0);

		if($categorias_id && !$productos_id)
			$this->db->where('pc.productos_categorias_id',$categorias_id);

		if($productos_id && !$categorias_id)
			$this->db->where('pp.productos_id',$productos_id);

		if($categorias_id && $productos_id)
			$this->db->where("(pc.productos_categorias_id={$categorias_id} OR pp.productos_id={$productos_id})");

		$this->db->join('promociones_productos as pp','pp.promociones_id=p.id','LEFT OUTER');
		$this->db->join('promociones_categorias as pc','pc.promociones_id=p.id','LEFT OUTER');
		$res=$this->db->get('promociones as p')->result_array();//debuglq();

		if(empty($res))
			return FALSE;

		$promociones_ids=array();
		foreach($res as $k=>$v)
			$promociones_ids[]=$v['id'];

		if(empty($promociones_ids))
			return FALSE;

		$regalos=array();
		foreach($promociones_ids as $k=>$v)
		{
			$promocion = $this->regalos_get($v);
			$regalos[$v]['promocion']= $promocion['promocion'];
			$regalos[$v]['productos'][]= $promocion['productos'][0];
			if(@$promocion['accesorios'][0])
				$regalos[$v]['accesorios'][]= $promocion['accesorios'][0];
		}

		return $regalos;
	}
}