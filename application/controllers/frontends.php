<?php
require_once('main.php');
class Frontends extends Main {

	public function __construct()
	{
		parent::__construct();
		// PONER EN CONSTRUCCION MIELE SHOP
		//if(@$_SERVER['HTTP_HOST']=='shop.miele.com.mx')
		//  redirect('construccion');
		$this->load->model('Frontend');

		$interno=$this->session->userdata('logged');
		if($this->session->userdata('logged') && $this->session->userdata('cliente_externo'))
			$interno=FALSE;

		define('INTERNO',$interno);
	}

	private function _init_miele()
	{
		//INICIALIZA VARIABLES GENERALES
		$datos['categorias_menu']=$this->base->lista('productos_categorias','id','nombre',TRUE,'id','ASC',array('parent_id'=>NULL));

		// ACTIVAR LA MESA DE REGALOS
		$mesa_logged=$this->session->userdata('mesa');
		$datos['mesa_logged']=FALSE;
		if(!empty($mesa_logged))
		{
			$datos['mesa_logged']=TRUE;
			$this->load->model('Producto');
			$datos['mp_sel']=$this->Producto->get_mesa_productos_id($mesa_logged->id);
		}

		$datos['num_productos']=count($this->session->userdata('productos'));// NUMERO EN CARRITO
		$accesorios=$this->session->userdata('accesorios');
		if(!empty($accesorios))
			$datos['num_productos']+=count($this->session->userdata('accesorios'));// NUMERO EN CARRITO
		$datos['usuario']=$this->session->userdata('usuario');
		$cotizacion_id=$this->session->userdata('cotizaciones_id');

		if(isset($cotizacion_id) && $cotizacion_id!=NULL)
		{
			$this->load->model('Cotizacion');
			$status_id=$this->Cotizacion->get_status($this->session->userdata('cotizaciones_id'));
			if(in_array($status_id,array(2,3,4,5)) && $this->uri->segment(2)!='enviar_compra' && INTERNO)
			{
				$this->session->set_userdata('productos',array());
				$this->session->set_userdata('cotizaciones_id',NULL);
				$this->session->set_userdata('accesorios',array());
				$datos['num_productos']=0;
			}
		}

		return $datos;
	}

	public function index()
	{
		//HOME DEL FRONTEND PARA VISUALIZAR PAQUETES BANNERS Y PRODUCTOS
		$productos_front=$this->session->userdata('productos');
		if(empty($productos_front))
			$this->session->set_userdata('productos',array());

		$datos=$this->_init_miele();
		$datos['banners']=$this->Frontend->get_banner();

		$CI =& get_instance();
		$CI->load->model('Producto');
		foreach($datos['banners'] as &$b)
			$b->accesorios=$CI->Producto->get_accesorios_producto($b->productos_id);

		$this->load->model('Categoria');
		$this->load->model('Paquete');
		$this->load->model('Promocion');
		$res=$this->Categoria->get_categorias(FALSE,TRUE);
		foreach($res as &$r)
			$r->promocion=$this->Promocion->promocion_tiene($r->id);
		$datos['categorias']=$res;
		$datos['paquetes']=$this->Paquete->get_paquetes(4);
		$datos['productos_paquetes']=$this->base->lista('productos','id','nombre',TRUE,NULL,'ASC',array('ocultar'=>0));
		$datos['titulo']='Home';
		$this->load->view('frontend/home/index',$datos);
	}

	public function categorias($categorias_id)
	{
		$categoria = $this->base->get_datos('parent_id, activo', 'productos_categorias', array('id'=>$categorias_id));

		if(empty($categoria->activo))
		{
			$this->session->set_flashdata('error', 'La categoría ya no está disponible.');
			redirect('frontends/index');
		}

		if(!empty($categoria->parent_id))
		{
			$categoria_padre = $categoria->parent_id;
			while(!empty($categoria_padre))
			{
				$categoria_activa = $this->base->get_dato('activo', 'productos_categorias', array('id'=>$categoria_padre));
				if(empty($categoria_activa))
				{
					$this->session->set_flashdata('error', 'La categoría ya no está disponible.');
					redirect('frontends/index');
				}
				$categoria_padre = $this->base->get_dato('parent_id', 'productos_categorias', array('id'=>$categoria_padre));
			}
		}

		//PARA MOSTRAR SUBCATEGORIAS O PRODUCTOS DE UNA CATEGORIA EN LA NAVEGACION
		$datos=$this->_init_miele();
		$this->load->model('Categoria');
		$this->load->model('Promocion');

		$datos['categoria_promocion']=$this->Promocion->promocion_tiene($categorias_id,1);
		$datos['subcategorias']=$this->Categoria->get_subcategorias($categorias_id);
		$datos['r']=NULL;
		if(empty($datos['subcategorias']))
		{
			$res=$this->Frontend->get_productos($categorias_id);

			foreach($res as &$r)
				$r['promocion']=$this->Promocion->promocion_tiene($categorias_id,$r['id']);

			$datos['r'] = $res;
		}

		$datos['categorias_id']=$categorias_id;
		$datos['categoria']=$this->base->read('productos_categorias',$categorias_id);

		$orden = $datos['categoria']->video_orden?'_'.$datos['categoria']->video_orden:'';
		$path = (!empty($orden))? ($this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/categorias/{$datos['categoria']->id}{$orden}"):  site_url("files/categorias/{$datos['categoria']->id}{$orden}")):'';
		$path_play = site_url('img/categorias/play_icon.png');
		$path_play_hover = site_url('img/categorias/play_icon_over.png');

		if(!empty($path))
		{
			$video=TRUE;
			$datos['path_video'] = $path;
			$datos['path_play'] = $path_play;
			$datos['path_play_hover'] = $path_play_hover;
			$datos['categorias_id'] = $categorias_id;
		}else
			$video=FALSE;

		$datos['video']=$video;
		$datos['categorias'] = $this->Frontend->breadcumb_categorias($categorias_id);
		$datos['informacion_general'] = $this->base->get_dato('informacion_general','productos_categorias',array('id'=>$categorias_id));
		$datos['titulo']=$this->base->value_get('productos_categorias',$categorias_id,'nombre');
		$this->load->view('frontend/categorias/index',$datos);
	}

	public function cotizacion_nueva()
	{
		//GENERA NUEVA COTIZACION LIMPIANDO LA SESSION
		$this->session->set_userdata('accesorios',array());
		$this->Frontend->cotizacion_limpiar();
		$this->session->set_flashdata('done', 'Se ha iniciado una nueva cotizaci&oacute;n, seleccione de nuestro cat&aacute;logo los productos que desea comprar.');
		redirect('frontends/index');
	}

	public function cancelar_edicion()
	{
		$this->session->set_userdata('accesorios',array());
		$this->Frontend->cotizacion_limpiar();
		redirect('cotizaciones/index');
	}

	public function generar_cotizacion()
	{
		//GENERA NUEVA COTIZACION LIMPIANDO LA SESSION
		$this->session->set_userdata('generar_cotizacion',TRUE);
		redirect('frontends/cotizacion');
	}

	public function cotizacion_calculo($status_posterior=FALSE,$validar_descuento=FALSE)
	{
		//PARA CALCULO EN TIEMPO REAL DE LA COTIZACION
		$this->load->model('Cotizacion');
		$this->load->model('Promocion');
		$carrito = $this->session->userdata('productos');

		if(!empty($_POST['evento_estado']))
			$this->session->set_userdata('evento_estado',$_POST['evento_estado']);

		$evento_estado = (!empty($_POST['evento_estado']) ? $_POST['evento_estado'] : $this->session->userdata('evento_estado'));

		if(!empty($carrito))
		{
			// ELIMINO LOS PRODUCTOS Y ACCESORIOS QUE NO ESTÁN ACTIVOS
			$carrito_aux = $carrito;
			foreach ($carrito_aux AS $k => $p)
			{
				$producto_activo = $this->base->get_dato('activo', 'productos', array('id' => $k));
				if (!$producto_activo)
				{
					unset($carrito[$k]);
				}
				else
				{
					if (!empty($p['accesorios']))
					{
						foreach ($p['accesorios'] as $a => $acc)
						{
							$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $a));
							if (!$accesorio_activo)
								unset($carrito[$k]['accesorios'][$a]);
						}
					}
				}
			}
		}

		$productos  = array();
		$evento_calcular_precio = false;

		if(!empty($carrito))
		{
			foreach($carrito as $k => &$p)
			{
				$productos[$k] = array();
				$productos[$k] = $this->Cotizacion->get_producto($k, $p['accesorios'],TRUE);

				$categorias_id = $categorias_id=$this->Frontend->get_categorias_id($k);
				$p['promocion_opcional'] = $this->Promocion->promociones_opcionales_get($k,$categorias_id,TRUE);
				$productos[$k]['unidad'] = elemento('Unidades',$p['unidad_id']);
				$p['precio'] = $productos[$k]['precio'];
				$productos[$k]['cantidad'] = $p['cantidad'];

               	if(!empty($evento_estado) && $p['unidad_id']==2)
                {
                    $postfijo = strtolower(str_replace(' ','_',$evento_estado));
                    $evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', horas_maximas_'.$postfijo.',precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$k));
                    $p['precio'] = $productos[$k]['precio'] = $evento->{'precio_inicial_'.$postfijo};
					$p['cantidad'] = $productos[$k]['cantidad'] = (@$_POST['cotizaciones_id'])?($this->base->get_dato('cantidad','cotizaciones_productos',array('cotizaciones_id'=>$_POST['cotizaciones_id'], 'productos_id'=>$k))):((@$carrito[$k]['cantidad'])?$carrito[$k]['cantidad']:$evento->{'horas_iniciales_'.$postfijo});

					$productos[$k]['horas_minimo'] = $evento->{'horas_iniciales_' . $postfijo};
					$productos[$k]['horas_maximo'] = $evento->{'horas_maximas_' . $postfijo};
                }
			}

			$i=0;

			foreach ($_POST['productos_ids'] as $k=>$v)
			{
				if(isset($carrito[$v]))
				{
					if($carrito[$v]['unidad_id']!=2)
					{
					    $carrito[$v]['cantidad'] = $productos[$v]['cantidad'] = (!empty($_POST['productos_cantidades'][$k]) && is_numeric($_POST['productos_cantidades'][$k]))?$_POST['productos_cantidades'][$k]:$this->base->get_dato('cantidad','cotizaciones_productos',array('cotizaciones_id'=>$_POST['cotizaciones_id'], 'productos_id'=>$v));
						$carrito[$v]['cantidad'] = (!empty($carrito[$v]['cantidad']))?$carrito[$v]['cantidad']:1;
					}else
					{
						if(empty($_POST['productos_cantidades'][$k]) || $_POST['productos_cantidades'][$k] < $carrito[$v]['horas_minimo'])
						    $carrito[$v]['cantidad'] = $productos[$v]['cantidad'] = $carrito[$v]['horas_minimo'];
						else
							$carrito[$v]['cantidad'] = $productos[$v]['cantidad'] = $_POST['productos_cantidades'][$k];

						if(@$_POST['cotizaciones_id'] || $evento_estado)
						{
							$entrega_estado = (@$evento_estado)?$evento_estado:$this->base->get_dato('entrega_estado','cotizaciones',array('id'=>$_POST['cotizaciones_id']));
							$postfijo = strtolower(str_replace(' ','_',$entrega_estado));
							$evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', horas_maximas_'.$postfijo.', precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$v));
							$cantidad = (!empty($_POST['productos_cantidades'][$k]) && is_numeric($_POST['productos_cantidades'][$k]))?$_POST['productos_cantidades'][$k]:$this->base->get_dato('cantidad','cotizaciones_productos',array('cotizaciones_id'=>$_POST['cotizaciones_id'], 'productos_id'=>$v));

							$carrito[$v]['horas_minimo'] = $evento->{'horas_iniciales_' . $postfijo};
							$carrito[$v]['horas_maximo'] = $evento->{'horas_maximas_' . $postfijo};

							if($cantidad >= $evento->{'horas_iniciales_'.$postfijo})
							{
								$horas_iniciales = $evento->{'horas_iniciales_'.$postfijo};
								$diferencia = $cantidad - $horas_iniciales;
								$total_diferencia = $diferencia *  $evento->{'precio_horas_extra_'.$postfijo};
								$carrito[$v]['cantidad'] =  $productos[$v]['cantidad'] = $cantidad;
								$carrito[$v]['precio'] = $productos[$v]['precio'] = $evento->{'precio_inicial_'.$postfijo};
								$carrito[$v]['unidad_id'] = 2;
								$carrito[$v]['unidad'] = 'Hora(s)';
							}else
							{
								$carrito[$v]['cantidad'] =  $productos[$v]['cantidad'] = $evento->{'horas_iniciales_'.$postfijo};
								$carrito[$v]['precio'] = $productos[$v]['precio'] = $evento->{'precio_inicial_'.$postfijo};
								$carrito[$v]['unidad_id'] = 2;
								$carrito[$v]['unidad'] = 'Hora(s)';
							}

							$evento_calcular_precio = true;
						}
					}
					$carrito[$v]['sin_envio'] =  $productos[$v]['sin_envio'];

					$x=0;
					if(!empty($productos[$v]['accesorios']))
					{
						foreach ($productos[$v]['accesorios'] as $acc=>$a)
						{
							$productos[$v]['accesorios'][$x]['cantidad']=isset($_POST['accesorios_cantidades'][$i])?$_POST['accesorios_cantidades'][$i]:1;
							$carrito[$v]['accesorios'][$a['id']]['cantidad']=isset($_POST['accesorios_cantidades'][$i])?$_POST['accesorios_cantidades'][$i]:1;
							$carrito[$v]['accesorios'][$a['id']]['precio']=$a['precio'];
							$i++;
							$x++;
						}
					}
				}
			}

			$this->session->unset_userdata('productos');
			$this->session->set_userdata('productos', $carrito);
		}

		$cotizaciones_id = @$_POST['cotizaciones_id']?$_POST['cotizaciones_id']:NULL;
		$accesorios = $this->session->userdata('accesorios');

