<?php
require_once ('base.php');
class Descuento extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function calcular($cotizaciones_id,$data,$productos, $guarda=FALSE)
	{
		$productos_ids=@array_keys($productos);//SOLO IDS DE PRODUCTOS

		$datos=array();
		$cot=array();
		$subtotales =array('productos'=>0,'accesorios'=>0,'consumibles'=>0);
		$cupones_id = @$data['descuento_cupon'] && @$data['cupones_id'] ? $data['cupones_id'] : FALSE;
		$promocion_msi = @$data['promocion_msi'] ? $data['promocion_msi'] : 0;

		if(!in_array($promocion_msi,array(12,18)))
			$promocion_msi=0;

		$datos['promocion_msi']=$promocion_msi;

		$articulos_participantes = FALSE;
		$this->load->model('Cupon');
		if($cupones_id && @$data['opcion_cupon_id']==1)
			$articulos_participantes = $this->Cupon->articulos_participantes_get($cupones_id);

		$descuento_cliente_cupon = 0;
		$descuento_distribuidor_cupon = 0;
		$total_productos_cargo_recuperacion = 0;

		if($productos)
		{
			//RECUPERO STATUS DE LA COTIZACION
			$status=1;
			if($cotizaciones_id)
				$status=$this->Cotizacion->get_status($cotizaciones_id);

			$descuentos_cliente=0;
			$descuentos_distribuidor = $this->descuentos_distribuidor($cotizaciones_id,$productos_ids, $cupones_id, @$data['opcion_cupon_id']);
			$descuentos_cliente = $this->descuentos_cliente($productos, $data['descuento_opcional'], $data['descuento_paquete'], $cupones_id, @$data['opcion_cupon_id'],@$data['promocion_opcional']);

			if($promocion_msi)
				$descuentos_cliente=array();
			$paquete_adquirido =$this->paquete_adquirido($productos);
			$comisiones_distribuidor = 0;
			$datos['comisiones_vendedor_paquete']=0;
			$porcentaje_comision_vendedor=0;

			if($paquete_adquirido && $promocion_msi!=18 && !@$data['promocion_opcional'])
			{
				$datos['paquete_adquirido']=$this->base->read('paquetes',$paquete_adquirido,TRUE);
				$comisiones_distribuidor = $this->base->value_get('paquetes',$paquete_adquirido,'descuento_distribuidor');
				$porcentaje_comision_vendedor=$this->base->value_get('paquetes',$paquete_adquirido,'comision_vendedor');
			}

			//CALCULO IMPORTES Y DESCUENTOS INDIVIDUALES DE CADA PRODUCTO
			$subtotal_distribuidor=0;
			$subtotal_cliente=0;
			$importe_venta = 0;
			$data['accesorio_vinculados'] = FALSE;
			$data['total_productos'] = count($productos);
			$data['total_productos_sin_cargo']=0;
			$data['total_productos_iniciales'] = count($productos);
			$data['total_eventos'] = 0;
			$data['productos_costo_envio'] = 0;

			// Revisa de donde se puede tomar el dato del estado
			if(!empty($_POST['evento_estado']))
				$evento_estado = $_POST['evento_estado'];
			elseif(!empty($cotizaciones_id))
				$evento_estado = $this->base->get_dato('evento_estado', 'cotizaciones', array('id' => $cotizaciones_id));

			if(empty($evento_estado))
				$evento_estado = $this->session->userdata('evento_estado');

			foreach($productos as $id=>$p)
			{
				$cot_prod=array();
				$cantidad = $productos[$id]['cantidad'];
				$precio = $productos[$id]['precio'];
				$importe_cliente = $cantidad * $precio;
				$importe_distribuidor = $cantidad * $precio;

				if($p['unidad_id']==2 && !empty($evento_estado))
                {
					$postfijo = strtolower(str_replace(' ','_',$evento_estado));
					if(!empty($postfijo))
					{
						$evento = $this->base->get_datos('horas_iniciales_' . $postfijo . ', precio_inicial_' . $postfijo . ', precio_horas_extra_' . $postfijo, 'gastos_cursos', array('productos_id' => $id));
						$precio = $evento->{'precio_inicial_' . $postfijo};

						if ($cantidad > $evento->{'horas_iniciales_' . $postfijo}) {
							$total_horas_extras = $cantidad - $evento->{'horas_iniciales_' . $postfijo};
							$total_costo_horas_extras = $total_horas_extras * $evento->{'precio_horas_extra_' . $postfijo};

							$precio = $evento->{'precio_inicial_' . $postfijo} + $total_costo_horas_extras;
							$importe_distribuidor = $evento->{'precio_inicial_' . $postfijo} + $total_costo_horas_extras;
							$importe_cliente = $evento->{'precio_inicial_' . $postfijo} + $total_costo_horas_extras;
						} else {
							$importe_distribuidor = $evento->{'precio_inicial_' . $postfijo};
							$importe_cliente = $evento->{'precio_inicial_' . $postfijo};
						}

						$cantidad = 1;
					}
				}

				$importe_venta += $cantidad * $precio;

				//PARA CALCULAR COMISION DEL VENDEDOR
				$producto = $this->base->get_datos('categorias_id, unidad_id','productos',array('id'=>$id));
				$categoria_id = $producto->categorias_id;
				$productos_categorias = $this->base->get_datos('comision_vendedor, cargo_recuperacion','productos_categorias',array('id'=>$categoria_id));

				$comision_vendedor = $productos_categorias->comision_vendedor;
				$categoria_cargo_recuperacion = (!empty($productos_categorias->cargo_recuperacion))?$productos_categorias->cargo_recuperacion:0;

				$datos['comisiones_vendedor'][$id]=0;

				if(!empty($p['accesorios']))
				{
					foreach($p['accesorios'] as $acc_id=>$acc)
					{
						$cot_acc=array();
						$cantidad_acc=$acc['cantidad'];
						$precio_acc=$acc['precio'];

						$importe_distribuidor_acc = $cantidad_acc * $precio_acc;
						$importe_cliente_acc = $cantidad_acc * $precio_acc;
						$importe_venta += $cantidad_acc * $precio_acc;

						$descuento_cupon_consumible_distribuidor = FALSE;
						if($descuentos_distribuidor && !$promocion_msi)
						{
							$descuento_cupon_consumible_distribuidor = $articulos_participantes && $acc['consumible'] && in_array($acc_id,$articulos_participantes['consumibles_cupon'])?TRUE:FALSE;
							$descuento_cupon_consumible_distribuidor = !$descuento_cupon_consumible_distribuidor && @$descuentos_distribuidor[$id]['cupon']?$descuentos_distribuidor[$id]['cupon']:0;
							$importe_distribuidor_acc -= round($importe_distribuidor_acc * (!$acc['consumible']?$descuentos_distribuidor[$id]['total']/100:($descuento_cupon_consumible_distribuidor)/100),2);
						}

						$descuento_cupon_consumible_cliente = FALSE;
						if(isset($descuentos_cliente[$id]['accesorios'][$acc_id]['cliente']) && $descuentos_cliente[$id]['accesorios'][$acc_id]['cliente']!==NULL && !$promocion_msi && (empty($p['promocion_opcional']) || (!empty($p['promocion_opcional']) && !@$data['promocion_opcional'])))
						{
							$descuento_cupon_consumible_cliente = $acc['consumible'] && @$descuentos_cliente[$id]['accesorios'][$acc_id]['cliente_cupon']?@$descuentos_cliente[$id]['accesorios'][$acc_id]['cliente_cupon']:0;
							$importe_cliente_acc -= round($importe_cliente_acc * ( ($descuentos_cliente[$id]['accesorios'][$acc_id]['cliente'] + $descuento_cupon_consumible_cliente) / 100 ),2);
						}

						if($comision_vendedor)
						{
							$monto_comision_vendedor= $importe_cliente_acc * ( $comision_vendedor / 100 );
							$suma=FALSE;
							if(isset($datos['paquete_adquirido']['comision_vendedor']) && $comision_vendedor > $datos['paquete_adquirido']['comision_vendedor'])
								$suma=TRUE;
							elseif(!isset($datos['paquete_adquirido']['comision_vendedor']))
								$suma=TRUE;

							if(($suma && !$paquete_adquirido) || ($suma && $paquete_adquirido && $porcentaje_comision_vendedor < $comision_vendedor))
								$datos['comisiones_vendedor'][$id]+=$monto_comision_vendedor;
						}

						$datos['importe_cliente_acc'][$acc_id]=$importe_cliente_acc;
						$datos['importe_distribuidor_acc'][$acc_id]=$importe_distribuidor_acc; //

						if($paquete_adquirido && $porcentaje_comision_vendedor >= $comision_vendedor)
							$datos['comisiones_vendedor_paquete'] += ( $importe_cliente_acc * ( $porcentaje_comision_vendedor/100 ) );

						$subtotal_distribuidor += $importe_distribuidor_acc;
						$subtotal_cliente += $importe_cliente_acc;

						if(!$acc['consumible'])
							$subtotales['accesorios'] += $importe_cliente_acc;
						else
							$subtotales['consumibles'] += $importe_cliente_acc;

						if($cupones_id && @$data['opcion_cupon_id']==1 && !$promocion_msi)
						{
							$descuento_cliente_cupon += @$descuentos_cliente[$id]['accesorios'][$acc_id]['cliente_cupon']? ($cantidad_acc * $precio_acc * ( $descuentos_cliente[$id]['accesorios'][$acc_id]['cliente_cupon']/100 ) ):0;
							$descuento_distribuidor_cupon += @$descuentos_distribuidor[$id]['cupon']? ($cantidad_acc * $precio_acc * ( $descuentos_distribuidor[$id]['cupon']/100 ) ):0;
						}

						if($cotizaciones_id && $guarda)
						{
							$descuento_guarda = (@$descuentos_cliente[$id]['accesorios'][$acc_id]['cliente'] + $descuento_cupon_consumible_cliente) / 100;
							$cot_acc['descuento_cliente']=$descuento_guarda?$descuento_guarda:0;
							$cot_acc['importe_cliente']=$importe_cliente_acc;

							$cot_acc['descuento_distribuidor']=$descuentos_distribuidor?(!$acc['consumible']?$descuentos_distribuidor[$id]['total']/100:($descuentos_distribuidor[$id]['total']-$descuentos_distribuidor[$id]['base']-$descuento_cupon_consumible_distribuidor)/100):0;
							$cot_acc['importe_distribuidor']=$importe_distribuidor_acc;

							$this->db->where('cotizaciones_id',$cotizaciones_id);
							$this->db->where('productos_id',$id);
							$this->db->where('accesorios_id',$acc_id);
							$this->db->where('eliminado',0);
							$this->db->update('cotizaciones_accesorios',$cot_acc);
						}
					}
				}

				if($descuentos_distribuidor && !$promocion_msi)//&& (@empty($p['promocion_opcional']) || (!empty($p['promocion_opcional']) && !@$data['promocion_opcional']))
					$importe_distribuidor -= round($importe_distribuidor * ( $descuentos_distribuidor[$id]['total'] / 100 ),2);
				else
					$descuentos_distribuidor[$id]['total']=0;

				if(isset($descuentos_cliente[$id]['cliente']) && !empty($descuentos_cliente[$id]['cliente']) && !$promocion_msi && (empty($p['promocion_opcional']) || (!empty($p['promocion_opcional']) && !@$data['promocion_opcional'])))
					$importe_cliente -= round($importe_cliente * ( $descuentos_cliente[$id]['cliente'] / 100 ),2);
				else
					$descuentos_cliente[$id]['cliente']=0;

				// Si el producto tiene una categoria con cargo recuperación en porcentaje
				if(!empty($categoria_cargo_recuperacion))
				{
					if(empty($p['sin_envio']))
					{
						if($p['unidad_id']!=2)
							$data['productos_costo_envio'] += (($importe_cliente) * ($categoria_cargo_recuperacion/100));
						else
							$data['productos_costo_envio'] += ($importe_cliente * ($categoria_cargo_recuperacion/100));
						$total_productos_cargo_recuperacion++;
					}

					$data['total_productos_cargo_recuperacion'] = $total_productos_cargo_recuperacion;
				}else
				{
					if(empty($p['sin_envio']))
					{
						$data['total_productos']--;
						$data['total_productos_sin_cargo']++;
					}
				}

				if($producto->unidad_id==2)
				{
					$data['total_eventos']++;
				}

				if($comision_vendedor)
				{
					$monto_comision_vendedor= $importe_cliente * ( $comision_vendedor / 100 );
					$suma=FALSE;
					if(isset($datos['paquete_adquirido']['comision_vendedor']) && $comision_vendedor > $datos['paquete_adquirido']['comision_vendedor'])
						$suma=TRUE;
					elseif(!isset($datos['paquete_adquirido']['comision_vendedor']))
						$suma=TRUE;

					if(($suma && !$paquete_adquirido) || ($suma && $paquete_adquirido && $porcentaje_comision_vendedor < $comision_vendedor))
						$datos['comisiones_vendedor'][$id]+=$monto_comision_vendedor;
				}

				$datos['importe_cliente'][$id]=$importe_cliente;

				if($paquete_adquirido && $porcentaje_comision_vendedor >= $comision_vendedor)
					$datos['comisiones_vendedor_paquete'] += ($importe_cliente * ($porcentaje_comision_vendedor / 100));

				$datos['importe_distribuidor'][$id]=$importe_distribuidor;

				$subtotal_distribuidor += $importe_distribuidor;
				$subtotal_cliente += $importe_cliente;

				if(!$p['sin_envio'])
					$subtotales['productos'] += $importe_cliente;

				if($cupones_id && @$data['opcion_cupon_id']==1 && !$promocion_msi)
				{
					$descuento_cliente_cupon += @$descuentos_cliente[$id]['cliente_cupon']? ($cantidad * $precio * ( $descuentos_cliente[$id]['cliente_cupon']/100 ) ):0;
					$descuento_distribuidor_cupon += @$descuentos_distribuidor[$id]['cupon']? ($cantidad * $precio * ( $descuentos_distribuidor[$id]['cupon']/100 ) ):0;
				}

				if($cotizaciones_id && $guarda)
				{
					$cot_prod['descuento_cliente']=$descuentos_cliente?$descuentos_cliente[$id]['cliente'] / 100:NULL;
					$cot_prod['importe_cliente']=round($importe_cliente,2);

					$cot_prod['descuento_distribuidor']=$descuentos_distribuidor?$descuentos_distribuidor[$id]['total'] / 100:NULL;
					$cot_prod['importe_distribuidor']=round($importe_distribuidor,2);

					$this->db->where('cotizaciones_id',$cotizaciones_id);
					$this->db->where('productos_id',$id);
					$this->db->where('eliminado',0);
					$this->db->update('cotizaciones_productos',$cot_prod);
				}
			}

			$cot=array();
			$datos['descuento_comercial_distribuidor']=$importe_venta - $subtotal_distribuidor;
			$datos['descuento_comercial_cliente']=$importe_venta - ($subtotal_cliente);
			$descuento_paquete_distribuidor=0;
			if($paquete_adquirido && $comisiones_distribuidor)
			{
				$descuento_paquete_distribuidor = $importe_venta * ( $comisiones_distribuidor / 100 );
				$subtotal_distribuidor-=$descuento_paquete_distribuidor;
				$cot['descuento_paquete_distribuidor']=$comisiones_distribuidor / 100 ;
			}

			$datos['rescate_sucursal']=@$data['rescate_sucursal'];
			$datos['subtotal_distribuidor']=$subtotal_distribuidor;
			$datos['subtotal_cliente']=$subtotal_cliente;

		}

		// CALCULO ACCESORIOS INDIVIDUALES
		$this->load->model('Accesorio');
		$accesorios_individuales = $this->Accesorio->get_accesorios_individuales($cotizaciones_id, $data['descuento_opcional']);

		// ELIMINO LOS ACCESORIOS Y CONSUMIBLES INDIVIDUALES QUE NO ESTÁN ACTIVOS
		if(!empty($accesorios_individuales))
		{
			$accesorios_aux = $accesorios_individuales;
			foreach ($accesorios_aux AS $a => $acc)
			{
				$acc_id = isset($acc->accesorios_id)?$acc->accesorios_id:$acc->id;
				$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $acc_id));
				if (!$accesorio_activo)
				{
					unset($accesorios_individuales[$a]);
				}
			}
		}

		$ai = array();
		if($accesorios_individuales)
		{
			$ai['accesorios_importe_total_cliente']=0;
			$ai['accesorios_subtotal_cliente']=0;
			$ai['accesorios_subtotal_distribuidor']=0;
			$ai['accesorios_iva_cliente']=0;
			$ai['accesorios_total_cliente']=0;

			foreach ($accesorios_individuales as $k => $acc)
			{
				$cot_ai = array();

				$accesorio_id = $cotizaciones_id?$acc->accesorios_id:$acc->id;

				$descuento_acc_cliente=@$acc->descuento_cliente;//$cotizaciones_id?@$acc->descuento_cliente:@$acc->descuento_opcional;
				$descuento_cliente_ai = ($descuento_acc_cliente && $data['descuento_opcional'] && !$acc->consumible && !$promocion_msi)?$descuento_acc_cliente:FALSE;
				if($articulos_participantes && (in_array($accesorio_id, $articulos_participantes['accesorios_cupon']) || in_array($accesorio_id,$articulos_participantes['consumibles_cupon'])) && !$promocion_msi)
					$descuento_cliente_ai = $articulos_participantes['cupon']['porcentaje_descuento'];

				if($cupones_id && @$data['opcion_cupon_id']==1 && $descuento_cliente_ai && !$promocion_msi)
					$descuento_cliente_cupon += ($acc->precio * $acc->cantidad) * ( $descuento_cliente_ai/100 );

				$descuento_acc_distribuidor=@$acc->descuento_distribuidor;

				$descuento_distribuidor_ai = ($descuento_acc_distribuidor && !$acc->consumible && !$promocion_msi)?$descuento_acc_distribuidor:FALSE;
				if($articulos_participantes && (in_array($accesorio_id, $articulos_participantes['accesorios_cupon']) || in_array($accesorio_id,$articulos_participantes['consumibles_cupon'])) && !$promocion_msi)
					$descuento_distribuidor_ai = $articulos_participantes['cupon']['descuento_distribuidor'];

				if($cupones_id && @$data['opcion_cupon_id']==1 && $descuento_distribuidor_ai && !$promocion_msi)
					$descuento_distribuidor_cupon += ($acc->precio * $acc->cantidad) * ( $descuento_distribuidor_ai/100 );

				$ai['descuento_cliente_acc_individual'][$accesorio_id] = $cot_ai['descuento_cliente'] = $descuento_cliente_ai?$descuento_cliente_ai:NULL;
				$ai['importe_cliente_acc_individual'][$accesorio_id] = $cot_ai['importe_cliente'] = ($acc->precio*$acc->cantidad)-(($acc->precio*$acc->cantidad)*($cot_ai['descuento_cliente']/100));
				$ai['accesorios_importe_total_cliente']+=$acc->precio*$acc->cantidad;

				$ai['descuento_distribuidor_acc_individual'][$accesorio_id] = $cot_ai['descuento_distribuidor'] = $descuento_distribuidor_ai?$descuento_distribuidor_ai:0;
				$ai['importe_distribuidor_acc_individual'][$accesorio_id] = $cot_ai['importe_distribuidor'] = ($acc->precio*$acc->cantidad)-(($acc->precio*$acc->cantidad)*($cot_ai['descuento_distribuidor']/100));

				$ai['accesorios_subtotal_cliente']+=$ai['importe_cliente_acc_individual'][$accesorio_id];
				$ai['accesorios_subtotal_distribuidor']+=$ai['importe_distribuidor_acc_individual'][$accesorio_id];

				if($acc->consumible)
					$subtotales['consumibles'] += $ai['importe_cliente_acc_individual'][$accesorio_id];
				else
					$subtotales['accesorios'] += $ai['importe_cliente_acc_individual'][$accesorio_id];

				if($cotizaciones_id && $guarda)
				{
					$this->db->where('cotizaciones_id',$cotizaciones_id);
					$this->db->where('accesorios_id',$acc->accesorios_id);
					$this->db->where('productos_id IS NULL');
					$this->db->where('eliminado',0);
					$this->db->update('cotizaciones_accesorios',$cot_ai);
				}
			}
		}

		$datos = array_merge($datos, $ai);

		$promocion_opcional_importe=0;
		$promocion_opcional_subtotal=0;
		$datos['promocion_opcional_descuento']=0;
		$subtotales['promocion_opcional']=0;
		if(@$data['promocion_opcional'])
		{
			if(@$data['promocion_opcional']['productos'])
			{
				foreach($data['promocion_opcional']['productos'] as $k=>$po)
				{
					if(@$po['precio'] && @$po['porcentaje_descuento'])
					{
						$promocion_opcional_importe += $po['precio'];
						$datos['promocion_opcional_descuento'] += round($po['precio']*($po['porcentaje_descuento']/100),2);
					}
				}
			}

			if(@$data['promocion_opcional']['accesorios'])
			{
				foreach($data['promocion_opcional']['accesorios'] as $k=>$ao)
				{
					if(@$ao['precio'] && @$ao['porcentaje_descuento'])
					{
						$promocion_opcional_importe += $ao['precio'];
						$datos['promocion_opcional_descuento'] += round($ao['precio']*($ao['porcentaje_descuento']/100),2);
					}
				}
			}

			$promocion_opcional_subtotal+=$promocion_opcional_importe-$datos['promocion_opcional_descuento'];

			$subtotales['promocion_opcional']=$promocion_opcional_subtotal;
		}

		$datos['importe_venta'] = @$importe_venta + @$ai['accesorios_importe_total_cliente'] + $promocion_opcional_importe;
		$datos['descuento_cliente_cupon'] = $descuento_cliente_cupon;
		$datos['descuento_distribuidor_cupon'] = $descuento_distribuidor_cupon;

		$sin_envio = (isset($data['sin_envio']) && $data['sin_envio']==1 )?TRUE:FALSE;
		$envio=0;

		if(isset($data['rescate_sucursal']) && !empty($data['rescate_sucursal']))
			$rescate_sucursal = $data['rescate_sucursal'];
		elseif(isset($data['rescate_sucursal']) && empty($data['rescate_sucursal']))
			$rescate_sucursal = $data['rescate_sucursal'];
		else
			$rescate_sucursal = $this->base->value_get('cotizaciones', $cotizaciones_id, 'rescate_sucursal');

		if((isset($data['entrega_estado']) && !empty($data['entrega_estado'])) && (isset($data['instalacion_estado']) && !empty($data['instalacion_estado'])) && empty($rescate_sucursal))
		{
			$p = $subtotales['productos'] ? TRUE : FALSE;
			$a = $subtotales['accesorios'] ? TRUE : FALSE;
			$c = $subtotales['consumibles'] ? TRUE : FALSE;

			$min_productos = $this->min_articulos_envio($data, $p, $a, $c); // MINIMO DE ARTICULOS A CONSIDERAR PARA COBRO DE ENVIO
			$num_articulos_carrito = $this->get_num_articulos($productos, $accesorios_individuales); // NUMERO DE ARTICULOS EN EL CARRITO

			if (!empty($productos_ids) && $num_articulos_carrito <= $min_productos && $sin_envio == FALSE) {
				if (isset($data['entrega_estado']) && $data['entrega_estado'] == NULL)
					$data['entrega_estado'] = $this->base->value_get('cotizaciones', $cotizaciones_id, 'entrega_estado');
				if (isset($data['instalacion_estado']) && $data['instalacion_estado'] == NULL)
					$data['instalacion_estado'] = $this->base->value_get('cotizaciones', $cotizaciones_id, 'instalacion_estado');

				$envio += $this->calcular_envio($subtotales, $data);
			} elseif (empty($productos_ids) && $num_articulos_carrito <= $min_productos && $sin_envio == FALSE) {
				$envio += $this->calcular_envio($subtotales, $data, TRUE);
			}
		}elseif(!empty($data['rescate_sucursal']))
		{
			$envio_datos['envio']=0;
			$this->db->where('id',$cotizaciones_id);
			$this->db->update('cotizaciones',$envio_datos);
		}

		// Si el estado entrega está definido se puede calcular el precio del curso
		$datos['evento_calcular_precio'] = false;
		if((isset($data['entrega_estado']) && !empty($data['entrega_estado'])))
			$datos['evento_calcular_precio'] = true;

		$this->session->set_userdata('evento_calcular_precio',true);

		$datos['subtotal_cliente'] = @$datos['subtotal_cliente']+@$ai['accesorios_subtotal_cliente']+$promocion_opcional_subtotal;
		$datos['descuento_comercial_cliente']=$datos['importe_venta'] - $datos['subtotal_cliente'];
		$datos['subtotal_distribuidor'] = @$datos['subtotal_distribuidor']+@$ai['accesorios_subtotal_distribuidor']+$promocion_opcional_subtotal;//debug($datos['subtotal_distribuidor']);

		// RESTO DESCUENTO DE PROMOCIONES
		$promocion_descuento_fijo=0;
		$promocion_descuento_porcentaje=0;
		$promocion_descuento_porcentaje_monto=0;
		$promocion_descuento_porcentaje_monto_distribuidor=0;
		$datos['promocion_msi_cliente']=0;
		$datos['promocion_msi_distribuidor']=0;
		$datos['promocion_descuento_fijo']=0;
		$datos['promocion_descuento_porcentaje']=0;
		$datos['promocion_descuento_porcentaje_monto']=0;
		$datos['promocion_descuento_porcentaje_monto_distribuidor']=0;

		if(@$data['promociones_id'] && !$promocion_msi)
		{
			$this->load->Model('Promocion');
			$regalos=$this->Promocion->regalos_get($data['promociones_id']);
			$promocion_descuento_fijo = @$regalos['descuento']['monto']?$regalos['descuento']['monto']:0;
			if($promocion_descuento_fijo)
				$datos['promocion_descuento_fijo']=$promocion_descuento_fijo;
			$promocion_descuento_porcentaje = @$regalos['descuento']['porcentaje']?$regalos['descuento']['porcentaje']:0;
			if($promocion_descuento_porcentaje)
			{
				$promocion_descuento_porcentaje_monto = round($datos['subtotal_cliente']*($promocion_descuento_porcentaje/100),2);
				$datos['promocion_descuento_porcentaje']=$promocion_descuento_porcentaje;
				$datos['promocion_descuento_porcentaje_monto']=$promocion_descuento_porcentaje_monto;

				$promocion_descuento_porcentaje_monto_distribuidor = round($datos['subtotal_distribuidor']*($promocion_descuento_porcentaje/100),2);
				$datos['promocion_descuento_porcentaje_monto_distribuidor']=$promocion_descuento_porcentaje_monto_distribuidor;
			}

			if(@$data['sin_descuento_promocion'])
			{
				$promocion_descuento_fijo=0;
				$promocion_descuento_porcentaje_monto=0;
			}

			$datos['subtotal_cliente']=@$datos['subtotal_cliente']-$promocion_descuento_fijo-$promocion_descuento_porcentaje_monto;
			$datos['subtotal_distribuidor']=@$datos['subtotal_distribuidor']-$promocion_descuento_fijo-$promocion_descuento_porcentaje_monto_distribuidor;
		}

		$datos['subtotal_cliente'] = @$datos['subtotal_cliente']+$envio;
		$datos['subtotal_distribuidor'] = @$datos['subtotal_distribuidor']+$envio;//debug($datos['subtotal_distribuidor']);
		$datos['envio']=$envio;

		//$datos['descuento_comercial_distribuidor']=$datos['importe_venta'] - ($datos['subtotal_distribuidor']-$envio);

		$iva_cliente = @$datos['subtotal_cliente']?( $datos['subtotal_cliente']) * ( 16 / 100 ):0;
		$iva_distribuidor = @$datos['subtotal_distribuidor']?( $datos['subtotal_distribuidor']) * ( 16 / 100 ):0;

		$datos['iva_cliente'] = $iva_cliente;
		$datos['iva_distribuidor']=$iva_distribuidor;
		$datos['descuentos_distribuidor']=@$descuentos_distribuidor?$descuentos_distribuidor:0;
		$datos['descuentos_cliente']=@$descuentos_cliente?$descuentos_cliente:0;
		$datos['comisiones_distribuidor']=@$comisiones_distribuidor?$comisiones_distribuidor:0;

		$datos['total_cliente'] = $datos['subtotal_cliente'] + $datos['iva_cliente'];
		$datos['total_distribuidor'] = $datos['subtotal_distribuidor'] + $datos['iva_distribuidor'];
		$datos['descuento_opcional']=@$data['descuento_opcional']?$data['descuento_opcional']:0;
		$datos['descuento_paquete']=@$data['descuento_paquete']?$data['descuento_paquete']:0;
		$datos['descuento_paquete_distribuidor']=@$descuento_paquete_distribuidor?$descuento_paquete_distribuidor:0;
		$datos['importe_distribuidor_neto']= @$importe_distribuidor?$importe_distribuidor:0;
		$datos['importe_cliente_neto']= @$importe_cliente?$importe_cliente:0;
		$datos['descuento_cupon'] = @$data['descuento_cupon']?$data['descuento_cupon']:0;
		$datos['cupones_id'] = @$data['cupones_id']?$data['cupones_id']:NULL;
		$datos['folio_cupon'] = @$data['folio_cupon']?$data['folio_cupon']:NULL;
		$datos['opcion_cupon_id'] = @$data['opcion_cupon_id']?$data['opcion_cupon_id']:NULL;
		$datos['mensualidad_cliente_cupon'] = @$data['opcion_cupon_id']==2?round($datos['total_cliente']/12,2):0;
		$datos['mensualidad_distribuidor_cupon'] = @$data['opcion_cupon_id']==2?round($datos['total_distribuidor']/12,2):0;
		$datos['promocion_msi_cliente'] = $promocion_msi?round($datos['total_cliente']/$promocion_msi,2):0;
		$datos['promocion_msi_distribuidor'] = $promocion_msi?round($datos['total_distribuidor']/$promocion_msi,2):0;

		if($cotizaciones_id && $guarda)
		{
			$cot['id']=$cotizaciones_id;
			$cot['importe_total'] = round($datos['importe_venta'],2);
			if($cupones_id && @$data['opcion_cupon_id']==1)
			{
				$cot['descuento_cliente_cupon'] = round($datos['descuento_cliente_cupon'],2);
				$cot['descuento_distribuidor_cupon'] = round($datos['descuento_distribuidor_cupon'],2);
			}
			$cot['descuento_cliente']=round($datos['descuento_comercial_cliente'],2);
			$cot['descuento_distribuidor']=round($datos['importe_venta']-$datos['subtotal_distribuidor']-@$descuento_paquete_distribuidor-@$promocion_descuento_porcentaje_monto_distribuidor-@$promocion_descuento_fijo,2);
			$cot['promocion_opcional_descuento']=$datos['promocion_opcional_descuento'];
			$cot['promocion_fija']=round($promocion_descuento_fijo,2);
			$cot['promocion_porcentaje']=round($promocion_descuento_porcentaje,2);
			$cot['promocion_porcentaje_monto']=round($promocion_descuento_porcentaje_monto,2);
			$cot['promocion_porcentaje_monto_distribuidor']=round($promocion_descuento_porcentaje_monto_distribuidor,2);
			$cot['promocion_msi']=$promocion_msi;
			$cot['promocion_msi_cliente']=$datos['promocion_msi_cliente'];
			$cot['promocion_msi_distribuidor']=$datos['promocion_msi_distribuidor'];
			$cot['subtotal_cliente']=round($datos['subtotal_cliente'],2);
			$cot['subtotal_distribuidor']=round($datos['subtotal_distribuidor'],2);
			$cot['envio']=$envio;
			$cot['iva_cliente']=round($datos['iva_cliente'],2);
			$cot['iva_distribuidor']=round($datos['iva_distribuidor'],2);
			$cot['total_cliente']=round($datos['total_cliente'],2);
			$cot['total_distribuidor']=round($datos['total_distribuidor'],2);
			$cot['mensualidad_cliente_cupon']=$datos['mensualidad_cliente_cupon'];
			$cot['mensualidad_distribuidor_cupon']=$datos['mensualidad_distribuidor_cupon'];
			$this->base->guarda('cotizaciones',$cot);
		}

		if($cupones_id && @$data['opcion_cupon_id']==1 && $guarda)
		{
			$cupon = array('usado'=>1);
			$this->db->where('cupones_id',$cupones_id);
			$this->db->where('folio',$data['folio_cupon']);
			$this->db->update('cupones_folios',$cupon);
		}

		return $datos;
	}

	public function get_num_articulos($productos, $accesorios)
	{
		$num_articulos=0;
		if($productos)
		{
			foreach($productos as $p_id=>$producto)
			{
				if(!@$producto['sin_envio'])
					$num_articulos += $producto['cantidad'];
			}
		}

		/*if($accesorios)
        {
          foreach($accesorios as $acc_id=>$accesorio)
            $num_articulos += $accesorio->cantidad;
        }*/

		return $num_articulos;
	}

	public function min_articulos_envio($estados, $productos=FALSE, $accesorios=FALSE, $consumibles=FALSE)
	{
		$maximo=NULL;

		$this->load->model('gastos_envio','GE');

		if($productos)
		{
			$data['envio_estado_entrega'] = $this->GE->gastos_envio_min_productos($estados['entrega_estado'],1);
			$data['envio_estado_instalacion'] = $this->GE->gastos_envio_min_productos($estados['instalacion_estado'],1);
			$maximo = max($data['envio_estado_entrega'],$data['envio_estado_instalacion']);
		}
		elseif($accesorios)
		{
			$data['envio_estado_entrega'] = $this->GE->gastos_envio_min_productos($estados['entrega_estado'],2);
			$data['envio_estado_instalacion'] = $this->GE->gastos_envio_min_productos($estados['instalacion_estado'],2);
			$maximo = max($data['envio_estado_entrega'],$data['envio_estado_instalacion']);
		}
		elseif($consumibles)
			$maximo = 99999;

		return $maximo;
	}

	public function calcular_envio($subtotales, $estados, $individual=FALSE)
	{
		$this->load->model('gastos_envio','GE');
		$data = $estados;

		if(!$individual)
		{
			// ACCESORIOS - PORCENTAJE ENTREGA
			$ae = $this->GE->gastos_envio_costo($estados['entrega_estado'],2);

			$envio_ae=0;
			if($subtotales['accesorios']>0 && ($ae->accesorios_porcentaje > 0 || $ae->accesorios_monto_fijo > 0))
			{
				if($ae->accesorios_porcentaje>0)
					$envio_ae = round($subtotales['accesorios'] * ($ae->accesorios_porcentaje/100),2);
				else
					$envio_ae = round($ae->accesorios_monto_fijo,2);
			}

			// ACCESORIOS - PORCENTAJE INSTALACION
			$ai = $this->GE->gastos_envio_costo($estados['instalacion_estado'],2);

			$envio_ai=0;
			if($subtotales['accesorios']>0 && ($ai->accesorios_porcentaje > 0 || $ai->accesorios_monto_fijo > 0))
			{
				if($ai->accesorios_porcentaje>0)
					$envio_ai = round($subtotales['accesorios'] * ($ai->accesorios_porcentaje/100),2);
				else
					$envio_ai = round($ai->accesorios_monto_fijo,2);
			}

			// CONSUMIBLES - PORCENTAJE ENTREGA
			$ce = $this->GE->gastos_envio_costo($estados['entrega_estado'],3);

			$envio_ce=0;
			if($subtotales['consumibles']>0 && ($ce->consumibles_porcentaje > 0 || $ce->consumibles_monto_fijo > 0))
			{
				if($ce->consumibles_porcentaje>0)
					$envio_ce = round($subtotales['consumibles'] * ($ce->consumibles_porcentaje/100),2);
				else
					$envio_ce = round($ce->consumibles_monto_fijo,2);

				/*if($ce->consumibles_porcentaje>0 && !$envio_ae)
					$envio_ce = round($subtotales['consumibles'] * ($ce->consumibles_porcentaje/100),2);
				else
				{
					$envio_ce = $envio_ae;
					$envio_ae = 0;
				}*/
			}

			// CONSUMIBLES - PORCENTAJE INSTALACION
			$ci = $this->GE->gastos_envio_costo($estados['instalacion_estado'],3);

			$envio_ci=0;
			if($subtotales['consumibles']>0 && ($ci->consumibles_porcentaje > 0 || $ci->consumibles_monto_fijo > 0))
			{
				if($ci->consumibles_porcentaje>0)
					$envio_ci = round($subtotales['consumibles'] * ($ci->consumibles_porcentaje/100),2);
				else
					$envio_ci = round($ci->consumibles_monto_fijo,2);

				/*if($ci->consumibles_porcentaje>0 && !$envio_ai)
					$envio_ci = round($subtotales['consumibles'] * ($ci->consumibles_porcentaje/100),2);
				else
				{
					$envio_ci = $envio_ai;
					$envio_ai = 0;
				}*/
			}

			// PROMOCION PRODUCTOS OPCIONALES - PORCENTAJE ENTREGA
			$poe = $this->GE->gastos_envio_costo($estados['entrega_estado'],1);

			$envio_poe=0;
			if($subtotales['promocion_opcional']>0 && ($poe->productos_porcentaje > 0 || $poe->productos_monto_fijo > 0))
			{
				if($poe->productos_porcentaje > 0 && !$envio_ae)
					$envio_poe = round($subtotales['promocion_opcional'] * ($poe->productos_porcentaje/100),2);
				else
				{
					$envio_poe = $envio_ae;
					$envio_ae = 0;
				}
			}

			// PROMOCION PRODUCTOS OPCIONALES- PORCENTAJE INSTALACION
			$poi = $this->GE->gastos_envio_costo($estados['instalacion_estado'],1);

			$envio_poi=0;
			if($subtotales['promocion_opcional']>0 && ($poi->productos_porcentaje > 0 || $poi->productos_monto_fijo > 0))
			{
				if($poi->productos_porcentaje>0 && !$envio_ai)
					$envio_poi = round($subtotales['promocion_opcional'] * ($poi->productos_porcentaje/100),2);
				else
				{
					$envio_poi = round($poi->productos_monto_fijo, 2);
					$envio_ai = 0;
				}
			}

			$total_productos = $data['total_productos'];
			$total_productos_iniciales = $data['total_productos_iniciales'];
			$total_productos_sin_cargo = $data['total_productos_sin_cargo'];
			$total_eventos = $data['total_eventos'];
			$productos_costo_envio = $data['productos_costo_envio'];
			$total_productos_cargo_recuperacion = (@$data['total_productos_cargo_recuperacion'])?$data['total_productos_cargo_recuperacion']:0;

			$envio_entrega = $envio_ae + $envio_ce + $envio_poe;
			$envio_instalacion = $envio_ai + $envio_ci + $envio_poi;

			// PRODUCTOS - PORCENTAJE ENTREGA
			$pe = $this->GE->gastos_envio_costo($estados['entrega_estado'],1);

			$envio_pe=0;
			if($pe->productos_porcentaje > 0 || $pe->productos_monto_fijo > 0)
			{
				// Si todos mis productos tienen un cargo de recuperación en porcentaje, no hay accesorios ni consumibles
				if($total_productos == $total_productos_iniciales && !$subtotales['accesorios'] && !$subtotales['consumibles'])
				{
					$envio_pe = $productos_costo_envio + $envio_entrega;
				// Si todos mis productos tienen un cargo de recuperación en porcentaje, no hay accesorios pero hay consumibles
				}elseif($total_productos == $total_productos_iniciales && ($subtotales['accesorios'] || $subtotales['consumibles']))
				{
					$envio_pe = $productos_costo_envio + $envio_entrega;
				}elseif(!$total_productos && ($total_eventos && $total_productos_sin_cargo==$total_eventos) && !$subtotales['accesorios'] && $subtotales['consumibles'])
				{
					$envio_pe = $productos_costo_envio + $envio_entrega;
				}elseif($total_productos && ($total_eventos && $total_productos_sin_cargo==$total_eventos) && !$subtotales['accesorios'] && !$subtotales['consumibles'])
				{
					$envio_pe = $productos_costo_envio + $envio_entrega;
				}elseif($total_productos && ($total_eventos && $total_productos_sin_cargo==$total_eventos) && !$subtotales['accesorios'] && $subtotales['consumibles'])
				{
					$envio_pe = $productos_costo_envio + $envio_entrega;
				}
				else
				{
					if($subtotales['productos']>0)
					{
						if($pe->productos_porcentaje > 0)
							$envio_pe = round($subtotales['productos'] * ($pe->productos_porcentaje/100),2);
						else
							$envio_pe = round($pe->productos_monto_fijo, 2);
					}
				}

				// Si sólo hay eventos
				if($total_eventos == $total_productos_iniciales && !$subtotales['accesorios'] && !$subtotales['consumibles'])
					$envio_pe = 0;
			}

			// PRODUCTOS - PORCENTAJE INSTALACION
			$pi = $this->GE->gastos_envio_costo($estados['instalacion_estado'],1);

			$envio_pi=0;
			if($pi->productos_porcentaje > 0 || $pi->productos_monto_fijo > 0)
			{
				// Si todos mis productos tienen un cargo de recuperación en porcentaje, no hay accesorios ni consumibles
				if($total_productos == $total_productos_iniciales && !$subtotales['accesorios'] && !$subtotales['consumibles'])
				{
					$envio_pi = $productos_costo_envio + $envio_instalacion;
					// Si todos mis productos tienen un cargo de recuperación en porcentaje, no hay accesorios pero hay consumibles
				}elseif($total_productos == $total_productos_iniciales && ($subtotales['accesorios'] || $subtotales['consumibles']))
				{
					$envio_pi = $productos_costo_envio + $envio_instalacion;
				}elseif(!$total_productos && ($total_eventos && $total_productos_sin_cargo==$total_eventos) && !$subtotales['accesorios'] && $subtotales['consumibles'])
				{
					$envio_pi = $productos_costo_envio + $envio_instalacion;
				}elseif($total_productos && ($total_eventos && $total_productos_sin_cargo==$total_eventos) && !$subtotales['accesorios'] && !$subtotales['consumibles'])
				{
					$envio_pi = $productos_costo_envio + $envio_instalacion;
				}elseif($total_productos && ($total_eventos && $total_productos_sin_cargo==$total_eventos) && !$subtotales['accesorios'] && $subtotales['consumibles'])
				{
					$envio_pi = $productos_costo_envio + $envio_instalacion;
				}
				else
				{
					if($subtotales['productos']>0)
					{
						if($pi->productos_porcentaje > 0)
							$envio_pi = round($subtotales['productos'] * ($pi->productos_porcentaje/100),2);
						else
							$envio_pi = round($pi->productos_monto_fijo, 2);
					}
				}

				// Si sólo hay eventos
				if($total_eventos == $total_productos_iniciales && !$subtotales['accesorios'] && !$subtotales['consumibles'])
					$envio_pi = 0;
			}

			$envio_entrega = $envio_pe;
			$envio_instalacion = $envio_pi;
			$envio = max($envio_entrega, $envio_instalacion);
		}
		else
		{
			// ACCESORIOS - PORCENTAJE ENTREGA
			$ae = $this->GE->gastos_envio_costo($estados['entrega_estado'],2);

			$envio_ae=0;
			if($subtotales['accesorios']>0 && ($ae->accesorios_porcentaje > 0 || $ae->accesorios_monto_fijo > 0))
			{
				if($ae->accesorios_porcentaje>0)
					$envio_ae = round($subtotales['accesorios'] * ($ae->accesorios_porcentaje/100),2);
				else
					$envio_ae = round($ae->accesorios_monto_fijo,2);
			}

			// ACCESORIOS - PORCENTAJE INSTALACION
			$ai = $this->GE->gastos_envio_costo($estados['instalacion_estado'],2);

			$envio_ai=0;
			if($subtotales['accesorios']>0 && ($ai->accesorios_porcentaje > 0 || $ai->accesorios_monto_fijo > 0))
			{
				if($ai->accesorios_porcentaje>0)
					$envio_ai = round($subtotales['accesorios'] * ($ai->accesorios_porcentaje/100),2);
				else
					$envio_ai = round($ai->accesorios_monto_fijo,2);
			}

			// CONSUMIBLES - PORCENTAJE ENTREGA
			$ce = $this->GE->gastos_envio_costo($estados['entrega_estado'],3);

			$envio_ce=0;
			if($subtotales['consumibles']>0 && ($ce->consumibles_porcentaje > 0 || $ce->consumibles_monto_fijo > 0))
			{
				if($ce->consumibles_porcentaje>0)
					$envio_ce = round($subtotales['consumibles'] * ($ce->consumibles_porcentaje/100),2);
				else
					$envio_ce = round($ce->consumibles_monto_fijo,2);

				/*if(!$subtotales['accesorios'])
					$envio_ce = round($subtotales['consumibles'] * ($ce->consumibles_porcentaje/100),2);
				else
				{
					$envio_ce = $envio_ae;
					$envio_ae = 0;
				}*/
			}

			// CONSUMIBLES - PORCENTAJE INSTALACION
			$ci = $this->GE->gastos_envio_costo($estados['instalacion_estado'],3);

			$envio_ci=0;
			if($subtotales['consumibles']>0 && ($ci->consumibles_porcentaje > 0 || $ci->consumibles_monto_fijo > 0))
			{
				if($ci->consumibles_porcentaje>0)
					$envio_ci = round($subtotales['consumibles'] * ($ci->consumibles_porcentaje/100),2);
				else
					$envio_ci = round($ci->consumibles_monto_fijo,2);

				/*if(!$subtotales['accesorios']>0)
					$envio_ci = round($subtotales['consumibles'] * ($ci->consumibles_porcentaje/100),2);
				else
				{
					$envio_ci = $envio_ai;
					$envio_ai = 0;
				}*/
			}

			$envio_entrega = $envio_ae + $envio_ce;
			$envio_instalacion = $envio_ai + $envio_ci;

			$envio = max($envio_entrega,$envio_instalacion);
		}

		return $envio;
	}

	public function calcular_accesorios_individuales($cotizaciones_id=NULL, $cupones_id=FALSE)
	{

		$this->load->model('Accesorio');
		$accesorios = $this->Accesorio->get_accesorios_individuales($cotizaciones_id);

		if($accesorios)
		{
			$datos = array();
			$datos['accesorios_importe_total_cliente']=0;
			$datos['accesorios_subtotal_cliente']=0;
			$datos['accesorios_iva_cliente']=0;
			$datos['accesorios_total_cliente']=0;
			foreach ($accesorios as $k => $acc)
			{
				$accesorio_id= $cotizaciones_id?$acc->accesorios_id:$acc->id;
				$datos['importe_cliente_acc_individual'][$accesorio_id] = $acc->precio*$acc->cantidad;
				$datos['accesorios_importe_total_cliente']+=$acc->precio*$acc->cantidad;
				$datos['accesorios_subtotal_cliente']+=$acc->precio*$acc->cantidad;
			}

			/*$envio=0;
            $datos['accesorios_iva_cliente']=($datos['accesorios_subtotal_cliente'] + $envio ) * ( 16 / 100);
            $datos['accesorios_total_cliente'] = $datos['accesorios_subtotal_cliente'] + $envio + $datos['accesorios_iva_cliente'];*/

			return $datos;
		}
		return array();
	}

	public function categoria_pertenece_paquete($paquete_id,$categoria_id)
	{
		$this->db->where('paquetes_id',$paquete_id);
		$this->db->where('categorias_id',$categoria_id);
		$res=$this->db->count_all_results('paquetes_categorias');

		return $res?TRUE:FALSE;
	}

	public function descuentos_cliente($productos, $descuento_categoria = FALSE,$descuento_paquete=FALSE, $cupones_id = FALSE, $opcion_cupon_id=FALSE, $promocion_opcional=FALSE)
	{
		$paquete_adquirido = $this->paquete_adquirido($productos,$descuento_categoria,$descuento_paquete);

		$descuento = 0;
		if($descuento_paquete)
			$descuento = $this->base->value_get('paquetes',$paquete_adquirido,'descuento');

		$articulos_participantes = FALSE;
		if($cupones_id && $opcion_cupon_id==1)
		{
			$this->load->model('Cupon');
			$articulos_participantes = $this->Cupon->articulos_participantes_get($cupones_id);
		}

		$promociones_opcionales_ids=array();
		if($promocion_opcional)
		{
			foreach($promocion_opcional as $k=>$v)
				$promociones_opcionales_ids[]=$k;
		}
		$descuentos = array();
		foreach($productos as $id => $pro)
		{
			$descuento_final = !@$pro['sin_envio']?$descuento:0;
			$descuento_final = $promocion_opcional?0:$descuento_final;
			$producto = $this->base->read('productos', $id);
			if ($descuento_categoria)
			{
				$descuento_categoria = $this->base->read('productos_categorias', $producto->categorias_id);
				$descuento_opcional = $descuento_categoria->descuento_opcional;
				$descuento_final = $descuento_opcional > $descuento_final ? $descuento_opcional : $descuento;
			}

			$descuentos[$id] = array('cliente' => $descuento_final);

			if ($articulos_participantes && (in_array($producto->categorias_id, $articulos_participantes['categorias_cupon']) || in_array($id, $articulos_participantes['productos_cupon'])))
			{
				$descuentos[$id]['cliente_cupon'] = $descuento_final = $articulos_participantes['cupon']['porcentaje_descuento'];
				$descuentos[$id]['cliente'] = $descuento_final;
			}

			if($cupones_id && $opcion_cupon_id==2)
				$descuentos[$id]['cliente'] = 0;

			$descuentos[$id]['accesorios'] = array();
			if (isset($pro['accesorios']))
			{
				foreach ($pro['accesorios'] as $id_acc => $acc)
				{
					$descuentos[$id]['accesorios'][$id_acc] = !$acc['consumible'] ? array('cliente' => $descuento_final) : array('cliente' => 0);

					if ($articulos_participantes && !$acc['consumible'] && (in_array($producto->categorias_id, $articulos_participantes['categorias_cupon']) || in_array($id, $articulos_participantes['productos_cupon'])))
						$descuentos[$id]['accesorios'][$id_acc]['cliente_cupon'] = $articulos_participantes['cupon']['porcentaje_descuento'];
					elseif($articulos_participantes && $acc['consumible'] && in_array($id_acc, $articulos_participantes['consumibles_cupon']))
						$descuentos[$id]['accesorios'][$id_acc]['cliente_cupon'] = $articulos_participantes['cupon']['porcentaje_descuento'];

					if($cupones_id && $opcion_cupon_id==2)
						$descuentos[$id]['accesorios'][$id_acc]=0;
				}
			}
		}
		return $descuentos;
	}

	public function descuentos_distribuidor($cotizaciones_id=NULL,$productos_ids, $cupones_id=FALSE,$opcion_cupon_id=FALSE)
	{
		/*
           * DESCUENTOS DISTRIBUIDOR
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

		$categorias_ids=array();
		foreach($productos_ids as $producto_id)
			$categorias_ids[$producto_id] = $this->base->value_get('productos',$producto_id,'categorias_id');

		$descuentos=array();
		foreach($productos_ids as $p_id)
		{
			$descuentos[$p_id]['total'] = 0;
			// 1.- DESCUENTO BASE POR VENTA DE CATEGORÍA DE PRODUCTOS
			if($categorias_ids)
				$descuentos[$p_id]['total'] += $descuentos[$p_id]['base'] = $this->base->value_get('productos_categorias',$categorias_ids[$p_id],'descuento_base');
			//debug('BASE '.$descuentos[$p_id]['base']);
			// 2.- DESCUENTO DE VENTA ANUAL PARA EL DISTRIBUIDOR
			$descuentos[$p_id]['total'] += $descuentos[$p_id]['monto_anual'] = $this->base->value_get('cuentas',$cuentas_id,'descuento_monto');
			//debug('monto anual: '.$descuentos[$p_id]['monto_anual']);
			// 3.- DESCUENTO POR ESPACIO DE EXHIBICIÓN DE CATEGORÍA DE PRODUCTOS
			$this->db->select('categorias_id');
			$this->db->where('cuentas_id',$cuentas_id);
			$categorias=$this->db->get('cuentas_categorias')->result();

			if($categorias)
			{
				$suma=0;
				$descuentos_categorias=array();
				foreach ($categorias as $categoria_id)
					$suma += $this->base->value_get('productos_categorias',$categoria_id->categorias_id,'descuento_exhibicion');

				$descuentos[$p_id]['total'] += $descuentos[$p_id]['exhibicion_categoria'] = $suma;
				//debug('exhibicion categoria: '.$descuentos[$p_id]['exhibicion_categoria']);
			}

			// 4.- DESCUENTO POR EXHIBICIÓN DE PAQUETE
			$this->db->select('paquetes_id');
			$this->db->where('cuentas_id',$cuentas_id);
			$paquetes_ids=$this->db->get('cuentas_paquetes')->result();

			if($paquetes_ids)
			{
				$valor=0;
				foreach ($paquetes_ids as $paquete_id)
				{
					$exhibicion_paquete = $this->base->value_get('paquetes',$paquete_id->paquetes_id,'descuento_exhibicion');
					if($exhibicion_paquete > $valor)
						$valor = $exhibicion_paquete;
				}

				$descuentos[$p_id]['total'] += $descuentos[$p_id]['exhibicion_paquete'] = $valor;
				//debug('EXHIBICION PAQ: '.$descuentos[$p_id]['exhibicion_paquete']);
			}

			$desc = $this->value_get('cuentas',$cuentas_id,array('descuento_cooperacion','descuento_transicion','descuento_espacio'));

			// 5.- DESCUENTO POR ESPACIO DE EXHIBICIÓN
			if(isset($desc->descuento_espacio) && !empty($desc->descuento_espacio))
				$descuentos[$p_id]['total'] += $descuentos[$p_id]['espacio'] = $desc->descuento_espacio;
			//debug('espacio '.$descuentos[$p_id]['espacio']);
			// 6.- DESCUENTO POR COOPERACIÓN
			if(isset($desc->descuento_cooperacion) && !empty($desc->descuento_cooperacion))
				$descuentos[$p_id]['total'] += $descuentos[$p_id]['cooperacion'] = $desc->descuento_cooperacion;
			//debug('cooperacion '.$descuentos[$p_id]['cooperacion']);
			// 7.- DESCUENTO POR TRANSICIÓN
			if(isset($desc->descuento_transicion) && !empty($desc->descuento_transicion))
				$descuentos[$p_id]['total'] += $descuentos[$p_id]['transicion'] = $desc->descuento_transicion;
			//debug('transicion '.$descuentos[$p_id]['transicion']);

			// 8.- DESCUENTO POR CUPONES
			if($cupones_id && $opcion_cupon_id==1)
			{
				$this->load->model('Cupon');
				$articulos_participantes = $this->Cupon->articulos_participantes_get($cupones_id);

				if($articulos_participantes && (in_array($categorias_ids[$p_id], $articulos_participantes['categorias_cupon']) || in_array($p_id,$articulos_participantes['productos_cupon'])))
					$descuentos[$p_id]['total'] += $descuentos[$p_id]['cupon'] = $articulos_participantes['cupon']['descuento_distribuidor'];
			}

			// SI ES UN EVENTO NO APLICA DESCUENTO
			$evento = $this->base->get_dato('sin_envio','productos',array('id'=>$p_id));
			if($evento)
				$descuentos[$p_id]['total']=0;
		}

		return $descuentos;
	}

	public function paquetes_combinaciones_posibles($paquete_categorias,$productos)
	{
		$productos_categorias_ids=array();
		foreach($productos as $p_id=>$p)
		{
			if(!isset($productos_categorias_ids[$this->base->value_get('productos',$p_id,'categorias_id')]))
				$productos_categorias_ids[$this->base->value_get('productos',$p_id,'categorias_id')]=$p['cantidad'];
			else
				$productos_categorias_ids[$this->base->value_get('productos',$p_id,'categorias_id')]+=$p['cantidad'];
		}

		$indices=array();
		$i=0;
		foreach($paquete_categorias as $k=>$v)
		{
			$indices[$v->indice][$i]['categoria_id']=$v->categorias_id;
			$indices[$v->indice][$i]['cantidad']=$v->cantidad;
			$i++;
		}

		$existe_paquete=TRUE;
		foreach($indices as $i)
		{
			foreach($i as $cat)
			{
				if(array_key_exists($cat['categoria_id'],$productos_categorias_ids) && $cat['cantidad'] <= trim($productos_categorias_ids[$cat['categoria_id']]))
				{
					$existe_paquete=TRUE;
					break;
				}
				else
					$existe_paquete=FALSE;
			}
			if(!$existe_paquete)
				break;
		}
		return $existe_paquete;
	}

	public function paquete_adquirido($productos)
	{
		$this->db->where('eliminado',0);
		$this->db->order_by('descuento','DESC');
		$paquetes=$this->db->get('paquetes')->result();

		$paquete_adquirido=FALSE;
		foreach ($paquetes as $paquete)
		{
			$this->db->select('categorias_id,cantidad,indice');
			$this->db->where('eliminado',0);
			$this->db->where('paquetes_id',$paquete->id);
			$paquete_categorias=$this->db->get('paquetes_categorias')->result();

			$existe_paquete=$this->paquetes_combinaciones_posibles($paquete_categorias,$productos);
			if($existe_paquete)
			{
				$paquete_adquirido=$paquete->id;
				break;
			}
		}
		return $paquete_adquirido;
	}

	public function paquete_descuento_probable($productos)
	{
		// 1.- RECUPERO LAS CATEGORIAS Y CANTIDADES DE LOS PRODUCTOS DE LA COTIZACION
		$productos_categorias_ids=array();
		foreach($productos as $p_id=>$p)
		{
			if(!isset($productos_categorias_ids[$this->base->value_get('productos',$p_id,'categorias_id')]))
				$productos_categorias_ids[$this->base->value_get('productos',$p_id,'categorias_id')]=$p['cantidad'];
			else
				$productos_categorias_ids[$this->base->value_get('productos',$p_id,'categorias_id')]+=$p['cantidad'];
		}
		debug('CATEGORIAS Y CANTIDADES DE LA COTIZACION');
		debug($productos_categorias_ids);

		// 2.- CONSULTO LAS COMBINACIONES POSIBLES DE CADA PAQUETE
		//$this->db->select('id,nombre,');
		$this->db->where('eliminado',0);
		$this->db->order_by('id','ASC');
		$paquetes=$this->db->get('paquetes')->result();
		debug('PAQUETES');
		debug($paquetes);
		$paquetes_combinaciones=array();
		foreach ($paquetes as $paquete)
		{
			$this->db->select('categorias_id,cantidad,indice');
			$this->db->where('eliminado',0);
			$this->db->where('paquetes_id',$paquete->id);
			$paquete_categorias=$this->db->get('paquetes_categorias')->result();

			debug('CATEGORIAS DEL PAQUETE '.$paquete->id);
			debug($paquete_categorias);

			$combinacion=FALSE;
			while(!empty($paquete_categorias))
			{
				$i=0;
				$indice=0;

				foreach($paquete_categorias as $k=>$v)
				{
					if($indice!=$v->indice)
					{
						$paquetes_combinaciones[$paquete->id][$i][$v->categorias_id]=$v->cantidad;
						$indice=$v->indice;
						$i++;
					}
					else
					{
						if($combinacion)
						{
							debug('COMBINACION');
							debug($k);
							exit;
						}
					}

				}
				$combinacion=TRUE;
				//debug($paquetes_combinaciones);
			}
		}
		debug('PAQUETES COMBINACIONES');
		debug($paquetes_combinaciones);
		exit;
	}

	public function genera_comisiones($cotizaciones_id,$data,$productos,$venta_directa,$debug=FALSE)
	{
		$data['descuento_opcional']=1;
		$descuentos=$this->Descuento->calcular($cotizaciones_id,$data,$productos);

		$datos=array();
		$fecha = date("Y-m-d H:i:s");
		$datos['fecha_autorizacion'] = $fecha;
		if(!empty($descuentos['paquete_adquirido']['id']))
			$datos['descuento_paquete_id'] = $descuentos['paquete_adquirido']['id'];

		$datos['id'] = $cotizaciones_id;
		$this->base->guarda('cotizaciones',$datos);

		$subtotal=$descuentos['subtotal_distribuidor'];
        $subtotal_venta_directa=(isset($descuentos['descuento_comercial_distribuidor'])) ? $descuentos['descuento_comercial_distribuidor'] : 0;
		if($debug)debug($descuentos);
		//VENTA DIRECTA
		$referidos=$this->base->value_get('cotizaciones',$cotizaciones_id,array('referido_distribuidor_id','referido_porcentaje_comision'));if($debug)debug($referidos);

		// ELIMINA LAS COMISIONES ANTERIORES
		$datos=array();
		$datos['eliminado']=1;
		$this->db->where('cotizaciones_id',$cotizaciones_id);
		$this->db->update('comisiones',$datos);

		if($venta_directa)
		{
			//COMISIONES DEL DISTRIBUIDOR
			if(!empty($referidos))
			{
				$porcentaje_comision=$referidos->referido_porcentaje_comision?1:0.5;
				$porcentaje_distribuidor=(($subtotal_venta_directa*100)/$descuentos['importe_venta'])*$porcentaje_comision;
				$comision_referido = round( ( $descuentos['importe_venta']-$descuentos['descuento_comercial_cliente'] ) * ($porcentaje_distribuidor/100),2);

				$data=array();
				$data['status_id']=1;
				$data['cotizaciones_id']=$cotizaciones_id;
				$data['cuentas_id']=$referidos->referido_distribuidor_id;
				$data['porcentaje']=round($porcentaje_distribuidor,2);
				$data['monto']=$comision_referido;if($debug)debug($data);
				$this->base->guarda('comisiones',$data);
			}

			//COMISIONES DEL VENDEDOR
			$comision_vendedor=0;
			foreach($descuentos['comisiones_vendedor'] as $com)
				$comision_vendedor+=$com;

			if($descuentos['comisiones_vendedor_paquete'])
				$comision_vendedor+=$descuentos['comisiones_vendedor_paquete'];

			$porcentaje_vendedor=round(($comision_vendedor*100)/$descuentos['subtotal_cliente'],2);

			if($comision_vendedor && $porcentaje_vendedor)
			{
				$data=array();
				$data['status_id']=1;
				$data['cotizaciones_id']=$cotizaciones_id;
				$data['cuentas_id']=$referidos->referido_distribuidor_id;
				$data['usuarios_id']=$this->base->value_get('cotizaciones',$cotizaciones_id,'usuario_id');
				$data['porcentaje']=$porcentaje_vendedor;
				$data['monto']=$comision_vendedor;if($debug)debug($data);
				$this->base->guarda('comisiones',$data);
			}
		}

		//VENTA DE DISTRIBUIDOR

		//COMISIONES DEL DISTRIBUIDOR
		if($descuentos['comisiones_distribuidor'] > 0 && !$venta_directa)
		{
			$comision_venta=round($subtotal * ( $descuentos['comisiones_distribuidor'] / 100 ),2);

			$data=array();
			$data['status_id']=1;
			$data['cotizaciones_id']=$cotizaciones_id;
			$data['cuentas_id']=$this->base->value_get('cotizaciones',$cotizaciones_id,'cuentas_id');
			$data['porcentaje']=$descuentos['comisiones_distribuidor'];
			$data['monto']=$comision_venta;if($debug)debug($data);
			$this->base->guarda('comisiones',$data);
		}

		//COMISIONES DEL VENDEDOR
		$comision_vendedor=0;
		if(isset($descuentos['comisiones_vendedor']) && $descuentos['comisiones_vendedor'] > 0 && !$venta_directa)
		{
			foreach($descuentos['comisiones_vendedor'] as $com)
				$comision_vendedor+=$com;

			if($descuentos['comisiones_vendedor_paquete'])
				$comision_vendedor+=$descuentos['comisiones_vendedor_paquete'];

			$porcentaje_vendedor=round(($comision_vendedor*100)/$descuentos['subtotal_cliente'],2);

			if($comision_vendedor && $porcentaje_vendedor)
			{
				$data=array();
				$data['status_id']=1;
				$data['cotizaciones_id']=$cotizaciones_id;
				$data['cuentas_id']=$this->base->value_get('cotizaciones',$cotizaciones_id,'cuentas_id');
				$data['usuarios_id']=$this->base->value_get('cotizaciones',$cotizaciones_id,'usuario_id');
				$data['porcentaje']=$porcentaje_vendedor;
				$data['monto']=$comision_vendedor;if($debug)debug($data);
				$this->base->guarda('comisiones',$data);
			}
		}
	}
}