		if(!empty($accesorios))
		{
			// ELIMINO ACCESORIOS Y CONSUMIBLES INDIVIDUALES QUE NO ESTÁN ACTIVOS
			$accesorios_aux = $accesorios;
			foreach ($accesorios_aux as $a => $acc)
			{
				$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $a));
				if (!$accesorio_activo)
					unset($accesorios[$a]);
			}
		}

		if($accesorios)
		{
			foreach ($_POST['acc_individuales_ids'] as $k => $v)
			{
				if(isset($accesorios[$v]))
				{
					if($cotizaciones_id)
						$accesorios[$v]->cantidad = isset($_POST['acc_individuales_cantidades'][$k]) ? $_POST['acc_individuales_cantidades'][$k] : 1;
					else
						$accesorios[$v]->cantidad = isset($_POST['acc_individuales_cantidades'][$k]) ? $_POST['acc_individuales_cantidades'][$k] : 1;
				}
			}

			//$this->session->unset_userdata('accesorios');
			$this->session->set_userdata('accesorios', $accesorios);
		}

		if(@$_POST['cupones_id'] && @$_POST['folio_cupon'] && @$_POST['opcion_cupon_id'] && @$_POST['descuento_cupon'])
		{
			$cupon = $this->session->userdata('cupon');
			$cupon ['opcion_cupon_id'] = $_POST['opcion_cupon_id'];
			$cupon ['descuento_cupon'] = $_POST['descuento_cupon'];
			$this->session->set_userdata('cupon', $cupon);
		}

		$data['accesorios_individuales']=NULL;
		if($this->session->userdata('accesorios'))
		{
			$this->load->model('Accesorio');
			$data['accesorios_individuales'] = $this->Accesorio->get_accesorios_individuales($cotizaciones_id);
			if(!empty($data['accesorios_individuales']))
			{
				// ELIMINO ACCESORIOS Y CONSUMIBLES INDIVIDUALES QUE NO ESTÁN ACTIVOS
				$accesorios_aux = $data['accesorios_individuales'];
				foreach ($accesorios_aux as $a_aux => $acc_aux)
				{
					$a_id=is_array($acc_aux)?$a_aux:(isset($acc_aux->accesorios_id)?$acc_aux->accesorios_id:$acc_aux->id);
					$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $a_id));
					if (!$accesorio_activo)
						unset($data['accesorios_individuales'][$a_aux]);
				}
			}
		}

		$data['banamex_msi_vigente']=$banamex_msi_vigente=strtotime($this->config->item('banamex_msi_vigencia'))>strtotime(date('Y-m-d H:i:s'));
		if(@$_POST['promocion_msi']==18 && !$banamex_msi_vigente)
			$_POST['promocion_msi']=0;

		$data['calculo']=$this->calcular($cotizaciones_id,$_POST,FALSE);

		if(@$_POST['cupones_id'] && @$_POST['folio_cupon'])
		{
			$producto_regalo_id = $this->base->get_dato('producto_regalo_id','cupones_folios', array('cupones_id'=>$_POST['cupones_id'], 'folio'=>$_POST['folio_cupon']));

			if(!empty($producto_regalo_id))
			{
				$this->load->model('Producto');
				$data['producto_regalo'] = $this->base->get_datos('id, nombre, modelo, precio, unidad_id','productos', array('id'=>$producto_regalo_id));
				$data['producto_regalo']->img_id = $this->Producto->get_imagen_principal($producto_regalo_id);
				$data['producto_regalo']->cantidad = 1;
				$data['producto_regalo']->unidad = elemento('Unidades', $data['producto_regalo']->unidad_id);

				$cupon = $this->session->userdata('cupon');
				$cupon ['producto_regalo_id'] = $producto_regalo_id;
				$this->session->set_userdata('cupon', $cupon);
			}
		}

		$msi_banamex = @$_POST['promocion_msi']==18 && $banamex_msi_vigente?TRUE:FALSE;
		$data['promociones']=array();
		$promocion_opcional = $this->session->userdata('promocion_opcional');//debug($promocion_opcional);
		if(@$_POST['promocion_msi'] || @$_POST['descuento_cupon'])//if(@$_POST['descuento_opcional'] || @$_POST['descuento_paquete'] || @$_POST['promocion_msi'] || @$_POST['descuento_cupon'])
			$promocion_opcional=FALSE;
		$data['promociones']=$this->Promocion->get_promociones($carrito,$data['accesorios_individuales'], $data['calculo']['importe_venta'],$msi_banamex,$promocion_opcional);//debug($data['promociones']);

		$this->session->set_userdata('promociones',$data['promociones']);
		$data['status_id']=$status_id=$_POST['status'];
		$promociones_id=(int)$this->session->userdata('promociones_id');
		if(!array_key_exists($promociones_id,$data['promociones']) && count($data['promociones'])>1 && (!$status_id || ($status_id && $status_id==1)))
		{
			$data['promocion_change']=1;
		}
		elseif(!array_key_exists($promociones_id,$data['promociones']) && count($data['promociones'])==1 && (!$status_id || ($status_id && $status_id==1)))
		{
			$promo_id=array_keys($data['promociones']);
			$promociones_id=$promo_id[0];
			$this->session->set_userdata('promociones_id',$promociones_id);
		}

		$data['promociones_productos']=array();
		if($promociones_id && !empty($data['promociones']) && array_key_exists($promociones_id,$data['promociones']))
		{
			$data['promociones_id']=$promociones_id;
			$this->promocion_regalos_add($cotizaciones_id);
			$data['promociones_productos'] = $this->session->userdata('promociones_productos');
			$data['promociones_alianzas']= $this->session->userdata('promociones_alianzas');
		}

		$_POST['promociones_id']=$promociones_id;
		$promocion_opcional_regalos=array();
		if($promocion_opcional)
			$promocion_opcional_regalos = $this->Promocion->regalos_get($promociones_id);
		$_POST['promocion_opcional']=$promocion_opcional_regalos?$promocion_opcional_regalos:array();//debug($_POST['promocion_opcional']);
		$data['calculo'] = $this->calcular($cotizaciones_id,$_POST,FALSE);

		if(@$_POST['rescate_sucursal'])
			$data['calculo']['rescate_sucursal']=1;

		$data['cupones_opciones'] = NULL;
		if(@$_POST['descuento_cupon'] && @$_POST['cupones_id'])
		{
			$this->load->model('Cupon');
			$data['cupones_opciones'] = $this->Cupon->opciones_get($_POST['cupones_id']);
		}

		if($validar_descuento)
		{
			$msj=json_encode($data['calculo']);
			echo $msj;exit;
		}

		$prods = new stdClass();
		foreach($productos as $prod=>$pr)
		{
			foreach($pr as $k=>$v)
			{
				@$prods->$prod->$k=$v;
				$categorias_id = $categorias_id = $this->Frontend->get_categorias_id($prod);
				@$prods->$prod->promocion_opcional = $this->Promocion->promociones_opcionales_get($prod,$categorias_id,TRUE);
			}
		}

		foreach($prods as $prod=>$pr)
		{
			$accesorios=new stdClass();
			foreach($pr->accesorios as $acc=>$a)
			{
				$accesorio=new stdClass();
				foreach($a as $k=>$v)
					$accesorio->$k=$v;

				$accesorios->$acc=$accesorio;
			}

			$pr->accesorios=$accesorios;
		}

		$cuentas_id = $cotizaciones_id ? $this->base->get_dato('cuentas_id','cotizaciones',array('id'=>$cotizaciones_id)):$this->session->userdata('cuentas_id');
		$data['venta_directa']=$this->Cotizacion->es_venta_directa($cuentas_id);
		$data['status_posterior']=$status_posterior;//PARA SABER SI PUEDE EDITAR CANTIDADES DE LOS PRODUCTOS
		$data['aplicar_cupon']=$this->base->tiene_permiso('cupones');
		$data['descuento_cupon']=@$_POST['descuento_cupon'];
		$data['rescate_sucursal']=@$_POST['rescate_sucursal'];
		if(@$_POST['rescate_sucursal'])
			$data['envio']=0;
		$data['carrito'] = $productos;
		//$data['promociones_id'] = $data['calculo']['promocion']=$promociones_id;
		$data['productos_sesion']=$prods;
		$data['cot_id'] = $data['cotizaciones_id'] = $this->session->userdata('cotizaciones_id')?$this->session->userdata('cotizaciones_id'):0;
		$data['recibo_pago_cdn']=$this->base->get_dato('recibo_pago_cdn','cotizaciones',array('id'=>$cotizaciones_id));
		$data['evento_calcular_precio'] = (@$data['calculo']['evento_calcular_precio'])?$data['calculo']['evento_calcular_precio']:$evento_calcular_precio;

		$this->load->view('frontend/cotizacion_tabla',$data);
	}

	public function cotizacion($cotizaciones_id=NULL,$agregar_nuevo=FALSE, $promocion_change=FALSE)
	{
		//GUARDA Y CALCULA UNA COTIZACION
		$this->load->model('Cotizacion');
		$this->load->model('Referido');
		$this->load->model('Sepomex_model','SP');
		$this->load->model('Accesorio');
		$this->load->model('Promocion');

		if($cotizaciones_id && INTERNO)
			$this->session->set_userdata('generar_cotizacion',TRUE);

		// Revisa de donde se puede tomar el dato del estado
		if(!empty($_POST['evento_estado']))
			$evento_estado = $_POST['evento_estado'];
		elseif(!empty($cotizaciones_id))
			$evento_estado = $this->base->get_dato('evento_estado', 'cotizaciones', array('id' => $cotizaciones_id));

		if(empty($evento_estado))
			$evento_estado = $this->session->userdata('evento_estado');

		$accesorios=array();
		$promociones_productos=array();
		$promociones_alianzas=array();

		// Aqui se carga la cotizacion a la sesion
		if($cotizaciones_id && !$agregar_nuevo && empty($_POST))
		{
			$this->session->set_userdata('productos',array());
			$this->session->set_userdata('cotizaciones_id',$cotizaciones_id);
			$productos_cotizacion = $this->Cotizacion->get_productos_ids($cotizaciones_id);

			if(!empty($productos_cotizacion))
			{
				// ELIMINO LOS PRODUCTOS Y ACCESORIOS QUE NO ESTÁN ACTIVOS
				$productos_cotizacion_aux = $productos_cotizacion;
				foreach ($productos_cotizacion_aux AS $k => $p)
				{
					$producto_activo = $this->base->get_dato('activo', 'productos', array('id' => $p));
					if (!$producto_activo)
					{
						unset($productos_cotizacion[$k]);
					}
				}
			}

			// Cantidades y precios de la cotizacion
			$productos  = array();
			foreach($productos_cotizacion as $k => $p)
			{
				$cotizacion_producto = $this->Cotizacion->get_cotizacion_producto($cotizaciones_id, $p,TRUE);
				$cotizacion_accesorios = $this->Cotizacion->get_cotizacion_accesorios($cotizaciones_id, $p, FALSE, TRUE);

				if(!empty($cotizacion_accesorios))
				{
					// ELIMINO LOS ACCESORIOS Y CONSUMIBLES QUE NO ESTÁN ACTIVOS
					$cotizacion_accesorios_aux = $cotizacion_accesorios;
					foreach ($cotizacion_accesorios_aux AS $a => $acc)
					{
						$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $acc->accesorios_id));
						if (!$accesorio_activo)
						{
							unset($cotizacion_accesorios[$a]);
						}
					}
				}

				$productos[$p] = array();

				$productos[$p]['precio']     = $cotizacion_producto->precio;
				$productos[$p]['cantidad']   = $cotizacion_producto->cantidad;

				if(@$cotizacion_producto->unidad_id==2)
				{
					$postfijo = strtolower(str_replace(' ','_',$evento_estado));
					$evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', horas_maximas_'.$postfijo.', precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$p));
					$productos[$p]['precio'] = $evento->{'precio_inicial_' . $postfijo};
					$productos[$p]['cantidad'] = (@$productos[$p]['cantidad']!=1) ? $productos[$p]['cantidad'] : $evento->{'horas_iniciales_' . $postfijo};

					$productos[$p]['horas_minimo'] = $evento->{'horas_iniciales_' . $postfijo};
					$productos[$p]['horas_maximo'] = $evento->{'horas_maximas_' . $postfijo};
				}

				$productos[$p]['unidad_id'] = $cotizacion_producto->unidad_id;
				$productos[$p]['unidad'] = elemento('Unidades',$cotizacion_producto->unidad_id);
				$productos[$p]['descuento_cliente']  = $cotizacion_producto->descuento_cliente;
				$productos[$p]['importe_cliente']    = $cotizacion_producto->importe_cliente;
				$productos[$p]['descuento_distribuidor']  = $cotizacion_producto->descuento_distribuidor;
				$productos[$p]['importe_distribuidor']    = $cotizacion_producto->importe_distribuidor;
				$productos[$p]['sin_envio']    = $cotizacion_producto->sin_envio;
				$categorias_id = $categorias_id=$this->Frontend->get_categorias_id($p);
				$productos[$p]['promocion_opcional'] = $this->Promocion->promociones_opcionales_get($p,$categorias_id,TRUE);
				$productos[$p]['accesorios'] = array();
				foreach($cotizacion_accesorios as $accesorio)
				{
					if(!empty($accesorio->accesorios_id))
					{
						$tipo_accesorio_id=$this->base->value_get('accesorios',$accesorio->accesorios_id,'tipos_accesorios_id');
						$productos[$p]['accesorios'][$accesorio->accesorios_id] = array();
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['precio'] = $accesorio->precio;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['cantidad'] = $accesorio->cantidad;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['descuento_cliente'] = $cotizacion_producto->descuento_cliente;//$accesorio->descuento_cliente;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['importe_cliente'] = $accesorio->importe_cliente;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['descuento_distribuidor'] = $accesorio->descuento_distribuidor;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['importe_distribuidor'] = $accesorio->importe_distribuidor;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['obligatorio'] = $this->Cotizacion->accesorio_obligatorio($p,$tipo_accesorio_id);
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['consumible'] = $accesorio->consumible;
					}
				}
			}
			$this->session->set_userdata('productos', $productos);

			$accesorios =  $this->Accesorio->get_accesorios_individuales($cotizaciones_id);

			if(!empty($accesorios))
			{
				// ELIMINO LOS ACCESORIOS Y CONSUMIBLES INDIVIDUALES QUE NO ESTÁN ACTIVOS
				$accesorios_aux = $accesorios;
				foreach ($accesorios_aux AS $a => $acc)
				{
					$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $acc->accesorios_id));
					if (!$accesorio_activo)
					{
						unset($accesorios[$a]);
					}
				}
			}

			$this->session->set_userdata('accesorios', $accesorios);
		}

		$data=$this->_init_miele();
		$carrito = $this->session->userdata('productos');

		$productos = array();
		if(!empty($carrito))
		{
			$i=0;
			foreach($carrito as $k => $p)
			{
				$producto = $this->Cotizacion->get_producto($k, @$p['accesorios']);
				$producto->cantidad = $p['cantidad'];
				$producto->precio = $p['precio'];
				$producto->unidad = elemento('Unidades',$producto->unidad_id);
				$producto->descuento_cliente = isset($p['descuento_cliente'])?$p['descuento_cliente']:0;
				$producto->importe_cliente = isset($p['importe_cliente'])?$p['importe_cliente']:0;
				$producto->descuento_distribuidor = isset($p['descuento_distribuidor'])?$p['descuento_distribuidor']:0;
				$producto->importe_distribuidor = isset($p['importe_distribuidor'])?$p['importe_distribuidor']:0;
				$categorias_id = $categorias_id=$this->Frontend->get_categorias_id($k);
				$producto->promocion_opcional = $this->Promocion->promociones_opcionales_get($k,$categorias_id,TRUE);
				$productos[] = $producto;
			}
		}

		if($this->session->userdata('accesorios'))
		{
			$accesorios =  $this->Accesorio->get_accesorios_individuales($cotizaciones_id);

			if(!empty($accesorios))
			{
				//$this->session->unset_userdata('accesorios');
				// ELIMINO LOS ACCESORIOS Y CONSUMIBLES INDIVIDUALES QUE NO ESTÁN ACTIVOS
				$accesorios_aux = $accesorios;
				foreach ($accesorios_aux AS $a => $acc)
				{
					$acc_id = isset($acc->accesorios_id)?$acc->accesorios_id:$acc->id;
					$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $acc_id));
					if (!$accesorio_activo)
					{
						unset($accesorios[$a]);
					}
				}
			}
		}

		$data['accesorios_individuales'] = $accesorios;

		if(!empty($_POST))
		{
			// VALIDA LOS ACCESORIOS REQUERIDOS
			if($this->form_validation->run('cotizaciones'))
			{
				$cuentas_id = $cotizaciones_id?$this->base->get_dato('cuentas_id','cotizaciones',array('id'=>$cotizaciones_id)):FALSE;
				$datos = array(
					'cuentas_id'=>$cuentas_id?$cuentas_id:$this->session->userdata('cuentas_id'),
					'fecha'=>date('Y-m-d'),
					'nombre_comprador'=>$this->input->post('nombre_comprador'),
					'paterno_comprador'=>$this->input->post('paterno_comprador'),
					'materno_comprador'=>$this->input->post('materno_comprador'),
					'email_comprador'=>$this->input->post('email_comprador'),
					'telefono_comprador'=>$this->input->post('telefono_comprador'),
					'usuario_id'=>$this->session->userdata('id'),
					'observaciones'=>$this->input->post('observaciones'),
					'referido_distribuidor_id'=>$this->input->post('referido_distribuidor_id'),
					'referido_vendedor_nombre'=>$this->input->post('referido_vendedor_nombre'),
					'referido_vendedor_paterno'=>$this->input->post('referido_vendedor_paterno'),
					'referido_vendedor_materno'=>$this->input->post('referido_vendedor_materno'),
					'entrega_estado'=>$this->input->post('entrega_estado'),
					'instalacion_estado'=>$this->input->post('instalacion_estado'),
					'vendedor_nombre'=>$this->input->post('vendedor_nombre'),
					'vendedor_paterno'=>$this->input->post('vendedor_paterno'),
					'vendedor_materno'=>$this->input->post('vendedor_materno'),
					'descuento_opcional'=>$this->input->post('descuento_opcional'),
					'descuento_paquete'=>$this->input->post('descuento_paquete'),
					'producto_regalo_id'=>$this->input->post('producto_regalo_id'),
					'cupones_id'=>$this->input->post('cupones_id'),
					'descuento_cupon'=>$this->input->post('descuento_cupon'),
					'folio_cupon'=>$this->input->post('folio_cupon'),
					'opcion_cupon_id'=>$this->input->post('opcion_cupon_id'),
					'descuento_porcentaje_cupon'=>$this->input->post('opcion_cupon_id')==1?$this->base->get_dato('porcentaje_descuento','cupones',array('id'=>$this->input->post('cupones_id'))):NULL,
					'msi_cupon'=>$this->input->post('opcion_cupon_id')==2?$this->base->get_dato('meses_sin_intereses','cupones',array('id'=>$this->input->post('cupones_id'))):NULL,
					'promociones_id'=>$this->input->post('promociones_id'),
					'rescate_sucursal'=>$this->input->post('rescate_sucursal'),
					'evento_estado'=>$evento_estado,
					'ofrecio_evento'=>$this->input->post('ofrecio_evento')
				);

				if($this->input->post('rescate_sucursal'))
					$datos['envio']=0;

				if($cotizaciones_id)
					$folio_existente = $this->base->value_get('cotizaciones', $cotizaciones_id, 'folio_cuentas');

				foreach($productos as $k => &$p)
				{
					$p->cantidad = $_POST['cantidad_productos'][$p->id];
					foreach($p->accesorios as &$a)
						$a->cantidad = $_POST['cantidad_accesorios'][$p->id][$a->id];
				}

				foreach($accesorios as $k=>&$acc)
				{
					$accesorio_id = @$cotizaciones_id?$acc->accesorios_id:$acc->id;
					$acc->cantidad = $_POST['cantidad_accesorios_individuales'][$accesorio_id];
				}

				$promocion_opcional=array();//debug($this->session->userdata('promocion_opcional'));
				if($this->session->userdata('promocion_opcional'))
					$promocion_opcional=$this->Promocion->regalos_get($this->input->post('promociones_id'));
				$_POST['promocion_opcional']=$promocion_opcional?$promocion_opcional:array();

				$cot_id=$this->Cotizacion->guarda(mayuscula_array($datos), $productos, $accesorios);

				if(!empty($datos['cupones_id']) && !empty($datos['folio_cupon']))
				{
					// Actualiza las tablas cupones_folios
					$data=array();
					$data['usado']=1;
					$this->db->where(array('cupones_id'=>$datos['cupones_id'], 'folio'=>$datos['folio_cupon']));
					$this->db->update('cupones_folios', $data);
				}

				$_POST['promocion_msi']=0;
				if(@$_POST['promocion_msi_amex'])
					$_POST['promocion_msi']=$_POST['promocion_msi_amex'];
				elseif(@$_POST['promocion_msi_banamex'])
					$_POST['promocion_msi']=$_POST['promocion_msi_banamex'];

				if($cot_id)
				{
					$data['calculo']=$this->calcular($cot_id,$_POST,TRUE);
				}

				$folio=$this->Cotizacion->genera_folio($cot_id,$folio_existente);
				$this->Frontend->cotizacion_limpiar();
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('cotizaciones/index');
			}
			else
			{
				$msg='Verifique los datos.';
				$data['flashdata']['error'] = $msg;
				$data['r']=$_POST;

				if(!$cotizaciones_id)
					$data['r']['cuentas_id']=$this->session->userdata('cuentas_id');

				if($cotizaciones_id)
				{
					$totales=$this->base->value_get('cotizaciones',$cotizaciones_id,array('importe_total','descuento_cliente','descuento_distribuidor','descuento_paquete_distribuidor','subtotal_cliente','subtotal_distribuidor','envio','iva_cliente','iva_distribuidor','total_cliente','total_distribuidor','cuentas_id'));
					foreach($totales as $k=>$v)
						$data['r'][$k]=$v;
				}

				$data['municipios']=(@$_POST['entrega_estado']) ? $this->SP->get_municipios($_POST['entrega_estado']) : NULL;
				$data['productos_sesion']=$productos;
				$data['venta_directa']=$this->Cotizacion->es_venta_directa($this->session->userdata('cuentas_id'));

				$data['cupones_opciones'] = NULL;
				if(@$_POST['descuento_cupon'] && @$_POST['cupones_id'])
				{
					$this->load->model('Cupon');
					$data['cupones_opciones'] = $this->Cupon->opciones_get($_POST['cupones_id']);
				}
			}
		}
		else
		{
			$data['venta_directa'] = $this->Cotizacion->es_venta_directa($this->session->userdata('cuentas_id'));
			if($cotizaciones_id)
			{
				$data['r']=$this->base->read('cotizaciones',$cotizaciones_id,TRUE);
				$data['municipios']=$this->SP->get_municipios($data['r']['entrega_estado']);
				//$data['status']=$this->Cotizacion->get_status($cotizaciones_id);
				$data['venta_directa']=$this->Cotizacion->es_venta_directa($data['r']['cuentas_id']);

				$data['cupones_opciones'] = NULL;
				if($data['r']['descuento_cupon'] && $data['r']['cupones_id'])
				{
					$this->load->model('Cupon');
					$data['cupones_opciones'] = $this->Cupon->opciones_get($data['r']['cupones_id']);
				}

				if(@$data['r']['promocion_opcional_descuento'])
					$this->session->set_userdata('promocion_opcional',$data['r']['promociones_id']);
			}

			$data['productos_sesion'] = $productos;
		}

		if((!empty($_POST['folio_cupon']) && !empty($_POST['cupones_id']) || !empty($data['r']['folio_cupon']) && !empty($data['r']['cupones_id'])))
		{
			$cupones_id = !empty($_POST['cupones_id']) ? $_POST['cupones_id'] : $data['r']['cupones_id'];
			$folio_cupon = !empty($_POST['folio_cupon']) ? $_POST['folio_cupon'] : $data['r']['folio_cupon'];

			$producto_regalo_id = $this->base->get_dato('producto_regalo_id', 'cupones_folios', array('cupones_id' => $cupones_id, 'folio' => $folio_cupon));

			if ($producto_regalo_id)
			{
				$this->load->model('Producto');
				$data['producto_regalo'] = $this->base->get_datos('id, nombre, modelo, precio, unidad_id', 'productos', array('id' => $producto_regalo_id));
				$data['producto_regalo']->img_id = $this->Producto->get_imagen_principal($producto_regalo_id);
				$data['producto_regalo']->cantidad = 1;
				$data['producto_regalo']->unidad = elemento('Unidades', $data['producto_regalo']->unidad_id);
			}
		}

		$data['carrito'] = $carrito;
		$data['accesorios_sesion']=$this->session->userdata('accesorios');
		$data['estados']=$this->SP->get_estados();
		$data['productos']=$this->base->lista('productos','id','modelo',TRUE,NULL,'ASC',array('ocultar'=>0));
		$data['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$data['vendedores']=$this->Cotizacion->get_vendedores();
		$data['distribuidores'] = $this->Referido->get_distribuidores();
		//$data['cupones_cuentas'] = $this->Frontend->cupones_cuentas();
		$data['tipo_moneda']=catalogo('tipo_moneda',FALSE);
		$data['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');
		$data['cuentas']=$this->base->lista('cuentas','id','nombre',TRUE,'nombre','ASC');
		$data['cotizaciones_id']=$cotizaciones_id;
		$data['cot_id']=$cotizaciones_id?$cotizaciones_id:0;

		if($cotizaciones_id)
			$data['status_id']=$this->Cotizacion->get_status($cotizaciones_id);

		$post['entrega_estado']=isset($data['r']['entrega_estado'])?$data['r']['entrega_estado']:FALSE;
		$post['instalacion_estado']=isset($data['r']['instalacion_estado'])?$data['r']['instalacion_estado']:FALSE;
		$post['descuento_opcional']=isset($data['r']['descuento_opcional'])?$data['r']['descuento_opcional']:0;
		$post['descuento_paquete']=isset($data['r']['descuento_paquete'])?$data['r']['descuento_paquete']:0;
		$post['descuento_cupon']=isset($data['r']['descuento_cupon'])?$data['r']['descuento_cupon']:0;
		$post['cupones_id']=isset($data['r']['cupones_id'])?$data['r']['cupones_id']:0;
		$post['opcion_cupon_id']=isset($data['r']['opcion_cupon_id'])?$data['r']['opcion_cupon_id']:0;
		$post['promocion_msi']=isset($data['r']['promocion_msi'])?$data['r']['promocion_msi']:0;

		$data['banamex_msi_vigente']=$banamex_msi_vigente=strtotime($this->config->item('banamex_msi_vigencia'))>strtotime(date('Y-m-d H:i:s'));
		if($post['promocion_msi']==18 && !$banamex_msi_vigente)
			$post['promocion_msi']=0;

		if(!INTERNO)
		{
			$post['descuento_opcional']=1;
			$post['descuento_paquete']=1;
			$post['sin_envio']=1;
		}

		$descuento = $this->calcular($cotizaciones_id,$post);

		$msi_banamex = @$_POST['promocion_msi']==18 && $banamex_msi_vigente?TRUE:FALSE;
		$data['promocion_opcional']=$promocion_opcional = $this->session->userdata('promocion_opcional');//debug($promocion_opcional);
		$data['promociones']=$this->Promocion->get_promociones($data['carrito'], $data['accesorios_sesion'], $descuento['importe_venta'],$msi_banamex,$promocion_opcional);
		if(!$this->session->userdata('promociones'))
			$this->session->set_userdata('promociones',$data['promociones']);

		if(@!empty($data['r']['promociones_id']) && !$promocion_change)
			$this->session->set_userdata('promociones_id',$data['r']['promociones_id']);

		$promociones_id=$this->session->userdata('promociones_id');

		if(count($data['promociones'])>1 && !$promociones_id)
		{
			$this->session->set_userdata('promociones',$data['promociones']);
			redirect('frontends/promocion_elegir/'.$cotizaciones_id);
		}
		else
		{
			if($data['promociones'] && count($data['promociones'])==1 && !$promociones_id)
			{
				$promo_id=array_keys($data['promociones']);
				$promociones_id=$promo_id[0];//debug($promociones_id);debug(var_dump($promociones_id));
				$this->session->set_userdata('promociones_id',$promociones_id);
			}

			$post['promociones_id']=$promociones_id?$promociones_id:FALSE;


			$promocion_opcional=array();
			if($this->session->userdata('promocion_opcional'))
				$promocion_opcional=$this->Promocion->regalos_get($promociones_id);
			$post['promocion_opcional']=$promocion_opcional?$promocion_opcional:array();

			$descuento = $this->calcular($cotizaciones_id,$post);

			if($promociones_id && @$data['r']['promocion_msi']!=18)
			{
				$this->promocion_regalos_add($cotizaciones_id);
				$promociones_productos = $this->session->userdata('promociones_productos');
				$promociones_alianzas = $this->session->userdata('promociones_alianzas');
			}

			$data['promociones_productos']=$promociones_productos;
			$data['promociones_id']=$promociones_id;
			$data['promociones_alianzas']=$promociones_alianzas;
			$data['calculo']=$descuento;

			if($this->input->post('rescate_sucursal'))
				$data['calculo']['envio']=0;

			$data['titulo']='Cotizaci&oacute;n';

			// Subtotal
			$data['subtotal'] = 0;
			if(!empty($carrito))
			{
				foreach($carrito as $k => $c) {
					$data['subtotal'] += ($c['precio'] * $c['cantidad']);
					foreach ($c['accesorios'] as $l => $a) {
						$data['subtotal'] += $a['precio'] * $a['cantidad'];
					}
				}
			}

			$data['aplicar_cupon']=$this->Frontend->cupones_puede();
			$data['paso'] = $cotizaciones_id ? 'edicion' : 'nueva';
			$data['evento_calcular_precio'] = 0;
			$data['evento_estado'] = $evento_estado;

			$this->load->view('frontend/cotizacion',$data);
		}
	}

	public function cotizacion_agregar_paquete($paquetes_id, $sin_accesorio=FALSE)
	{
		$this->load->model('paquete');
		$productos = $this->paquete->get_paquete_producto($paquetes_id);

		if($_POST || $sin_accesorio)
		{
			foreach($productos as $p) {
				for($i = 0 ; $i < $p['cantidad']; $i++) {
					$acc = isset($_POST['accesorios_ids'][$p['productos_id']]) ? array('accesorios_ids' => $_POST['accesorios_ids'][$p['productos_id']]) : array();
					$this->_agregar_producto($acc, $p['productos_id']);
				}
			}
			if($this->session->userdata('cotizaciones_id'))
				redirect('frontends/cotizacion/'.$this->session->userdata('cotizaciones_id').'/1');
			else
				redirect('frontends/cotizacion');
		}


		$datos=$this->_init_miele();
		$datos['productos'] = array();
		foreach($productos as $p)
		{
			$accesorios = $this->Producto->get_accesorios_producto($p['productos_id']);
			if(!empty($accesorios))
			{
				$datos['productos'][$p['productos_id']] = $this->base->read('productos',$p['productos_id']);
				$datos['productos'][$p['productos_id']]->accesorios = array();
				$datos['productos'][$p['productos_id']]->accesorios = $accesorios;
			}
		}

		//debug($datos['productos']);
		//die();

		if(empty($datos['productos']))
			$this->cotizacion_agregar_paquete($paquetes_id,TRUE);

		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['titulo']='Accesorios';
		$datos['paquetes_id']=$paquetes_id;
		$this->load->view('frontend/productos/accesorios_paquete',$datos);
	}

	public function producto_eliminar($productos_id,$cotizacion_id = FALSE,$acc_id=FALSE)
	{
		$productos=$this->session->userdata('productos');
		$productos_sess = array();
		foreach($productos as $k => $p)
		{
			if($acc_id)
			{
				foreach($p['accesorios'] as $acc=>$v)
				{
					if($acc == $acc_id)
					{
						unset($p['accesorios'][$acc]);
						if($cotizacion_id)
						{
							$data['eliminado'] = 1;
							$data['modified']=date('Y-m-d H:i:s');
							$data['modified_by']=$this->session->userdata('id');
							$this->db->where('productos_id',$k);
							$this->db->where('cotizaciones_id',$cotizacion_id);
							$this->db->where('accesorios_id',$acc_id);
							$this->db->where('eliminado',0);
							$this->db->update('cotizaciones_accesorios', $data);
						}
					}
				}
			}

			if($k != $productos_id)
				$productos_sess[$k] = $p;
			else
			{
				if($cotizacion_id)
				{
					$data['eliminado'] = 1;
					$data['modified']=date('Y-m-d H:i:s');
					$data['modified_by']=$this->session->userdata('id');
					$this->db->where('eliminado',0);
					$this->db->where('cotizaciones_id',$cotizacion_id);
					$this->db->where('productos_id',$productos_id);
					$this->db->update('cotizaciones_productos', $data);
				}
			}

		}

		$this->session->unset_userdata('productos');
		$this->session->set_userdata('productos', $productos_sess);

		$cotizacion=$this->base->read('cotizaciones',$cotizacion_id,TRUE);
		$datos=array();
		$datos['entrega_estado']=$cotizacion['entrega_estado'];
		$datos['instalacion_estado']=$cotizacion['instalacion_estado'];
		$datos['descuento_opcional']=$cotizacion['descuento_opcional']?$cotizacion['descuento_opcional']:0;
		$datos['descuento_paquete']=$cotizacion['descuento_paquete']?$cotizacion['descuento_paquete']:0;
		$datos['descuento_cupon']=$cotizacion['descuento_cupon']?$cotizacion['descuento_cupon']:0;
		$datos['cupones_id']=$cotizacion['cupones_id']?$cotizacion['cupones_id']:0;
		$datos['opcion_cupon_id']=$cotizacion['opcion_cupon_id']?$cotizacion['opcion_cupon_id']:0;
		$datos['promocion_msi']=$cotizacion['promocion_msi']?$cotizacion['promocion_msi']:0;

		$banamex_msi_vigente=strtotime($this->config->item('banamex_msi_vigencia'))>strtotime(date('Y-m-d H:i:s'));
		if($datos['promocion_msi']==18 && !$banamex_msi_vigente)
			$datos['promocion_msi']=0;

		if(!INTERNO)
		{
			$datos['descuento_opcional']=1;
			$datos['descuento_paquete']=1;
			$datos['sin_envio']=1;
		}

		$this->load->model('Cotizacion');
		$this->load->model('Promocion');
		$descuento=$this->calcular($cotizacion_id,$datos);

		$msi_banamex = @$cotizacion['promocion_msi']==18 && $banamex_msi_vigente?TRUE:FALSE;

		$promocion_opcional = $this->session->userdata('promocion_opcional');
		$accesorios_sesion = $this->session->userdata('accesorios');
		$promociones=$this->Promocion->get_promociones($productos_sess, $accesorios_sesion, $descuento['importe_venta'],$msi_banamex,$promocion_opcional);

		if(!$this->session->userdata('promociones'))
			$this->session->set_userdata('promociones',$promociones);

		if(@!empty($cotizacion['promociones_id']))
			$this->session->set_userdata('promociones_id',$cotizacion['promociones_id']);

		$promociones_id=$this->session->userdata('promociones_id');

		if(count($promociones)>1 && !$promociones_id)
		{
			$this->session->set_userdata('promociones',$promociones);
			redirect('frontends/promocion_elegir/'.$cotizacion_id);
		}
		else
		{
			if($promociones && count($promociones)==1 && !$promociones_id)
			{
				$promo_id=array_keys($promociones);
				$promociones_id=$promo_id[0];
				$this->session->set_userdata('promociones_id',$promociones_id);
			}

			$datos['promociones_id']=$promociones_id?$promociones_id:FALSE;

			$promocion_opcional=array();
			if($this->session->userdata('promocion_opcional'))
				$promocion_opcional=$this->Promocion->regalos_get($promociones_id);
			$post['promocion_opcional']=$promocion_opcional?$promocion_opcional:array();

			$this->calcular($cotizacion_id,$datos, TRUE);//debug($cotizacion_id);exit;

			if($promociones_id && @$cotizacion['promocion_msi']!=18)
				$this->promocion_regalos_add($cotizacion_id);
		}

		$this->_init_miele();
		$this->session->set_userdata('evento_estado',FALSE);
		if($cotizacion_id)
			redirect('frontends/cotizacion/' . $cotizacion_id . '/1');
		redirect('frontends/cotizacion');
	}

	public function accesorio_individual_eliminar($cotizacion_id, $acc_id)
	{
		$accesorios=$this->session->userdata('accesorios');//debug($accesorios);debug($acc_id);exit;
		if($cotizacion_id)
		{
			foreach($accesorios as $k=>&$v)
			{
				if($v->accesorios_id==$acc_id)
				{
					unset($accesorios[$acc_id]);
					$data=array();
					$data['eliminado']=1;
					$this->db->where('accesorios_id',$acc_id);
					$this->db->where('cotizaciones_id',$cotizacion_id);
					$this->db->update('cotizaciones_accesorios',$data);
					break;
				}
			}
		}
		else
			unset($accesorios[$acc_id]);

		$this->session->set_userdata('accesorios', $accesorios);

		$cotizacion=$this->base->read('cotizaciones',$cotizacion_id,TRUE);
		$datos=array();
		$datos['entrega_estado']=$cotizacion['entrega_estado'];
		$datos['instalacion_estado']=$cotizacion['instalacion_estado'];
		$datos['descuento_opcional']=$cotizacion['descuento_opcional']?$cotizacion['descuento_opcional']:0;
		$datos['descuento_paquete']=$cotizacion['descuento_paquete']?$cotizacion['descuento_paquete']:0;
		$datos['descuento_cupon']=$cotizacion['descuento_cupon']?$cotizacion['descuento_cupon']:0;
		$datos['cupones_id']=$cotizacion['cupones_id']?$cotizacion['cupones_id']:0;
		$datos['opcion_cupon_id']=$cotizacion['opcion_cupon_id']?$cotizacion['opcion_cupon_id']:0;
		$datos['promocion_msi']=$cotizacion['promocion_msi']?$cotizacion['promocion_msi']:0;

		$banamex_msi_vigente=strtotime($this->config->item('banamex_msi_vigencia'))>strtotime(date('Y-m-d H:i:s'));
		if($datos['promocion_msi']==18 && !$banamex_msi_vigente)
			$datos['promocion_msi']=0;

		if(!INTERNO)
		{
			$datos['descuento_opcional']=1;
			$datos['descuento_paquete']=1;
			$datos['sin_envio']=1;
		}

		$this->load->model('Cotizacion');
		$this->load->model('Promocion');
		$descuento=$this->calcular($cotizacion_id,$datos);

		$msi_banamex = @$cotizacion['promocion_msi']==18 && $banamex_msi_vigente?TRUE:FALSE;

		$promocion_opcional = $this->session->userdata('promocion_opcional');
		$productos_sess = $this->session->userdata('productos');
		$accesorios_sesion = $this->session->userdata('accesorios');
		$promociones=$this->Promocion->get_promociones($productos_sess, $accesorios_sesion, $descuento['importe_venta'],$msi_banamex,$promocion_opcional);

		if(!$this->session->userdata('promociones'))
			$this->session->set_userdata('promociones',$promociones);

		if(@!empty($cotizacion['promociones_id']))
			$this->session->set_userdata('promociones_id',$cotizacion['promociones_id']);

		$promociones_id=$this->session->userdata('promociones_id');

		if(count($promociones)>1 && !$promociones_id)
		{
			$this->session->set_userdata('promociones',$promociones);
			redirect('frontends/promocion_elegir/'.$cotizacion_id);
		}
		else
		{
			if($promociones && count($promociones)==1 && !$promociones_id)
			{
				$promo_id=array_keys($promociones);
				$promociones_id=$promo_id[0];
				$this->session->set_userdata('promociones_id',$promociones_id);
			}

			$datos['promociones_id']=$promociones_id?$promociones_id:FALSE;

			$promocion_opcional=array();
			if($this->session->userdata('promocion_opcional'))
				$promocion_opcional=$this->Promocion->regalos_get($promociones_id);
			$post['promocion_opcional']=$promocion_opcional?$promocion_opcional:array();

			$this->calcular($cotizacion_id,$datos, TRUE);//debug($cotizacion_id);exit;

			if($promociones_id && @$cotizacion['promocion_msi']!=18)
				$this->promocion_regalos_add($cotizacion_id);
		}

		if($cotizacion_id)
			redirect('frontends/cotizacion/' . $cotizacion_id . '/1');
		redirect('frontends/cotizacion');
	}

	public function calcular($cotizaciones_id=FALSE, $data, $guarda=FALSE)
	{
		$this->load->model('Descuento');
		$productos=$this->session->userdata('productos');
		$datos=$this->Descuento->calcular($cotizaciones_id,$data,$productos, $guarda);
		return $datos;
	}

	public function categorias_detalle($categorias_id)
	{
		$datos=$this->_init_miele();
		$datos['r']=$this->base->read('productos_categorias',$categorias_id);
		$this->load->model('Categoria');
		$datos['categorias']=$this->Categoria->get_categorias($categorias_id);
		$datos['productos']=$this->productos_grid($categorias_id,NULL,NULL,TRUE);
		$this->load->view('frontend/categorias/detalle',$datos);
	}

	public function productos_grid($categorias_id,$limit=FALSE,$page=FALSE,$return=FALSE)
	{
		$datos=$this->_init_miele();
		$this->load->model('Producto');
		$datos['productos']=$this->Producto->get_productos($categorias_id,$limit,$page);
		$datos['num_pages']=$this->Producto->get_num_pages($categorias_id,$limit);
		$datos['page']=$page?$page:1;
		$this->base->db->select('nombre');
		$datos['nombre_categoria']=$this->base->read('productos_categorias',$categorias_id)->nombre;
		$datos['categorias_id']=$categorias_id;
		if($return)
			return $this->load->view('frontend/elementos/grid_productos',$datos,TRUE);
		else
			$this->load->view('frontend/elementos/grid_productos',$datos);
	}

	public function productos($productos_id)
	{
		$producto = $this->base->get_datos('categorias_id, activo', 'productos', array('id'=>$productos_id));

		if(empty($producto->activo))
		{
			$this->session->set_flashdata('error', 'El producto ya no está disponible.');
			redirect('frontends/index');
		}

		if(!empty($producto->categorias_id))
		{
			$categoria = $this->base->get_datos('parent_id, activo', 'productos_categorias', array('id'=>$producto->categorias_id));

			if(empty($categoria->activo))
			{
				$this->session->set_flashdata('error', 'La categoría ya no está disponible.');
				redirect('frontends/index');
			}

			$categoria_padre = $categoria->parent_id;
			while(!empty($categoria_padre))
			{
				$categoria_activa = $this->base->get_dato('activo', 'productos_categorias', array('id'=>$categoria_padre));
				if(empty($categoria_activa))
				{
					$this->session->set_flashdata('error', 'La categoría ya no está disponible.');
					redirect('frontends/index');
				}
				$categoria_padre = $this->base->get_dato('parent_id', 'productos_categorias', array('id'=>$categoria_padre));
			}
		}

		$es_servicio = $this->base->get_dato('unidad_id','productos',array('id'=>$productos_id));

		if(!empty($_POST['agregar']) && @$es_servicio==2)
		{
			if($this->form_validation->run('productos_detalle'))
			{
				if(!empty($_POST['evento_estado']))
					$this->session->set_userdata('evento_estado',$_POST['evento_estado']);

				redirect('frontends/cotizacion_agregar_producto/' . $productos_id);
			}
		}elseif(!empty($_POST['agregar']) && @$es_servicio==1)
			redirect('frontends/cotizacion_agregar_producto/'.$productos_id);

		// PERMITE VER EL DETALLE DE UN PRODUCTO ANTES DE AGREGARLO A LA COTIZACIÓN
		$datos=$this->_init_miele();
		$this->load->model('Promocion');
		$this->load->model('Sepomex_model','SP');
		$categorias_id=$this->Frontend->get_categorias_id($productos_id);
		$res=$this->base->read('productos',$productos_id);
		$res->promocion=$this->Promocion->promocion_tiene($categorias_id,$res->id);
		$datos['r']=$res;
		$datos['fotografias']=$this->Frontend->get_fotografias_productos($productos_id);
		$datos['documentacion']=$this->Frontend->get_documentacion($productos_id);
		$this->db->order_by('created','DESC');
		$this->db->limit('5');
		$r=$this->Frontend->get_productos($categorias_id);
		foreach($r as &$ps)
			$ps['promocion']=$this->Promocion->promocion_tiene($categorias_id,$ps['id']);
		$datos['productos_similares']=$r;
		$datos['estados']=$this->SP->get_estados();
		$datos['titulo']=$datos['r']->modelo;
		$datos['categorias'] = $this->Frontend->breadcumb_categorias($categorias_id);
		$this->load->view('frontend/productos/detalles_producto',$datos);
	}

	public function cotizacion_agregar_producto($productos_id=NULL,$sin_accesorio=FALSE, $consumibles=FALSE)
	{
		$productos=$this->session->userdata('productos');

		$mostrar=false;
		if(!empty($productos))
		{
			foreach($productos as $k=>$v)
			{
				if($v['unidad_id']==2)
					$this->session->set_flashdata('error', 'El precio de las capacitaciones est&aacute; sujeto a cambios dependiendo del estado donde se realiza el servicio. Favor de continuar con la compra para ingresar su localidad.');

				if($k==@$productos_id)
					$mostrar=true;
			}
		}
		if(!empty($productos_id) || $mostrar)
		{
			$unidad_id = $this->base->get_dato('unidad_id','productos',array('id'=>$productos_id));

			if($unidad_id==2)
				$this->session->set_flashdata('error', 'El precio de las capacitaciones est&aacute; sujeto a cambios dependiendo del estado donde se realiza el servicio.');
		}

		// PERMITE AGREGAR UN PRODUCTO A LA COTIZACION, SI TIENE ACCESORIOS OBLIGATORIOS
		// LOS AGREGA AUTOMATICAMENTE SINO PERMITE ELEGIR LOS OPCIONALES
		$this->load->model('Producto');
		if($_POST || $sin_accesorio)
		{
			if(isset($_POST['sin_agregar']) && $_POST['sin_agregar'])
			{
				if(isset($_POST['accesorios_ids']))
					unset($_POST['accesorios_ids']);
				unset($_POST['sin_agregar']);
			}

			$this->_agregar_producto($_POST, $productos_id);

			if($productos_id)
				$this->session->set_flashdata('done', 'El producto fue agregado correctamente al carrito.');
			elseif($this->session->userdata('accesorios'))
				$this->session->set_flashdata('done', 'Los accesorios fueron agregados correctamente al carrito.');
			else
				$this->session->set_flashdata('done', 'No se ha seleccionado ning&uacute;n producto y/o accesorio para agregar al carrito.');

			if($this->session->userdata('curso'))
				$this->session->set_flashdata('error', 'El precio de las capacitaciones est&aacute; sujeto a cambios dependiendo del estado donde se realiza el servicio.');

			if($this->session->userdata('cotizaciones_id'))
				redirect('frontends/cotizacion/'.$this->session->userdata('cotizaciones_id').'/1');
			else
				redirect('frontends/cotizacion');
		}

		$datos=$this->_init_miele();
		$datos['productos_id'] = $productos_id;

		if($productos_id)
		{
			$datos['accesorios'] = $this->Producto->get_accesorios_producto($productos_id, FALSE);

			if (empty($datos['accesorios']))
				$this->cotizacion_agregar_producto($productos_id, TRUE);

			$datos['imagen_principal'] = $this->Producto->get_imagen_principal($productos_id);
		}
		else
		{
			$this->load->model('Accesorio');
			$cond = array();
			$cond['activo']=1;
			if($consumibles)
			{
				$cond['consumible']=1;
				$cond['tipos_accesorios_id']=$consumibles;
			}else
				$cond['consumible']=0;
			$datos['accesorios']=$this->Accesorio->find($cond,NULL,NULL);
		}
		$datos['consumibles']=$consumibles;
		$datos['titulo']=$consumibles?'Consumibles':'Accesorios';
		$this->load->view('frontend/productos/accesorios',$datos);
	}

	private function _agregar_producto($data, $productos_id)
	{
		$productos=$this->session->userdata('productos');
		$accesorios_obligatorios = $this->Producto->get_accesorios_producto($productos_id,TRUE);
		$producto = (!empty($productos_id)) ? $this->base->read('productos', $productos_id, FALSE) : FALSE;

		if(array_key_exists($productos_id,$productos) && $producto->unidad_id!=2)
		{
			$productos[$productos_id]['cantidad']++;
			if(isset($data['accesorios_ids']))
			{
				foreach($data['accesorios_ids'] as $accesorios_id)
				{
					$existe_acc = false;
					foreach($productos[$productos_id]['accesorios'] as $a => $acc)
					{
						if($a == $accesorios_id)
						{
							$productos[$productos_id]['accesorios'][$accesorios_id]['cantidad'] += 1;
							$existe_acc = true;
						}
					}
					if(!$existe_acc)
					{
						$accesorio = $this->base->read('accesorios', $accesorios_id);
						$productos[$productos_id]['accesorios'][$accesorios_id] = array();
						$productos[$productos_id]['accesorios'][$accesorios_id]['precio'] = $accesorio->precio;
						$productos[$productos_id]['accesorios'][$accesorios_id]['cantidad'] = 1;
						$productos[$productos_id]['accesorios'][$accesorios_id]['obligatorio'] = 0;
						$productos[$productos_id]['accesorios'][$accesorios_id]['consumible'] = $accesorio->consumible;
						$productos[$productos_id]['accesorios'][$accesorios_id]['unidad'] = elemento('Unidades',$accesorio->unidad_id);
					}
				}
			}

			foreach($accesorios_obligatorios as $ac)
			{
				$existe_acc = false;
				foreach($productos[$productos_id]['accesorios'] as $a => $acc)
				{
					if($a == $ac->id)
					{
						$productos[$productos_id]['accesorios'][$ac->id]['cantidad'] += 1;
						$existe_acc = true;
					}
				}
				if(!$existe_acc)
				{
					$productos[$productos_id]['accesorios'][$ac->id] = array();
					$productos[$productos_id]['accesorios'][$ac->id]['precio'] = $ac->precio;
					$productos[$productos_id]['accesorios'][$ac->id]['cantidad'] = 1;
					$productos[$productos_id]['accesorios'][$ac->id]['obligatorio'] = 1;
					$productos[$productos_id]['accesorios'][$ac->id]['consumible'] = $ac->consumible;
					$productos[$productos_id]['accesorios'][$ac->id]['unidad'] = elemento('Unidades',$ac->unidad_id);
				}
			}

			$this->session->set_userdata('productos',$productos);
		}
		else
		{
			if($productos_id)
			{
				$productos[$productos_id] = array();
				$productos[$productos_id]['precio'] = $producto->precio;
				$productos[$productos_id]['cantidad'] = 1;

				$evento_estado = $this->session->userdata('evento_estado');

				if(@$producto->unidad_id==2 && !empty($evento_estado))
				{
					$postfijo = strtolower(str_replace(' ','_',$evento_estado));
					$evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', horas_maximas_'.$postfijo.', precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$productos_id));
					$productos[$productos_id]['precio'] = $evento->{'precio_inicial_' . $postfijo};
					$productos[$productos_id]['cantidad'] = $evento->{'horas_iniciales_' . $postfijo};

					$productos[$productos_id]['horas_minimo'] = $evento->{'horas_iniciales_' . $postfijo};
					$productos[$productos_id]['horas_maximo'] = $evento->{'horas_maximas_' . $postfijo};
				}

				$productos[$productos_id]['sin_envio'] = $producto->sin_envio;
				$productos[$productos_id]['accesorios'] = array();
				$productos[$productos_id]['unidad'] = elemento('Unidades',$producto->unidad_id);
				$productos[$productos_id]['unidad_id'] = $producto->unidad_id;

				if (isset($data['accesorios_ids']))
				{
					foreach ($data['accesorios_ids'] as $accesorios_id)
					{
						$accesorio = $this->base->read('accesorios', $accesorios_id);
						$productos[$productos_id]['accesorios'][$accesorios_id] = array();
						$productos[$productos_id]['accesorios'][$accesorios_id]['precio'] = $accesorio->precio;
						$productos[$productos_id]['accesorios'][$accesorios_id]['cantidad'] = 1;
						$productos[$productos_id]['accesorios'][$accesorios_id]['obligatorio'] = 0;
						$productos[$productos_id]['accesorios'][$accesorios_id]['consumible'] = $accesorio->consumible;
						$productos[$productos_id]['accesorios'][$accesorios_id]['unidad'] = elemento('Unidades',$accesorio->unidad_id);
					}
				}
				foreach ($accesorios_obligatorios as $ac)
				{
					$productos[$productos_id]['accesorios'][$ac->id] = array();
					$productos[$productos_id]['accesorios'][$ac->id]['precio'] = $ac->precio;
					$productos[$productos_id]['accesorios'][$ac->id]['cantidad'] = 1;
					$productos[$productos_id]['accesorios'][$ac->id]['obligatorio'] = 1;
					$productos[$productos_id]['accesorios'][$ac->id]['consumible'] = $ac->consumible;
					$productos[$productos_id]['accesorios'][$ac->id]['unidad'] = elemento('Unidades',$ac->unidad_id);
				}

				$this->session->set_userdata('productos',$productos);
			}
			else // VENTA DE ACCESORIOS INDIVIDUALES
			{
				$accesorios=array();
				if (isset($data['accesorios_ids']))
				{
					foreach ($data['accesorios_ids'] as $accesorios_id)
					{
						$accesorio = $this->base->read('accesorios', $accesorios_id);
						$accesorios[$accesorios_id] = array();
						$accesorios[$accesorios_id]['precio'] = $accesorio->precio;
						$accesorios[$accesorios_id]['cantidad'] = 1;
						$accesorios[$accesorios_id]['obligatorio'] = 0;
						$accesorios[$accesorios_id]['consumible'] = $accesorio->consumible;
						$accesorios[$accesorios_id]['unidad'] = elemento('Unidades',$accesorio->unidad_id);
						$accesorios[$accesorios_id]['unidad_id'] = $accesorio->unidad_id;
					}
				}

				$accesorios_actuales = $this->session->userdata('accesorios');
				if($accesorios_actuales || $accesorios)
				{
					foreach($accesorios as $acc_id=>$acc)
					{
						if(array_key_exists($acc_id,$accesorios_actuales))
							$accesorios_actuales[$acc_id]->cantidad+=$acc['cantidad'];
						else
						{
							$accesorio = $this->base->read('accesorios', $acc_id);
							$accesorio->cantidad = $acc['cantidad'];
							$accesorio->obligatorio = $acc['obligatorio'];
							$accesorio->accesorios_id = $acc_id;
							$accesorio->unidad_id = $acc['unidad_id'];
							$accesorio->unidad = elemento('Unidades',$acc['unidad_id']);
							$accesorios_actuales[$acc_id] = $accesorio;
						}
					}
				}
				$this->session->set_userdata('accesorios',$accesorios_actuales);
			}
		}
	}


	/*public function accesorios_productos($productos_id,$sin_accesorio=FALSE)
      {
        if($_POST || $sin_accesorio)
        {
          $data=$this->session->userdata('productos');
          $ts=time();
          $data[$ts]['productos_id']=$productos_id;
          $data[$ts]['cantidad']=1;
          if(isset($_POST['accesorio_id']))
          {
            $data[$ts]['accesorios']=$_POST['accesorio_id'];
            $data[$ts]['tipo_accesorios_id']=$_POST['tipo_accesorios_id'];
          }
          $this->session->set_userdata('productos',$data);
          $this->session->set_flashdata('done', 'El producto fue agregado correctamente.');
          redirect('frontends/cotizacion/');
        }

        $datos=$this->_init_miele();
        $this->load->model('Producto');
        $datos['accesorios']=$this->Producto->get_accesorios_producto($productos_id);

        if(empty($datos['accesorios']))
          $this->accesorios_productos($productos_id,1);
        $datos['producto']=$this->base->read('productos',$productos_id);
        $datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
        $datos['titulo']='Accesorios';
        $datos['productos_id']=$productos_id;
        $this->load->view('frontend/productos/accesorios',$datos);
      }*/

	public function contacto()
	{
		$datos=$this->_init_miele();
		$datos['titulo']='Contacto';
		$this->load->view('frontend/home/contacto',$datos);
	}

	public function legal()
	{
		$datos=$this->_init_miele();
		$datos['titulo']='Aviso Legal';
		$this->load->view('frontend/home/legal',$datos);
	}

	public function ver_resultados()
	{
		if(!empty($_POST))
		{
			$datos=$this->_init_miele();
			$string=str_replace(' ','-',$_POST);
			$string=$string['search'];
			$str=explode('-',$string);
			$resultado=$this->Frontend->busca_resultados($str);
			$datos['resultado']=$resultado;
			$datos['titulo']='Resultados';
			$this->load->view('frontend/home/busqueda_resultados', $datos);
		}
		else
		{
			redirect('frontends/index');
		}
	}

	public function estilos_comunes()
	{
		$this->load->view('frontend/estilos_comunes',$data);
	}

	function paquetes_detalles()
	{
		$datos=$this->_init_miele();
		$this->load->model('Paquete');
		$datos['paquetes'] = $this->Paquete->get_paquetes();
		$datos['productos_paquetes']=$this->base->lista('productos','id','nombre',TRUE,NULL,'ASC',array('ocultar'=>0));
		$datos['titulo']='Detalles por paquetes';
		$this->load->view('paquetes/detalles',$datos);
	}

	function paquete_detalle($paquetes_id)
	{
		$datos=$this->_init_miele();
		$this->load->model('paquete');
		$datos['paquete'] = $this->base->read('paquetes', $paquetes_id);
		$datos['categorias'] = $this->paquete->paquete_categorias_get($paquetes_id);
		$datos['titulo']='Detalle paquete';
		$this->load->view('paquetes/paquete_detalle', $datos);
	}

	public function enviar_compra($cotizaciones_id, $enviar_compra = null, $autorizar=FALSE)
	{
		$this->load->model('Cotizacion');
		$this->load->model('Referido');
		$this->load->Model('Sepomex_model','SP');
		$this->load->model('Accesorio');
		$this->load->model('Promocion');

		// Aqui se carga la cotizacion a la sesion
		$promociones_productos=array();
		$promociones_alianzas=array();
		if($cotizaciones_id)
		{
			$this->session->set_userdata('productos',array());
			$this->session->set_userdata('cotizaciones_id',$cotizaciones_id);
			$productos_cotizacion = $this->Cotizacion->get_productos_ids($cotizaciones_id);

			if(!empty($productos_cotizacion))
			{
				// ELIMINO LOS PRODUCTOS Y ACCESORIOS QUE NO ESTÁN ACTIVOS
				$productos_cotizacion_aux = $productos_cotizacion;
				foreach ($productos_cotizacion_aux AS $k => $p)
				{
					$producto_activo = $this->base->get_dato('activo', 'productos', array('id' => $p));
					if (!$producto_activo)
					{
						unset($productos_cotizacion[$k]);
					}
				}
			}

			$evento_estado = $this->session->userdata('evento_estado');

			// Cantidades y precios de la cotizacion
			$productos  = array();
			$recalcular=TRUE;
			if(in_array($this->Cotizacion->get_status($cotizaciones_id),array(5)))
				$recalcular=FALSE;

			foreach($productos_cotizacion as $k => $p)
			{
				$cotizacion_producto = $this->Cotizacion->get_cotizacion_producto($cotizaciones_id, $p, $recalcular);
				$cotizacion_accesorios = $this->Cotizacion->get_cotizacion_accesorios($cotizaciones_id, $p, FALSE, $recalcular);

				if(!empty($cotizacion_accesorios))
				{
					// ELIMINO LOS ACCESORIOS Y CONSUMIBLES QUE NO ESTÁN ACTIVOS
					$cotizacion_accesorios_aux = $cotizacion_accesorios;
					foreach ($cotizacion_accesorios_aux AS $a => $acc)
					{
						$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $acc->accesorios_id));
						if (!$accesorio_activo)
						{
							unset($cotizacion_accesorios[$a]);
						}
					}
				}


				$productos[$p] = array();
				$productos[$p]['unidad'] = elemento('Unidades',$cotizacion_producto->unidad_id);
				$productos[$p]['unidad_id'] = $cotizacion_producto->unidad_id;
				$productos[$p]['precio']     = $cotizacion_producto->precio;


				if($cotizacion_producto->unidad_id==2)
				{

					$evento_estado = $this->base->get_dato('evento_estado','cotizaciones',array('id'=>$cotizaciones_id));
					$this->session->set_userdata('evento_estado',$evento_estado);

					$postfijo = strtolower(str_replace(' ','_',$evento_estado));
					$evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', horas_maximas_'.$postfijo.', precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$p));
					$productos[$p]['precio'] = $evento->{'precio_inicial_'.$postfijo};


					$productos[$p]['horas_minimo'] = $evento->{'horas_iniciales_' . $postfijo};
					$productos[$p]['horas_maximo'] = $evento->{'horas_maximas_' . $postfijo};
				}

				$productos[$p]['importe_cliente']    = $cotizacion_producto->importe_cliente;
				$productos[$p]['cantidad']   = $cotizacion_producto->cantidad;
				$productos[$p]['descuento_cliente']  = $cotizacion_producto->descuento_cliente;

				$productos[$p]['descuento_distribuidor']  = $cotizacion_producto->descuento_distribuidor;
				$productos[$p]['importe_distribuidor']    = $cotizacion_producto->importe_distribuidor;
				$productos[$p]['sin_envio']    = $cotizacion_producto->sin_envio;
				$productos[$p]['accesorios'] = array();

				foreach($cotizacion_accesorios as $accesorio)
				{
					if(!empty($accesorio->accesorios_id))
					{
						$tipo_accesorio_id=$this->base->value_get('accesorios',$accesorio->accesorios_id,'tipos_accesorios_id');
						$productos[$p]['accesorios'][$accesorio->accesorios_id] = array();
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['precio'] = $accesorio->precio;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['cantidad'] = $accesorio->cantidad;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['descuento_cliente'] = $accesorio->descuento_cliente;//$accesorio->descuento_cliente;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['importe_cliente'] = $accesorio->importe_cliente;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['descuento_distribuidor'] = $accesorio->descuento_distribuidor;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['importe_distribuidor'] = $accesorio->importe_distribuidor;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['obligatorio'] = $this->Cotizacion->accesorio_obligatorio($p,$tipo_accesorio_id);
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['consumible'] = $accesorio->consumible;
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['unidad'] = elemento('Unidades',$accesorio->unidad_id);
						$productos[$p]['accesorios'][$accesorio->accesorios_id]['unidad_id'] = $accesorio->unidad_id;
					}
				}
			}
			$this->session->set_userdata('productos', $productos);

			$accesorios = $this->Accesorio->get_accesorios_individuales($cotizaciones_id);
			if(!empty($accesorios))
			{
				// ELIMINO LOS ACCESORIOS Y CONSUMIBLES INDIVIDUALES QUE NO ESTÁN ACTIVOS
				$accesorios_aux = $accesorios;
				foreach ($accesorios_aux AS $a => $acc)
				{
					$accesorio_activo = $this->base->get_dato('activo', 'accesorios', array('id' => $acc->accesorios_id));
					if (!$accesorio_activo)
					{
						unset($accesorios[$a]);
					}
				}
			}

			$this->session->set_userdata('accesorios', $accesorios);
		}

		$data=$this->_init_miele();

		$carrito = $this->session->userdata('productos');
		$productos=array();
		if(!empty($carrito))
		{
			$i=0;
			foreach($carrito as $k => $p)
			{
				$producto = $this->Cotizacion->get_producto($k, $p['accesorios']);
				$producto->importe_cliente = $p['importe_cliente'];
				$producto->importe_distribuidor = $p['importe_distribuidor'];

				if($p['unidad_id']!=2)
					$producto->precio = $p['precio'];
				else
				{
					$evento_estado = $this->base->get_dato('evento_estado','cotizaciones',array('id'=>$cotizaciones_id));
					$postfijo = strtolower(str_replace(' ','_',$evento_estado));
					$evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', horas_maximas_'.$postfijo.', precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$k));
					$producto->precio = $evento->{'precio_inicial_'.$postfijo};
					$producto->horas_minimo = $evento->{'horas_iniciales_' . $postfijo};
					$producto->horas_maximo = $evento->{'horas_maximas_' . $postfijo};
				}
				$producto->cantidad = $p['cantidad'];
				$producto->unidad_id = $p['unidad_id'];
				$producto->unidad = $p['unidad'];
				$producto->descuento_cliente = $p['descuento_cliente'];
				$producto->descuento_distribuidor = $p['descuento_distribuidor'];

				$categorias_id = $categorias_id=$this->Frontend->get_categorias_id($k);
				$producto->promocion_opcional = $this->Promocion->promociones_opcionales_get($k,$categorias_id,TRUE);
				$productos[] = $producto;
			}
		}

		$data['accesorios_individuales']= $accesorios;

		if(!empty($_POST))
		{
			// VALIDA LOS ACCESORIOS REQUERIDOS
			$reglas=$this->form_validation->_config_rules['cotizaciones_compra'];
			if($this->Cotizacion->es_venta_directa($this->session->userdata('cuentas_id')))
			{
				$reglas[]=array(
					'field'=>'condiciones_pago_id',
					'label'=>'Condiciones de Pago',
					'rules'=>'trim|required'
				);
			}

			if(!in_array($this->Cotizacion->get_status($cotizaciones_id),array(2,3,4,5,6)))
			{
				$reglas[] = array(
					'field' => 'entrega_fecha_tentativa',
					'label' => 'fecha tentativa de entrega',
					'rules' => 'trim|required|fecha_entrega'
				);
			}

			$this->form_validation->set_rules($reglas);
			if($this->form_validation->run())
			{
				$datos=array(
					'id' => $cotizaciones_id,
					'fecha'=>date('Y-m-d'),
					'usuario_id'=>$this->input->post('usuario_id'),
					'nombre_comprador'=>$this->input->post('nombre_comprador'),
					'referido_distribuidor_id'=>$this->input->post('referido_distribuidor_id'),
					'referido_vendedor_nombre'=>$this->input->post('referido_vendedor_nombre'),
					'referido_vendedor_paterno'=>$this->input->post('referido_vendedor_paterno'),
					'referido_vendedor_materno'=>$this->input->post('referido_vendedor_materno'),
					'paterno_comprador'=>$this->input->post('paterno_comprador'),
					'materno_comprador'=>$this->input->post('materno_comprador'),
					'email_comprador'=>$this->input->post('email_comprador'),
					'telefono_comprador'=>$this->input->post('telefono_comprador'),
					'observaciones'=>$this->input->post('observaciones'),
					'entrega_codigo_postal'=>$this->input->post('entrega_codigo_postal'),
					'entrega_estado'=>$this->input->post('entrega_estado'),
					'entrega_municipio'=>$this->input->post('entrega_municipio'),
					'entrega_asentamiento'=>$this->input->post('entrega_asentamiento'),
					'entrega_calle'=>$this->input->post('entrega_calle'),
					'entrega_numero_exterior'=>$this->input->post('entrega_numero_exterior'),
					'entrega_numero_interior'=>$this->input->post('entrega_numero_interior'),
					'entrega_fecha_tentativa'=>$this->input->post('entrega_fecha_tentativa'),
					'entrega_fecha_instalacion'=>$this->input->post('entrega_fecha_instalacion'),
					'fecha_nacimiento_comprador'=>$this->input->post('fecha_nacimiento_comprador'),
					'anio_nacimiento_comprador'=>$this->input->post('anio_nacimiento_comprador'),
					'fecha_aniversario_comprador'=>$this->input->post('fecha_aniversario_comprador'),
					'nombre_contacto'=>$this->input->post('nombre_contacto'),
					'telefono_particular'=>$this->input->post('telefono_particular'),
					'telefono_celular'=>$this->input->post('telefono_celular'),
					'tipo_persona_id'=>$this->input->post('tipo_persona_id'),
					'razon_social'=>$this->input->post('razon_social'),
					'apellido_paterno'=>$this->input->post('apellido_paterno'),
					'apellido_materno'=>$this->input->post('apellido_materno'),
					'rfc'=>$this->input->post('rfc'),
					'email'=>$this->input->post('email'),
					'telefono'=>$this->input->post('telefono'),
					'estado'=>$this->input->post('estado'),
					'municipio'=>$this->input->post('municipio'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'asentamiento'=>$this->input->post('asentamiento'),
					'calle'=>$this->input->post('calle'),
					'numero_exterior'=>$this->input->post('numero_exterior'),
					'numero_interior'=>$this->input->post('numero_interior'),
					'observaciones'=>$this->input->post('observaciones'),
					'instalacion_nombre_contacto'=>$this->input->post('instalacion_nombre_contacto'),
					'instalacion_telefono_particular'=>$this->input->post('instalacion_telefono_particular'),
					'instalacion_telefono_celular'=>$this->input->post('instalacion_telefono_celular'),
					'instalacion_codigo_postal'=>$this->input->post('instalacion_codigo_postal'),
					'instalacion_estado'=>$this->input->post('instalacion_estado'),
					'instalacion_municipio'=>$this->input->post('instalacion_municipio'),
					'instalacion_asentamiento'=>$this->input->post('instalacion_asentamiento'),
					'instalacion_calle'=>$this->input->post('instalacion_calle'),
					'instalacion_numero_exterior'=>$this->input->post('instalacion_numero_exterior'),
					'instalacion_numero_interior'=>$this->input->post('instalacion_numero_interior'),
					'vendedor_nombre'=>$this->input->post('vendedor_nombre'),
					'vendedor_paterno'=>$this->input->post('vendedor_paterno'),
					'vendedor_materno'=>$this->input->post('vendedor_materno'),
					'forma_pago_id'=>$this->input->post('forma_pago_id'),
					'condiciones_pago_id'=>$this->input->post('condiciones_pago_id'),
					'referido_porcentaje_comision'=>$this->input->post('referido_porcentaje_comision'),
					'promociones_id'=>$this->input->post('promociones_id'),
					'evento_estado'=>$this->input->post('evento_estado'),
				);
                                
				if($_FILES)
					$this->Cotizacion->guarda_documentacion($cotizaciones_id,$_FILES);

				$promocion_opcional=array();
				if($this->session->userdata('promocion_opcional'))
					$promocion_opcional=$this->Promocion->regalos_get($this->input->post('promociones_id'));
				$_POST['promocion_opcional']=$promocion_opcional?$promocion_opcional:array();

				$cot_id=$this->Cotizacion->guarda(mayuscula_array($datos),$productos, $accesorios);

				$_POST['promocion_msi']=0;
				if(@$_POST['promocion_msi_amex'])
					$_POST['promocion_msi']=$_POST['promocion_msi_amex'];
				elseif(@$_POST['promocion_msi_banamex'])
					$_POST['promocion_msi']=$_POST['promocion_msi_banamex'];

				if($cot_id)
					$this->calcular($cot_id,$_POST,TRUE);

				$this->Frontend->cotizacion_limpiar();

				$status_id=$this->Cotizacion->get_status($cotizaciones_id);
				$this->Cotizacion->genera_folio_compra($cotizaciones_id);
				if($enviar_compra)
				{
					$this->_valida_orden_compra($cotizaciones_id, $data);
				}
				else
				{
					if ($autorizar)
						redirect('cotizaciones/autorizar/'.$cotizaciones_id);
					elseif ($status_id==3)
						$this->Cotizacion->cambia_status($cotizaciones_id,3);
					else
						$this->Cotizacion->cambia_status($cotizaciones_id,2);

					$this->Frontend->cotizacion_limpiar();
					$this->session->set_flashdata('done', 'La orden de compra fue generada correctamente.');
					redirect('cotizaciones/index');
				}
			}
			else
			{
				$msg='Verifique los datos.';
				$data['flashdata']['error'] = $msg;
				$totales=$this->base->value_get('cotizaciones',$cotizaciones_id,array('importe_total','descuento_cliente','descuento_distribuidor','descuento_paquete_distribuidor','subtotal_cliente','subtotal_distribuidor','envio','iva_cliente','iva_distribuidor','total_cliente','total_distribuidor','cuentas_id'));
				$data['r']=$_POST;
				foreach($totales as $k=>$v)
					$data['r'][$k]=$v;

				$data['cuenta']= $this->base->read('cuentas', $data['r']['cuentas_id']);
				$data['municipios_instalacion']=$this->SP->get_municipios($_POST['instalacion_estado']);
				$data['municipios_entrega']=$this->SP->get_municipios($_POST['entrega_estado']);
				$data['municipios']=$this->SP->get_municipios($_POST['estado']);
				$data['venta_directa']=$this->Cotizacion->es_venta_directa($data['r']['cuentas_id']);

				$data['cupones_opciones'] = NULL;
				if(@$_POST['descuento_cupon'] && @$_POST['cupones_id'])
				{
					$this->load->model('Cupon');
					$data['cupones_opciones'] = $this->Cupon->opciones_get($_POST['cupones_id']);
				}
			}
		}
		else
		{
			$data['venta_directa']=$this->Cotizacion->es_venta_directa($this->session->userdata('cuentas_id'));
			if($cotizaciones_id)
			{
				$data['r']=$this->base->read('cotizaciones',$cotizaciones_id,TRUE);
				$data['cuenta']= $this->base->read('cuentas', $data['r']['cuentas_id']);
				$data['municipios_instalacion']=$this->SP->get_municipios($data['r']['instalacion_estado']);
				$data['municipios_entrega']=$this->SP->get_municipios($data['r']['entrega_estado']);
				$data['municipios']=$this->SP->get_municipios($data['r']['estado']);
				//$data['status_id']=$this->Cotizacion->get_status($cotizaciones_id);
				$data['venta_directa']=$this->Cotizacion->es_venta_directa($data['r']['cuentas_id']);
				$data['cupones_opciones'] = NULL;
				if($data['r']['descuento_cupon'] && $data['r']['cupones_id'])
				{
					$this->load->model('Cupon');
					$data['cupones_opciones'] = $this->Cupon->opciones_get($data['r']['cupones_id']);
				}

				if(@$data['r']['promocion_opcional_descuento'])
					$this->session->set_userdata('promocion_opcional',$data['r']['promociones_id']);
			}
		}

		if(!empty($data['r']['producto_regalo_id']))
		{
			$this->load->model('Producto');
			$data['producto_regalo'] = $this->base->get_datos('id, nombre, modelo, precio, unidad_id','productos', array('id'=>$data['r']['producto_regalo_id']));
			$data['producto_regalo']->img_id = $this->Producto->get_imagen_principal($data['r']['producto_regalo_id']);
			$data['producto_regalo']->cantidad = 1;
			$data['producto_regalo']->unidad = elemento('Unidades', $data['producto_regalo']->unidad_id);
		}

		//OBTENGO LOS DATOS DE FACTURACION DE LA CUENTA SI ES VENTA DE DISTRIBUIDOR
		if(!$data['venta_directa'] && isset($data['r']['cuentas_id']))
			$data['facturacion']=$this->base->get_datos_fiscales($data['r']['cuentas_id']);

		$data['productos_sesion']=$productos;
		$data['accesorios_sesion']=$this->session->userdata('accesorios');
		$data['carrito'] = $carrito;
		$data['estados']=$this->SP->get_estados();
		$data['productos']=$this->base->lista('productos','id','modelo',TRUE,NULL,'DESC',array('ocultar'=>0));
		$data['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$data['vendedores']=$this->base->lista('usuarios','id',array('nombre','apellido_paterno','apellido_materno'),FALSE,'id','ASC',array('vendedor'=>1));
		$data['distribuidores'] = $this->Referido->get_distribuidores();
		//$data['vendedores_ref'] = $this->Referido->get_vendedores();
		$data['tipo_moneda']=catalogo('tipo_moneda',FALSE);
		$data['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');
		$data['cuentas']=$this->base->lista('cuentas','id','nombre',TRUE,'nombre','ASC');
		$data['cotizaciones_id']=$cotizaciones_id;

		$post['entrega_estado']=isset($data['r']['entrega_estado'])?$data['r']['entrega_estado']:FALSE;
		$post['instalacion_estado']=isset($data['r']['instalacion_estado'])?$data['r']['instalacion_estado']:FALSE;
		$post['descuento_opcional']=isset($data['r']['descuento_opcional'])?$data['r']['descuento_opcional']:0;
		$post['descuento_paquete']=isset($data['r']['descuento_paquete'])?$data['r']['descuento_paquete']:0;
		$post['descuento_cupon']=isset($data['r']['descuento_cupon'])?$data['r']['descuento_cupon']:0;
		$post['cupones_id']=isset($data['r']['cupones_id'])?$data['r']['cupones_id']:0;
		$post['opcion_cupon_id']=isset($data['r']['opcion_cupon_id'])?$data['r']['opcion_cupon_id']:0;
		$post['promociones_id']=isset($data['r']['promociones_id'])?$data['r']['promociones_id']:0;
		$data['banamex_msi_vigente']=$banamex_msi_vigente=strtotime($this->config->item('banamex_msi_vigencia'))>strtotime(date('Y-m-d H:i:s'));
		$post['promocion_msi']=isset($data['r']['promocion_msi'])?$data['r']['promocion_msi']:0;
		if($post['promocion_msi']==18 && !$banamex_msi_vigente)
			$post['promocion_msi']=0;

		$descuento=$this->calcular($cotizaciones_id,$post);
		$msi_banamex = @$_POST['promocion_msi']==18 && $banamex_msi_vigente?TRUE:FALSE;
		$promocion_opcional = $this->session->userdata('promocion_opcional');
		$data['promociones']=$this->Promocion->get_promociones($data['carrito'], $data['accesorios_sesion'], $descuento['importe_venta'],$msi_banamex, $promocion_opcional);
		$promocion_opcional=array();
		if($this->session->userdata('promocion_opcional'))
			$promocion_opcional=$this->Promocion->regalos_get($data['r']['promociones_id']);
		$post['promocion_opcional']=$promocion_opcional?$promocion_opcional:array();
		$descuento=$this->calcular($cotizaciones_id,$post);

		$promociones_id = $this->base->get_dato('promociones_id','cotizaciones',array('id'=>$cotizaciones_id));
		if($promociones_id && !empty($data['promociones']) && array_key_exists($promociones_id,$data['promociones']) && @$data['r']['promocion_msi']!=18)
		{
			$data['promociones_id']=$promociones_id;
			$this->session->set_userdata('promociones_id',$promociones_id);
			$this->promocion_regalos_add($cotizaciones_id);
			$promociones_productos = $this->session->userdata('promociones_productos');
			$promociones_alianzas = $this->session->userdata('promociones_alianzas');
		}

		$data['promociones_productos']=$promociones_productos;
		$data['promociones_alianzas']=$promociones_alianzas;
		$data['calculo']=$descuento;
		// Subtotal
		$data['subtotal'] = 0;
		if($cotizaciones_id)
			$data['status_id']=$this->Cotizacion->get_status($cotizaciones_id);

		foreach($carrito as $k => $c) {
			$data['subtotal'] += ($c['precio'] * $c['cantidad']);
			foreach ($c['accesorios'] as $l => $a) {
				$data['subtotal'] += $a['precio'] * $a['cantidad'];
			}
		}
		$data['aplicar_cupon']=$this->Frontend->cupones_puede();
		$data['formas_pago']=$this->Cotizacion->forma_pago;
		$data['condiciones_pago']=$this->Cotizacion->condiciones_pago;
		$data['titulo']='Cotizaci&oacute;n';
		$data['evento_estado'] = $evento_estado;

		$this->load->view('frontend/cotizacion_compra',$data);
	}


	private function _valida_orden_compra($cotizaciones_id, $data)
	{
		$cotizacion = $this->base->read('cotizaciones', $cotizaciones_id);
		$venta_directa = $this->Cotizacion->es_venta_directa($cotizacion->cuentas_id);

		$orden_firmada = false;
		$recibo_pago = false;

		if (!empty($cotizacion->orden_firmada_cdn))//file_exists(FCPATH . "files/cotizaciones/{$cotizaciones_id}/orden_firmada.jpg") || file_exists(FCPATH . "files/cotizaciones/{$cotizaciones_id}/orden_firmada.pdf"))
			$orden_firmada = true;

		if (!empty($cotizacion->recibo_pago_cdn))//file_exists(FCPATH . "files/cotizaciones/{$cotizaciones_id}/recibo_pago.jpg") || file_exists(FCPATH . "files/cotizaciones/{$cotizaciones_id}/recibo_pago.pdf"))
			$recibo_pago = true;

		// Venta directa
		if($venta_directa)
		{
			// Falta la documentacion
			if (!$recibo_pago || !$orden_firmada)
			{
				$this->session->set_flashdata('error', 'Debe cargarse el comprobante de pago y la orden firmada.');
				redirect('frontends/enviar_compra/'.$cotizaciones_id);
			}
		}
		else
		{
			// Venta del distribuidor
			if($_POST['acepta_terminos'] == 1)
			{
				$cuenta = $this->base->read('cuentas',$cotizacion->cuentas_id);
				if($cuenta->credito != 1)
				{
					if (!$recibo_pago)
					{
						$this->session->set_flashdata('error', 'Debe cargarse el comprobante de pago.');
						redirect('frontends/enviar_compra/'.$cotizaciones_id);
					}
				}
			}
			else
			{
				// Falta aceptar terminos y condiciones
				$this->session->set_flashdata('error', 'Debe aceptar los t&eacute;rminos y condiciones.');
				redirect('frontends/enviar_compra/'.$cotizaciones_id);
			}
		}

		$this->Cotizacion->cambia_status($cotizaciones_id,3);
		$this->Cotizacion->genera_folio_compra($cotizaciones_id);
		$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
		redirect('cotizaciones/index');
	}

	function mostrar_detalle()
	{
		$this->session->set_userdata('mostrar_detalles', !$this->session->userdata('mostrar_detalles'));
		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}

	function construccion()
	{
		$datos['titulo']='En construcción';
		$this->load->view('frontend/construccion',$datos);
	}

	function autenticacion()
	{
		/*
         * SI ES UN EXTERNO SE SOLICITA AUTENTICAR PARA PODER GENERAR PEDIDOS
         */
		$productos=$this->session->userdata('productos');
		$datos=$this->_init_miele();
		$this->session->set_userdata('productos',$productos);

		if(!empty($_POST['email']))
		{
			$existe=$this->Frontend->existe_cliente($_POST['email']);// RETORNA EL USUARIO
			if($existe)
			{
				$data['usuario']=$_POST['email'];
				$data['contrasena']=$_POST['contrasena'];
				$login=$this->base->login($data);

				if($login === TRUE)
				{
					$this->session->set_flashdata('done', "¡Bienvenido, {$_POST['email']}!");
					if($productos)
						redirect('frontends/informacion_cliente');
					else
						redirect('frontends/index');
				}
				elseif($login==='DESACTIVADO')
				{
					$datos['flashdata']['error']='Lo sentimos, su usuario se encuentra desactivado, para m&aacute;s informaci&oacute;n p&oacute;ngase en contacto con el administrador.';
				}
				elseif($login==='ERROR')
				{
					$datos['flashdata']['error']='El usuario y/o contrase&ntilde;a son incorrectos.';
				}
				elseif($login==='MANTENIMIENTO')
				{
					$datos['flashdata']['error']='El sistema se encuentra en mantenimiento, por favor intente nuevamente m&aacute;s tarde.';
				}
			}
			else
			{
				$datos['flashdata']['error']='El usuario y/o contrase&ntilde;a son incorrectos.';
				redirect('frontends/registro/1');
			}
		}

		$datos['registro_exitoso']=$this->session->userdata('logged')?TRUE:FALSE;
		$datos['titulo']='Confirmaci&oacute;n';
		$this->load->view('frontend/shopline/autenticacion',$datos);
	}

	function registro()
	{
		/*
         * REGISTRO DE USUARIOS EXTERNOS
        */

		// RECUPERO CARRITO DE LA SESION
		$productos=$this->session->userdata('productos');
		$datos=$this->_init_miele();
		$this->session->set_userdata('productos',$productos);

		$existe=TRUE;
		if(!empty($_POST))
		{
			$reglas=$this->form_validation->_config_rules['registro'];
			if(empty($_POST['id']))
			{
				$reglas[]=array(
					'field' => 'contrasena_registro',
					'label' => 'contrase&ntilde;a',
					'rules' => 'trim|required'
				);
				$reglas[]=array(
					'field' => 'confirmar_contrasena',
					'label' => 'confirmar contrase&ntilde;a',
					'rules' => 'trim|required|matches[contrasena_registro]'
				);
			}
			else
			{
				if(isset($_POST['contrasena']) && !empty($_POST['contrasena']))
				{
					$reglas[]=array(
						'field' => 'confirmar_contrasena',
						'label' => 'confirmar contrase&ntilde;a',
						'rules' => 'trim|matches[contrasena]'
					);
				}
			}

			$this->form_validation->set_rules($reglas);
			if($this->form_validation->run())
			{
				$data=array(
					'grupos_id'=>$this->Frontend->grupo_externo_get(),
					'cuentas_id'=>$this->Frontend->cuenta_externo_get(),
					'usuario'=>$this->input->post('email_registro'),
					'nombre'=>$this->input->post('nombre'),
					'apellido_paterno'=>$this->input->post('apellido_paterno'),
					'apellido_materno'=>$this->input->post('apellido_materno'),
					'email'=>$this->input->post('email_registro'),
					'telefono'=>$this->input->post('telefono'),
					'celular'=>$this->input->post('celular'),
					'activo'=>1,
					'cliente_externo'=>1,
					'contrasena'=>MD5($this->input->post('contrasena_registro').$this->config->item('encryption_key')),
					'eliminado'=>0,
				);

				//if($this->session->userdata('id'))
				//$data['id']=$this->session->userdata('id');
				$id=$this->base->guarda('usuarios',$data);

				$login=array('usuario'=>$data['usuario'],'contrasena'=>$_POST['contrasena_registro']);
				$logged_externo=$this->base->login($login);
				$this->session->set_flashdata('done', '¡Bienvenido! Su cuenta ha sido creada correctamente.');
				if($productos && $this->session->userdata('logged'))
					redirect('frontends/informacion_cliente');
				else
					redirect('frontends/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}

		//$datos['registro_exitoso']=$this->session->userdata('logged')?TRUE:FALSE;
		$datos['titulo']='Autenticaci&oacute;n';
		$this->load->view('frontend/shopline/autenticacion',$datos);
	}

	function email_unico($str)
	{
		$id=NULL;
		if(isset($_POST['id']))
			$id=$_POST['id'];
		$unico=$this->base->unico('usuarios','email',$str,$id);
		if (!$unico)
		{
			$this->form_validation->set_message('email_unico', 'El email ya se encuentra registrado, favor de ingresar uno diferente.');
			return FALSE;
		}
		return TRUE;
	}

	function informacion_cliente()
	{
		// SI NO ESTÁ LOGUEADO REDIRECCIONAMOS A LOGUEO Y/O REGISTRO.
		$this->Frontend->cotizacion_verifica_session();

		// SI NO TIENE PRODUCTOS EN EL CARRITO NO PERMITE CAPTURAR INFORMACION DE CLIENTE
		$this->Frontend->cotizacion_verifica_carrito();

		$this->load->Model('Sepomex_model','SP');
		$datos=$this->_init_miele();

		if(!empty($_POST))
		{
			if($this->form_validation->run('informacion_cliente'))
			{
				$cotizaciones_id=$this->carrito_guarda($this->session->userdata('cotizaciones_id'));//debug($cotizaciones_id);exit;
				$data=$_POST;
				$data['evento_estado'] = $this->session->userdata('evento_estado');

				$_POST['promociones_id']=$this->base->get_dato('promociones_id','cotizaciones',array('id'=>$cotizaciones_id));
				$data['id']=$this->session->userdata('cotizaciones_id');
				$cotizaciones_id=$this->base->guarda('cotizaciones',mayuscula_array($data));

				$promocion_opcional=array();//debug($this->session->userdata('promocion_opcional'));
				if($this->session->userdata('promocion_opcional'))
					$promocion_opcional=$this->Promocion->regalos_get($_POST['promociones_id']);
				$_POST['promocion_opcional']=$promocion_opcional?$promocion_opcional:array();

				$this->calcular($cotizaciones_id,$_POST,TRUE);

				unset($data['nombre_contacto']);
				unset($data['telefono_particular']);
				unset($data['telefono_celular']);
				unset($data['descuento_opcional']);
				unset($data['descuento_paquete']);
				unset($data['entrega_fecha_tentativa']);
				unset($data['entrega_fecha_instalacion']);
				unset($data['descuento_cupon']);
				unset($data['cupones_id']);
				unset($data['folio_cupon']);
				unset($data['opcion_cupon_id']);
				unset($data['producto_regalo_id']);
				unset($data['evento_estado']);
				$data['id']=$this->session->userdata('id');
				$usuarios_id=$this->base->guarda('usuarios',$data);

				redirect('frontends/confirmacion/'.$cotizaciones_id);
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['r']=$_POST;
				$datos['municipios']=$this->SP->get_municipios($datos['r']['estado']);
				$datos['entrega_municipios']=$this->SP->get_municipios($datos['r']['entrega_estado']);
				$datos['instalacion_municipios']=$this->SP->get_municipios($datos['r']['instalacion_estado']);
			}
		}
		else
		{
			if($this->session->userdata('cotizaciones_id'))
			{
				$datos['r']=$this->base->read('cotizaciones',$this->session->userdata('cotizaciones_id'),TRUE);
				$datos['municipios']=$this->SP->get_municipios($datos['r']['estado']);
				$datos['entrega_municipios']=$this->SP->get_municipios($datos['r']['entrega_estado']);
				$datos['instalacion_municipios']=$this->SP->get_municipios($datos['r']['instalacion_estado']);
			}
			else
			{
				$datos['r']=$this->base->read('usuarios',$this->session->userdata('id'),TRUE);
				$datos['municipios']=$this->SP->get_municipios($datos['r']['estado']);
				$datos['entrega_municipios']=$this->SP->get_municipios($datos['r']['entrega_estado']);
				$datos['instalacion_municipios']=$this->SP->get_municipios($datos['r']['instalacion_estado']);
				$datos['r']['nombre_contacto']=$datos['r']['nombre'].' '.$datos['r']['apellido_paterno'].' '.$datos['r']['apellido_materno'];
				$datos['r']['telefono_celular']=$datos['r']['celular'];
				$datos['r']['telefono_particular']=$datos['r']['telefono'];
			}
		}
		$datos['cupon'] = $this->session->userdata('cupon');
		$datos['estados']=$this->SP->get_estados();
		$datos['titulo']='Informaci&oacute;n';
		$this->load->view('frontend/shopline/info_cliente',$datos);
	}

	function confirmacion($cotizaciones_id,$metodo_pago_id=FALSE)
	{
		// SI NO ESTÁ LOGUEADO REDIRECCIONAMOS A LOGUEO Y/O REGISTRO.
		$this->Frontend->cotizacion_verifica_session();

		// VERIFICA QUE LA COTIZACION LE PERTENEZCA AL USUARIO EN SESION
		$this->Frontend->cotizacion_verifica_pertenencia($cotizaciones_id);

		// VERIFICA QUE LA COTIZACIÓN AUN NO HA SIDO PAGADA PARA EVITAR PAGOS DUPLICADOS
		$this->Frontend->cotizacion_verifica_pago($cotizaciones_id);

		$this->load->model('Cotizacion');
		$this->load->model('Accesorio');
		$this->load->model('Promocion');
		$datos=$this->_init_miele();
		$datos['r'] = $this->Cotizacion->get_cotizacion($cotizaciones_id);

		if(!empty($_POST))
		{
			if($_POST['confirmado'] && $_POST['acepta_terminos'] && $_POST['acepta_disponibilidad'] && $_POST['metodo_pago_id'])
			{
				$data['id']=$cotizaciones_id;
				$data['acepta_terminos']=$_POST['acepta_terminos'];
				$data['acepta_disponibilidad']=$_POST['acepta_disponibilidad'];
				$data['datos_correctos']=$_POST['confirmado'];
				$data['mensualidades']=NULL;
				if(@$_POST['metodo_pago_id']==3)
				{
					$data['mensualidades']=12;
					$data['promocion_msi']=12;
				}
				elseif(@$_POST['metodo_pago_id']==4)
				{
					$data['mensualidades']=18;
					$data['promocion_msi']=18;
					$data['promociones_id']=NULL;
				}

				$cotizaciones_id=$this->base->guarda('cotizaciones',$data);
				$this->Cotizacion->genera_folio_compra($cotizaciones_id);
				$this->Frontend->pago_procesar($cotizaciones_id,$_POST['metodo_pago_id']);
			}
			else
			{
				$error_message = 'Por favor';

				if(!$_POST['metodo_pago_id'])
					$error_message .= ', seleccione una forma de pago';

				if(!$_POST['confirmado'])
					$error_message .= ', confirme los datos';

				if(!$_POST['acepta_terminos'])
					$error_message .= ', acepte t&eacute;rminos y condiciones';

				if(!$_POST['acepta_disponibilidad'])
					$error_message .= ', acepte que la disponibilidad del equipo depender&aacute; de acuerdo al inventario';

				$error_message.='.';
				$datos['flashdata']['error']=$error_message;

				$datos['r']['cotizacion']['acepta_terminos']=$_POST['acepta_terminos'];
				$datos['r']['cotizacion']['acepta_disponibilidad']=$_POST['acepta_disponibilidad'];
				$datos['r']['cotizacion']['metodo_pago_id']=@$_POST['metodo_pago_id'];
			}
		}

		// SIN DESCUENTO
		$post['entrega_estado']=$datos['r']['cotizacion']['entrega_estado'];
		$post['instalacion_estado']=$datos['r']['cotizacion']['instalacion_estado'];
		$post['descuento_opcional']=0;
		$post['descuento_paquete']=0;
		$post['descuento_cupon']=$datos['r']['cotizacion']['descuento_cupon'];
		$post['cupones_id']=$datos['r']['cotizacion']['cupones_id'];
		$post['opcion_cupon_id']=$datos['r']['cotizacion']['opcion_cupon_id'];
		$sin_descuento=$this->calcular($cotizaciones_id,$post);

		$datos['sin_descuento']['importe_cliente'] = @$sin_descuento['importe_cliente'];
		$datos['sin_descuento']['importe_cliente_acc'] = @$sin_descuento['importe_cliente_acc'];
		$datos['sin_descuento']['importe_cliente_acc_individual'] = @$sin_descuento['importe_cliente_acc_individual'];

		$datos['sin_descuento']['importe_total'] = $sin_descuento['importe_venta'];
		$descuento_cliente = @$sin_descuento['descuento_comercial_cliente'];
		if(@$sin_descuento['descuento_cliente_cupon'])
			$descuento_cliente=$descuento_cliente>@$sin_descuento['descuento_cliente_cupon']?$descuento_cliente-@$sin_descuento['descuento_cliente_cupon']:0;
		$datos['sin_descuento']['descuento_cliente'] = $descuento_cliente;
		$datos['sin_descuento']['promocion_porcentaje_monto'] = 0;
		$datos['sin_descuento']['promocion_fija'] = 0;
		$datos['sin_descuento']['subtotal_cliente'] = $sin_descuento['subtotal_cliente'];
		$datos['sin_descuento']['envio'] = $sin_descuento['envio'];
		$datos['sin_descuento']['iva_cliente'] = $sin_descuento['iva_cliente'];
		$datos['sin_descuento']['total_cliente'] = $sin_descuento['total_cliente'];

		$datos['productos'] = $this->Cotizacion->get_productos_cotizacion($cotizaciones_id);
		$datos['accesorios_individuales'] = $this->Accesorio->get_accesorios_individuales($cotizaciones_id);

		// CON DESCUENTO
		$post['entrega_estado']=$datos['r']['cotizacion']['entrega_estado'];
		$post['instalacion_estado']=$datos['r']['cotizacion']['instalacion_estado'];
		$post['descuento_opcional']=1;
		$post['descuento_paquete']=1;
		$post['descuento_cupon']=$datos['r']['cotizacion']['descuento_cupon'];
		$post['cupones_id']=$datos['r']['cotizacion']['cupones_id'];
		$post['opcion_cupon_id']=$datos['r']['cotizacion']['opcion_cupon_id'];

		$descuento=$this->calcular($cotizaciones_id,$post);
		$banamex_msi_vigente=strtotime($this->config->item('banamex_msi_vigencia'))>strtotime(date('Y-m-d H:i:s'));
		$msi_banamex = @$metodo_pago_id==4 && $banamex_msi_vigente?TRUE:FALSE;
		$promocion_opcional = in_array($metodo_pago_id,array(3,4))?FALSE:$this->session->userdata('promocion_opcional');
		$datos['promociones']=$this->Promocion->get_promociones($datos['productos'], $datos['accesorios_individuales'], $descuento['importe_venta'],$msi_banamex, $promocion_opcional);
		$post['promociones_id']=@$datos['r']['cotizacion']['promociones_id'];//$datos['promociones']?TRUE:FALSE;

		if(!array_key_exists($post['promociones_id'],$datos['promociones']))
		{
			$post['promociones_id']=FALSE;
			$this->session->set_userdata('promociones_id',FALSE);
			$datos['promociones']=array();
		}
		$promocion_opcional_regalos=array();
		if($promocion_opcional && !in_array($metodo_pago_id,array(3,4)))
			$promocion_opcional_regalos = $this->Promocion->regalos_get($post['promociones_id']);
		$post['promocion_opcional']=$promocion_opcional_regalos?$promocion_opcional_regalos:array();

		$descuento=$this->calcular($cotizaciones_id,$post);

		$datos['r']['cotizacion']['promocion_opcional_descuento']=$descuento['promocion_opcional_descuento'];

		if(@$datos['r']['cotizacion']['promociones_id'])
		{
			$this->promocion_regalos_add($cotizaciones_id);
			$promociones_productos = $this->session->userdata('promociones_productos');
			$promociones_alianzas = $this->session->userdata('promociones_alianzas');
		}

		$datos['metodo_pago_id']=$metodo_pago_id;
		$datos['promociones_productos']=@$promociones_productos;
		$datos['promociones_alianzas']=@$promociones_alianzas;
		$datos['descuento']['importe_cliente'] = @$descuento['importe_cliente'];
		$datos['descuento']['importe_cliente_acc'] = @$descuento['importe_cliente_acc'];
		$datos['descuento']['importe_cliente_acc_individual'] = @$descuento['importe_cliente_acc_individual'];

		$datos['descuento']['importe_total'] = $descuento['importe_venta'];
		$descuento_cliente = @$descuento['descuento_comercial_cliente']-@$descuento['promocion_opcional_descuento'];
		if(@$descuento['descuento_cliente_cupon'])
			$descuento_cliente = $descuento_cliente>@$descuento['descuento_cliente_cupon']?$descuento_cliente-@$descuento['descuento_cliente_cupon']:0;
		$datos['descuento']['descuento_cliente'] = $descuento_cliente;
		$datos['descuento']['promocion_porcentaje_monto'] = @$descuento['promocion_descuento_porcentaje_monto']?$descuento['promocion_descuento_porcentaje_monto']:0;
		$datos['descuento']['promocion_fija'] = @$descuento['promocion_descuento_fijo']?$descuento['promocion_descuento_fijo']:0;
		$datos['descuento']['subtotal_cliente'] = $descuento['subtotal_cliente'];
		$datos['descuento']['envio'] = $descuento['envio'];
		$datos['descuento']['iva_cliente'] = $descuento['iva_cliente'];
		$datos['descuento']['total_cliente'] = $descuento['total_cliente'];

		if($datos['r']['cotizacion']['producto_regalo_id'])
		{
			$this->load->model('Producto');
			$datos['producto_regalo'] = $this->base->get_datos('id, nombre, modelo, precio, unidad_id','productos', array('id'=>$datos['r']['cotizacion']['producto_regalo_id']));
			$datos['producto_regalo']->img_id = $this->Producto->get_imagen_principal($datos['r']['cotizacion']['producto_regalo_id']);
			$datos['producto_regalo']->cantidad = 1;
			$datos['producto_regalo']->unidad = elemento('Unidades', $datos['producto_regalo']->unidad_id);
		}

		$datos['cotizaciones_id']=$cotizaciones_id;
		$datos['banamex_msi_vigencia']=strtotime($this->config->item('banamex_msi_vigencia'))>strtotime(date('Y-m-d H:i:s'));
		$datos['cliente']= $this->base->read('usuarios', $datos['r']['cotizacion']['usuario_id']);
		$datos['titulo']='Confirmar Datos';

		$this->load->view('frontend/shopline/confirmacion',$datos);
	}

	function carrito_guarda($cotizaciones_id=NULL)
	{
		//GUARDA CARRITO DE CLIENTE EXTERNO
		$this->load->model('Cotizacion');
		$this->load->model('Accesorio');
		$this->load->model('Promocion');
		$carrito = $this->session->userdata('productos');
		$productos=array();
		if(!empty($carrito))
		{
			$i=0;
			foreach($carrito as $k => $p)
			{
				$producto = $this->Cotizacion->get_producto($k, $p['accesorios']);
				$producto->precio = $p['precio'];
				$producto->cantidad = $p['cantidad'];
				//$producto->descuento_cliente = isset($p['descuento_cliente'])?$p['descuento_cliente']:0;
				//$producto->importe_cliente = isset($p['importe_cliente'])?$p['importe_cliente']:0;
				$producto->descuento_distribuidor = isset($p['descuento_distribuidor'])?$p['descuento_distribuidor']:0;
				$producto->importe_distribuidor = isset($p['importe_distribuidor'])?$p['importe_distribuidor']:0;
				$productos[] = $producto;
			}
		}

		if(!empty($_POST))
		{
			$datos=array(
				'fecha'=>date('Y-m-d'),
				'usuario_id'=>$this->session->userdata('id'),
				'cuentas_id'=>$this->session->userdata('cuentas_id'),
				'status_id'=>2,
				'id'=>$cotizaciones_id,
				'descuento_opcional'=>1,
				'descuento_paquete'=>1,
				'descuento_cupon'=>$_POST['descuento_cupon'],
				'cupones_id'=>$_POST['cupones_id'],
				'folio_cupon'=>$_POST['folio_cupon'],
				'opcion_cupon_id'=>$_POST['opcion_cupon_id'],
				'descuento_porcentaje_cupon'=>$_POST['opcion_cupon_id']==1?$this->base->get_dato('porcentaje_descuento','cupones',array('id'=>$_POST['cupones_id'])):NULL,
				'msi_cupon'=>$_POST['opcion_cupon_id']==1?$this->base->get_dato('meses_sin_intereses','cupones',array('id'=>$_POST['cupones_id'])):NULL
			);

			$_POST['descuento_opcional']=1;
			$_POST['descuento_paquete']=1;

			$accesorios =  $this->Accesorio->get_accesorios_individuales($cotizaciones_id);

			$descuento=$this->calcular($cotizaciones_id,$datos);
			$productos_array=array();
			foreach($productos as $k=>$v)
				$productos_array[$v->id]=(array)$v;

			//$promociones=$this->Promocion->get_promociones($productos_array, $accesorios, $descuento['importe_venta']);
			$datos['promociones_id']=$this->session->userdata('promociones_id');

			$cotizaciones_id=$this->Cotizacion->guarda($datos,$productos, $accesorios );
			if($cotizaciones_id)
			{
				$this->calcular($cotizaciones_id,$datos,TRUE);
			}

			if($cotizaciones_id)
			{
				$this->session->set_userdata('cotizaciones_id', $cotizaciones_id);
			}
			return $cotizaciones_id;
		}
	}

	function pago($cotizaciones_id,$debug=FALSE)
	{
		// SI NO ESTÁ LOGUEADO REDIRECCIONAMOS A LOGUEO Y/O REGISTRO.
		$this->Frontend->cotizacion_verifica_session();

		// VERIFICA QUE LA COTIZACION LE PERTENEZCA AL USUARIO EN SESION
		$this->Frontend->cotizacion_verifica_pertenencia($cotizaciones_id);

		// VERIFICA QUE LA COTIZACIÓN AUN NO HA SIDO PAGADA PARA EVITAR PAGOS DUPLICADOS
		$this->Frontend->cotizacion_verifica_pago($cotizaciones_id);

		$this->load->model('Pago');
		$datos=$this->_init_miele();

		if(!empty($_POST))
		{
			$transaccion=$this->Pago->genera_pago($cotizaciones_id,$debug);
		}
		$datos['cotizaciones_id']=$cotizaciones_id;
		$datos['titulo']='Realizar Pago';
		$this->load->view('frontend/shopline/pago',$datos);
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('frontends/index');
	}

	function recuperar_contrasena()
	{
		if(!empty($_POST))
		{
			if(!empty($_POST['email']))
			{
				$data['usuario']=$this->input->post('email');
				$data['email']=$this->input->post('email');
				$id=$this->base->recuperar_contrasena($data);

				if(!empty($id))
				{
					$this->__usuarios_enviar_contrasena($id,TRUE);
					$this->session->set_flashdata('done', 'Su nueva contrase&ntilde;a ha sido enviada correctamente.');
					$this->output->set_output('<script type="text/javascript">location.href="'.site_url('frontends/autenticacion').'"</script>');
				}
				else
				{
					$datos['flashdata']['error']='Lo sentimos, no se ha encontrado ning&uacute;n usuario registrado con su correo electr&oacute;nico, por favor verifique e intente nuevamente.';
					$datos['titulo']='Recuperar contrase&ntilde;a';
					$this->load->view('frontend/shopline/recuperar_contrasena',$datos);
				}
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['titulo']='Recuperar contrase&ntilde;a';
				$this->load->view('frontend/shopline/recuperar_contrasena',$datos);
			}
		}
		else
		{
			$datos['titulo']='Recuperar contrase&ntilde;a';
			$this->load->view('frontend/shopline/recuperar_contrasena',$datos);
		}

	}

	function mis_datos()
	{
		$datos=$this->_init_miele();
		$this->load->Model('Sepomex_model','SP');
		if(!empty($_POST))
		{
			$_POST['id']=$this->session->userdata('id');
			$reglas=$this->form_validation->_config_rules['usuario'];
			$reglas[2]['rules']='trim|required|max_length[40]|callback_email_unico';
			$this->form_validation->set_rules($reglas);
			if($this->form_validation->run())
			{
				$data=$_POST;
				if(isset($_POST['contrasena']))
					$data['contrasena']=MD5($this->input->post('contrasena').$this->config->item('encryption_key'));
				$usuarios_id=$this->base->guarda('usuarios',$data);
				$this->session->set_flashdata('done', 'Sus datos han sido guardados correctamente.');
				redirect('frontends/mis_datos');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['r']=$_POST;
				$datos['municipios']=$this->SP->get_municipios($datos['r']['estado']);
				$datos['entrega_municipios']=$this->SP->get_municipios($datos['r']['entrega_estado']);
				$datos['instalacion_municipios']=$this->SP->get_municipios($datos['r']['instalacion_estado']);
			}
		}
		else
		{
			$datos['r']=$this->base->read('usuarios',$this->session->userdata('id'),TRUE);
			$datos['municipios']=$this->SP->get_municipios($datos['r']['estado']);
			$datos['entrega_municipios']=$this->SP->get_municipios($datos['r']['entrega_estado']);
			$datos['instalacion_municipios']=$this->SP->get_municipios($datos['r']['instalacion_estado']);
		}

		$datos['estados']=$this->SP->get_estados();
		$datos['titulo']='Mis Datos';
		$this->load->view('frontend/shopline/mis_datos',$datos);
	}

	function mis_pedidos()
	{
		// SI NO ESTÁ LOGUEADO REDIRECCIONAMOS A LOGUEO Y/O REGISTRO.
		$this->Frontend->cotizacion_verifica_session();

		$datos=$this->_init_miele();
		$b=bc_buscador();
		$datos['r']=$this->Frontend->cotizaciones_cliente_externo_find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Frontend->cotizaciones_cliente_externo_count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		$datos['titulo']='Mis Pedidos';
		$this->load->view('frontend/shopline/mis_pedidos',$datos);
	}

	function pedido_detalle($cotizaciones_id)
	{
		// SI NO ESTÁ LOGUEADO REDIRECCIONAMOS A LOGUEO Y/O REGISTRO.
		$this->Frontend->cotizacion_verifica_session();

		// VERIFICA QUE LA COTIZACION LE PERTENEZCA AL USUARIO EN SESION
		$this->Frontend->cotizacion_verifica_pertenencia($cotizaciones_id);

		$datos=$this->_init_miele();
		$this->load->model('Cotizacion');
		$this->load->model('Accesorio');
		$this->load->model('Promocion');

		// OBTENER MENSUALIDADES
		$datos['mensualidades'] = $this->Frontend->mensualidades_datos_get($cotizaciones_id);

		$datos['r']=$this->base->read('cotizaciones',$cotizaciones_id,TRUE);
		$datos['ecommerce_send']=$this->base->get_dato('ecommerce_send','cotizaciones',array('id'=>$cotizaciones_id));
		$datos['numero_autorizacion']=$this->base->get_dato('vpc_AuthorizeId','cotizaciones_pagos',array('cotizaciones_id'=>$cotizaciones_id));
		$datos['productos'] = $this->Cotizacion->get_productos_cotizacion($cotizaciones_id);
		$datos['accesorios_individuales'] = $this->Accesorio->get_accesorios_individuales($cotizaciones_id);
		$datos['promociones']=$this->Promocion->get_promocion_aplicada($cotizaciones_id);//debug($datos['promociones']);
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');
		if($datos['promociones'] && @$datos['promociones']['alianzas'])
		{
			$orden_pagada=$datos['r']['pago_realizado'];
			foreach($datos['promociones']['alianzas'] as $p)
			{
				if($orden_pagada)
				{
					$data=array();
					$id = $this->Cotizacion->get_table_id($cotizaciones_id,NULL,NULL,FALSE, FALSE, $p['alianzas_id']);
					if($id)
						$data['id']=$id;
					$codigo = $this->Promocion->alianza_codigo_get($p['alianzas_id'],$cotizaciones_id, TRUE);
					if($codigo)
					{
						$data['codigo'] = $codigo;
						$id = $this->base->guarda('cotizaciones_alianzas', $data);
					}
				}
			}

			foreach($datos['promociones']['alianzas'] as &$pa)
				$pa['codigo']=$this->base->get_dato('codigo','cotizaciones_alianzas',array('id'=>$pa['id']));
		}

		if($datos['r']['producto_regalo_id'])
		{
			$datos['producto_regalo'] = $this->base->get_datos('id, nombre, modelo, precio, unidad_id','productos', array('id'=>$datos['r']['producto_regalo_id']));
			$datos['producto_regalo']->img_id = $this->Producto->get_imagen_principal($datos['r']['producto_regalo_id']);
			$datos['producto_regalo']->cantidad = 1;
			$datos['producto_regalo']->unidad = elemento('Unidades', $datos['producto_regalo']->unidad_id);
		}
		$datos['evento_estado'] = $this->base->get_dato('evento_estado','cotizaciones',array('id'=>$cotizaciones_id));
		$datos['titulo']='Pedido No. '.$datos['r']['folio_compra'];

		$this->load->view('frontend/shopline/pedido_detalle',$datos);
	}

	function comentarios()
	{
		$datos=$this->_init_miele();
		if(!empty($_POST))
		{
			if($this->form_validation->run('contacto'))
			{
				$this->load->model('Comentario');
				$this->Comentario->comentarios_guarda($_POST);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('frontends/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}

		$datos['titulo']='Cont&aacute;ctenos';
		$this->load->view('frontend/home/comentarios',$datos);
	}


	function comprobante_compra_pdf($cotizaciones_id)
	{
		$this->Frontend->cotizacion_verifica_session(); // SI NO ESTÁ LOGUEADO REDIRECCIONAMOS A LOGUEO Y/O REGISTRO.
		$this->Frontend->cotizacion_verifica_pertenencia($cotizaciones_id); // VERIFICA QUE LA COTIZACION LE PERTENEZCA AL USUARIO EN SESION
		$pago_realizado = $this->base->get_dato('pago_realizado','cotizaciones',array('id'=>$cotizaciones_id));
		if(!$pago_realizado)
			redirect('frontends/mis_pedidos');

		$this->load->model('Cotizacion');
		$this->Cotizacion->imprimir($cotizaciones_id,'I',TRUE,4);

	}

	function comprobante_compra_mail($cotizaciones_id)
	{
		//$this->Frontend->cotizacion_verifica_session(); // SI NO ESTÁ LOGUEADO REDIRECCIONAMOS A LOGUEO Y/O REGISTRO.
		//$this->Frontend->cotizacion_verifica_pertenencia($cotizaciones_id); // VERIFICA QUE LA COTIZACION LE PERTENEZCA AL USUARIO EN SESION
		$pago_realizado = $this->base->get_dato('pago_realizado','cotizaciones',array('id'=>$cotizaciones_id));
		if(!$pago_realizado)
			redirect('frontends/mis_pedidos');

		$this->load->model('Payment');
		$this->Payment->compra_mail($cotizaciones_id);
		$this->session->set_flashdata('done', 'Correo enviado correctamente.');

		redirect('frontends/mis_pedidos');
	}

	public function validar_cupon()
	{
		$this->load->model('Cupon');
		$data = $this->Cupon->validar();
		$data = json_encode($data);
		$this->output->set_output($data);
	}

	/**
	 * @name promocion_view
	 * @description Funcion para ver el detalle de las promociones disponibles
	 * @param null $promocion_id
	 * @param null $promocion_tipo
	 * @param bool $categorias_id
	 * @param bool $productos_id
	 */
	public function promocion_view($promocion_id=NULL,$promocion_tipo=NULL,$categorias_id=FALSE,$productos_id=FALSE)
	{
		$this->load->Model('Promocion');
		$datos=array();
		$datos['titulo']='Promoción';

		if($categorias_id || $productos_id)
		{
			$categoria=FALSE;
			if($categorias_id)
				$categoria=$this->base->read('categorias',$categorias_id);

			$producto=FALSE;
			if($productos_id)
				$producto=$this->base->read('productos',$productos_id);

			$msj='En la compra del producto';
			if($producto)
				$msj.=' <b>'.$producto->nombre.'</b>, usted puede adquirir las siguientes promociones';
			elseif($categorias_id && !$productos_id)
				$msj.=' cualquier producto de la categoría <b>'.$categoria->nombre.'</b>, usted puede adquirir las siguientes promociones';
			$datos['view']=$msj;
			$datos['regalos']=$this->Promocion->categorias_promociones_regalos($categorias_id, $productos_id);
			$this->load->view('promociones/regalos',$datos);
		}
		else
		{
			$datos['view']=FALSE;
			$regalos=$this->Promocion->regalos_get($promocion_id);//debug($regalos);

			$datos['regalos']=array();
			$datos['regalos'][$promocion_id]['promocion']= $regalos['promocion'];
                        if(isset($regalos['productos'][0]))
                            $datos['regalos'][$promocion_id]['productos'][]= $regalos['productos'][0];
			if(@$regalos['accesorios'][0])
				$datos['regalos'][$promocion_id]['accesorios'][]= $regalos['accesorios'][0];

			if($promocion_id)
				$datos['promocion']=$this->Promocion->get_promocion($promocion_id);
			if($promocion_tipo)
				$datos['promocion_tipo']=$promocion_tipo; // 1 = categoria, 2 = producto, 3 = accesorio

			$this->load->view('promociones/regalos',$datos);
		}
	}

	/**
	 * @param $cotizaciones_id
	 * FUNCION PARA ACTIVAR BANDERA DE PROMOCION EN UNA COTIZACION
	 * PARA SABER SI YA SE APLICO ALGUNA PROMOCION
	 */
	public function promocion_aceptar($cotizaciones_id)
	{
		$data['id']=$cotizaciones_id;
		$data['promocion']=$_POST['promocion']?1:0;

		$cot_id = $this->base->guarda('cotizaciones',$data);

		$data = json_encode($cot_id);
		$this->output->set_output($data);
	}

	/**
	 * @param $cotizaciones_id
	 * @return bool
	 * FUNCION PARA AGREGAR A LA SESSION LOS PRODUCTOS Y/O REGALOS QUE SE AGREGARÁN A LA COTIZACIÓN
	 */
	public function promocion_regalos_add($cotizaciones_id=FALSE)
	{
		$promociones_id = $this->session->userdata('promociones_id');

		if($cotizaciones_id && !$promociones_id)
			$promociones_id = $this->base->get_dato('promociones_id','cotizaciones',array('id'=>$cotizaciones_id));

		$this->load->Model('Cotizacion');
		$this->load->Model('Promocion');
		$regalos=$this->Promocion->regalos_get($promociones_id);

		if(empty($regalos) && !$cotizaciones_id)
			return FALSE;

		$this->session->unset_userdata('promociones_productos');

		$promociones=array();
		$i=0;
		if( isset($regalos['productos']) && !empty($regalos['productos']))
		{
			foreach($regalos['productos'] as $r)
			{
				if(!in_array($r['id'], $promociones))
				{
					$this->db->select('fp.id as foto_id');
					$this->db->where('p.id',$r['id']);
					$this->db->where('fp.tipos_id',1);
					$this->db->where('fp.eliminado',0);
					$this->db->join('fotografias_productos as fp', 'fp.productos_id=p.id','left');
					$this->db->limit(1);
					$img_id_ = $this->db->get('productos as p')->row('foto_id');
                                        $img_id = (($img_id_) > 0) ? $img_id_ : 0;
                                        
					$promociones[$i]['id']=$r['id'];
					$promociones[$i]['nombre']=$r['nombre'];
					$promociones[$i]['modelo']=$r['modelo'];
					$promociones[$i]['categorias_id']=$r['categorias_id'];
					$promociones[$i]['precio']=@$r['porcentaje_descuento']<100?$r['precio']:0.00;
					$promociones[$i]['descuento']=@$r['porcentaje_descuento']<100?$r['porcentaje_descuento']:0;
					$promociones[$i]['importe']=@$r['importe']?$r['importe']:0;
                                        $promociones[$i]['path']=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/productos/{$r['id']}/{$img_id}.jpg"): site_url("files/productos/{$r['id']}/{$img_id}.jpg");
					$promociones[$i]['promocion']=1;
					$promociones[$i]['cantidad']=1;
				}
				else
					$promociones[$i]['cantidad']+=1;

				$i++;
			}
		}

		if( isset($regalos['accesorios']) && !empty($regalos['accesorios']))
		{
			foreach($regalos['accesorios'] as $r)
			{
				if(!in_array($r['id'], $promociones))
				{
					$promociones[$i]['id']=$r['id'];
					$promociones[$i]['nombre']=$r['nombre'];
					$promociones[$i]['modelo']=$r['modelo'];
					$promociones[$i]['precio']=@$r['porcentaje_descuento']<100?$r['precio']:0.00;
					$promociones[$i]['descuento']=@$r['porcentaje_descuento']<100?$r['porcentaje_descuento']:0;
					$promociones[$i]['importe']=@$r['importe']?$r['importe']:0;
					$promociones[$i]['tipos_accesorios_id']=$r['tipos_accesorios_id'];
					$promociones[$i]['consumible']=$r['consumible'];
					$orden = $r['imagen_orden']?'_'.$r['imagen_orden']:'';
					$promociones[$i]['path']=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$r['id']}{$orden}.jpg"):"files/accesorios/{$r['id']}{$orden}.jpg";
					$promociones[$i]['imagen_orden']=$r['imagen_orden'];
					$promociones[$i]['promocion']=1;
					$promociones[$i]['cantidad']=1;
				}
				else
					$promociones[$i]['cantidad']+=1;

				$i++;
			}
		}

		if( isset($regalos['consumibles']) && !empty($regalos['consumibles']))
		{
			foreach($regalos['consumibles'] as $r)
			{
				if(!in_array($r['id'], $promociones))
				{
					$promociones[$i]['id']=$r['id'];
					$promociones[$i]['nombre']=$r['nombre'];
					$promociones[$i]['modelo']=$r['modelo'];
					$promociones[$i]['precio']=$r['precio'];
					$promociones[$i]['tipos_accesorios_id']=$r['tipos_accesorios_id'];
					$promociones[$i]['consumible']=$r['consumible'];
					$orden = $r['imagen_orden']?'_'.$r['imagen_orden']:'';
					$promociones[$i]['path']=$this->config->item('cloudfiles')?$this->cloud_files->url_publica("files/accesorios/{$r['id']}{$orden}.jpg"):"files/accesorios/{$r['id']}{$orden}.jpg";
					$promociones[$i]['promocion']=1;
					$promociones[$i]['cantidad']=1;
				}
				else
					$promociones[$i]['cantidad']+=1;

				$i++;
			}
		}

		if($promociones)
			$this->session->set_userdata('promociones_productos',$promociones);

		if( isset($regalos['msi']) && !empty($regalos['msi']))
			$this->session->set_userdata('promociones_msi',$regalos['msi']);

		if( isset($regalos['descuento']['porcentaje']) && !empty($regalos['descuento']['porcentaje']))
			$this->session->set_userdata('promociones_descuento_porcentaje',$regalos['descuento']['porcentaje']);

		if( isset($regalos['descuento']['monto']) && !empty($regalos['descuento']['monto']))
			$this->session->set_userdata('promociones_descuento_monto',$regalos['descuento']['monto']);

		if( isset($regalos['alianzas']) && !empty($regalos['alianzas']))
		{
			foreach($regalos['alianzas'] as &$a)
				$a['codigo']=$this->Cotizacion->alianza_codigo_get($cotizaciones_id,$a['id']);

			$this->session->set_userdata('promociones_alianzas',$regalos['alianzas']);
		}

	}

	/** SETEA EN LA SESIÓN LA PROMOCIÓN SELECCIONADA
	 *  QUE SE APLICARÁ A LA COTIZACIÓN SI HAY MÁS DE UNA PROMOCIÓN
	 */
	public function promocion_change($promociones_id,$cotizaciones_id=FALSE)
	{
		if($promociones_id)
			$this->session->set_userdata('promociones_id',$promociones_id);
		else
			$this->session->set_userdata('promociones_id',FALSE);

		redirect('frontends/cotizacion/'.$cotizaciones_id.'/0/1');
	}

	public function promocion_elegir($cotizaciones_id=NULL)
	{
		$this->load->Model('Promocion');
		$promociones=$this->session->userdata('promociones');

		$data=$this->_init_miele();
		$data['cotizaciones_id']=$cotizaciones_id;
		$data['regalos']= $regalos = $this->Promocion->promociones_regalos_get($promociones);

		// QUITA PROMOCIONES OPCIONALES
		if($regalos)
		{
			foreach($regalos as $promocion_id=>$v)
			{
				if(@$v['productos'])
				{
					foreach($v['productos'] as $k=>$v)
					{
						if(@$v['porcentaje_descuento']<100)
							unset($data['regalos'][$promocion_id]);
					}
				}
			}
		}

		$data['titulo']='Elegir promoción';
		$this->load->view('promociones/elegir',$data);
	}

	public function promocion_set()
	{
		$this->session->set_userdata('promociones_id',$_POST['promocion_id']);
		if(@$_POST['promocion_opcional'])
			$this->session->set_userdata('promocion_opcional',$_POST['promocion_opcional']);
		else
			$this->session->set_userdata('promocion_opcional',FALSE);
		$msj=json_encode(TRUE);
		echo $msj;exit;
	}

	public function promocion_opcional_elegir($productos_id, $categorias_id, $cotizaciones_id=FALSE)
	{
		$this->load->Model('Promocion');
		$datos=array();

		if($cotizaciones_id)
			$datos['cotizacion']=$this->base->read('cotizaciones',$cotizaciones_id,TRUE);

		$datos['producto']=$this->base->read('productos',$productos_id,TRUE);
		$datos['titulo']='Promoción';
		$datos['regalos']=$this->Promocion->promociones_opcionales_get($productos_id,$categorias_id);//debug($datos['regalos']);
		if(empty($datos['regalos']))
		{
			$this->session->set_flashdata('error', 'El producto seleccionado no tiene promociónes vigentes.');
			$this->load->library('user_agent');
			redirect($this->agent->referrer());
		}
		$this->load->view('promociones/regalos_opcionales',$datos);
	}

    public function reproductor($categorias_id)
    {
        $video_orden = $this->base->get_dato('video_orden','productos_categorias',array('id'=>$categorias_id));

        $orden = (!empty($video_orden))?'_'.$video_orden:'';
        $enlace = (!empty($orden))? ($this->config->item('cloudfiles')? $this->cloud_files->url_publica("files/categorias/{$categorias_id}{$orden}"):site_url("files/categorias/{$categorias_id}{$orden}")):'';
        $datos['enlace']=$enlace;

        $this->load->view('frontend/reproductor',$datos);
    }

	public function ecommerce_send()
	{
		$msj=json_encode(FALSE);
		if($_POST['cotizaciones_id'])
		{
			$data['id']=$_POST['cotizaciones_id'];
			$data['ecommerce_send']=1;
			$this->base->guarda('cotizaciones',$data);
			$msj=json_encode(TRUE);
		}
		echo $msj;exit;
	}

	/**
	 * @name visualizar_cupon
	 * @description recibe un conjunto de datos que se van a desencriptar y se van a visualizar en una vista responsiva
	 * @param $datos
	 */
	public function visualizar_cupon($datos)
	{
		$datos_desencriptados = $this->decrypt_string($datos);
		$datos_array = explode(",", $datos_desencriptados);

		$datos = array();
		$datos['folio_compra'] = $datos_array[0];
		$datos['cupon_folio'] = $this->base->get_dato('folio','cupones_folios',array('id'=>$datos_array[2],'usado'=>null));
		$datos['cupon_ruta'] = $cupon_ruta_imagen = $this->cloud_files->url_publica("files/cupones/{$datos_array[1]}/{$datos['cupon_folio']}.jpg");

		$this->load->view('cupones/visualizar_cupon',$datos);
	}

	public function evento_precio_base($productos_id,$evento_estado)
	{
		$postfijo = strtolower(str_replace('%20','_',$evento_estado));
		$evento = $this->base->get_datos('horas_iniciales_'.$postfijo.', precio_inicial_'.$postfijo.', precio_horas_extra_'.$postfijo,'gastos_cursos',array('productos_id'=>$productos_id));
		$precio = $evento->{'precio_inicial_'.$postfijo};

		$msg = '{"precio":"'.precio_con_iva($precio).'"}';
		$this->output->set_output($msg);
	}

	function consumibles()
	{
		$this->load->model('Accesorio');

		$datos['titulo']='Consumibles';
		$datos['categorias_menu']=$this->base->lista('productos_categorias','id','nombre',TRUE,'id','ASC',array('parent_id'=>NULL));
		$datos['usuario']=$this->session->userdata('usuario');
		$datos['num_productos']=count($this->session->userdata('productos'));
		$datos['tipos_accesorios']=$this->Accesorio->get_accesorios_tipos();

		$this->load->view('frontend/consumibles/index',$datos);
	}
}