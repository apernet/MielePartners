<?php
require_once('main.php');
class Promociones extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('promociones');
		$this->load->model('Promocion');
		$this->load->model('Alianza_promocion','AP');
	}
	
	public function index()
	{
		$this->base->verifica('promociones');
		$b=bc_buscador();
		$datos['r']=$this->Promocion->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Promocion->count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	
		$datos['alianzas']=catalogo('alianzas',FALSE);
		$datos['titulo']='Promociones';
		$this->load->view('promociones/index',$datos);
	}

	public function agregar()
	{
		$this->base->verifica('promociones_agregar');
		$datos['alianzas']=$this->AP->get_alianzas_disponibles();
		$datos['alianzas1']=$this->AP->get_alianzas_disponibles(1);
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre','ASC');
		$datos['productos']=$this->base->lista('productos','id','modelo',TRUE,NULL,'ASC',array('ocultar'=>0));
		$datos['accesorios']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'0'));
		$datos['consumibles']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'1'));

		if(!empty($_POST))
		{
			$reglas=$this->form_validation->_config_rules['promociones'];

			$reglas[]=array(
				'field' => 'participantes',
				'label' => 'Participantes',
				'rules' =>  'callback_validacion_participantes_promociones'
			);

			$reglas[]=array(
				'field' => 'regalos',
				'label' => 'Regalos',
				'rules' =>  'callback_validacion_regalos_promociones'
			);

			$reglas[]=array(
				'field' => 'vigencia_desde',
				'label' => 'Válido desde',
				'rules' =>  'callback_validacion_fechas'
			);

			$reglas[]=array(
				'field' => 'vigencia_hasta',
				'label' => 'Válido hasta',
				'rules' =>  'callback_validacion_fechas'
			);

			if(!empty($_POST['regaloAlianzas']))
			{
				$reglas[]=array(
					'field' => 'alianza',
					'label' => 'Alianza',
					'rules' => 'callback_validacion_existencias'
				);
			}

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'vigencia_desde'=>$this->input->post('vigencia_desde'),
					'vigencia_hasta'=>$this->input->post('vigencia_hasta'),
					'porcentaje_descuento'=>$this->input->post('porcentaje_descuento'),
					'monto_descuento'=>$this->input->post('monto_descuento'),
					'monto_minimo'=>$this->input->post('monto_minimo'),
					'meses_sin_intereses'=>$this->input->post('meses_sin_intereses'),
					'activo'=>1,
				);

				$promocion_id=$this->base->guarda('promociones',$data);

				// Promociones - Categorias
				$categorias_array = array();
				if(!empty($_POST['categorias']))
				{
					$categorias_promocion=$this->combinar_arreglos($_POST['categorias'],$_POST['cantidadCategorias']);
					foreach ($categorias_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'productos_categorias_id'=>$k,
							'cantidad'=>$v,
						);

						$categorias_array[] = $data;

						$this->base->guarda('promociones_categorias',$data);
					}
				}

				// Promociones - Productos / eventos
				$productos_array = array();
				if(!empty($_POST['productos']) && !empty($_POST['cantidadProductos']))
				{
					$productos_promocion=$this->combinar_arreglos($_POST['productos'],$_POST['cantidadProductos']);
					foreach ($productos_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'productos_id'=>$k,
							'cantidad'=>$v,
						);

						$productos_array[] = $data;

						$this->base->guarda('promociones_productos',$data);
					}
				}

				// Promociones - Accesorios
				$accesorios_array = array();
				if(!empty($_POST['accesorios']))
				{
					$accesorios_promocion=$this->combinar_arreglos($_POST['accesorios'],$_POST['cantidadAccesorios']);
					foreach ($accesorios_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'accesorios_id'=>$k,
							'consumible'=>0,
							'cantidad'=>$v,
						);

						$accesorios_array[] = $data;

						$this->base->guarda('promociones_accesorios',$data);
					}
				}

				// Promociones - Consumibles
				$consumibles_array = array();
				if(!empty($_POST['consumibles']))
				{
					$consumibles_promocion=$this->combinar_arreglos($_POST['consumibles'],$_POST['cantidadConsumibles']);
					foreach ($consumibles_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'accesorios_id'=>$k,
							'consumible'=>1,
							'cantidad'=>$v,
						);

						$consumibles_array[] = $data;

						$this->base->guarda('promociones_accesorios',$data);
					}
				}

				// Regalos - Productos
				$productosr_array = array();
				if(!empty($_POST['regaloProductos']))
				{
					$productos_regalo_promocion=$this->combinar_arreglos($_POST['regaloProductos'],$_POST['cantidadRegalosProductos'],$_POST['porcentajeRegalosProductos']);
					foreach ($productos_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'productos_id'=>$k,
							'cantidad'=>$v['cantidad'],
							'porcentaje_regalo'=>$v['porcentaje']
						);

						$productosr_array[] = $data;

						$this->base->guarda('promociones_productos_regalo',$data);
					}
				}

				// Regalos - Accesorios
				$accesoriosr_array = array();
				if(!empty($_POST['regaloAccesorios']))
				{
					$accesorios_regalo_promocion=$this->combinar_arreglos($_POST['regaloAccesorios'],$_POST['cantidadRegalosAccesorios'], $_POST['porcentajeRegalosAccesorios']);
					foreach ($accesorios_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'accesorios_id'=>$k,
							'consumible'=>0,
							'cantidad'=>$v['cantidad'],
							'porcentaje_regalo'=>$v['porcentaje']
						);

						$accesoriosr_array[] = $data;

						$this->base->guarda('promociones_accesorios_regalo',$data);
					}
				}

				// Regalos - Consumibles
				$consumiblesr_array = array();
				if(!empty($_POST['regaloConsumibles']))
				{
					$consumibles_regalo_promocion=$this->combinar_arreglos($_POST['regaloConsumibles'],$_POST['cantidadRegalosConsumibles']);
					foreach ($consumibles_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'accesorios_id'=>$k,
							'consumible'=>1,
							'cantidad'=>$v,
						);

						$consumiblesr_array[] = $data;

						$this->base->guarda('promociones_accesorios_regalo',$data);
					}
				}

				// Regalos - Alianzas
				$alianzas_array = array();
				if(!empty($_POST['regaloAlianzas']))
				{
					$alianzas_regalo_promocion=$this->combinar_arreglos($_POST['regaloAlianzas'],$_POST['cantidadRegalosAlianzas']);
					foreach ($alianzas_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'alianzas_id'=>$k,
							'cantidad'=>$v,
						);

						$alianzas_array[] = $data;

						$this->base->guarda('promociones_alianzas_regalo',$data);
					}
				}

				$datos_log = array(
					'nombre'=>$this->input->post('nombre'),
					'vigencia_desde'=>$this->input->post('vigencia_desde'),
					'vigencia_hasta'=>$this->input->post('vigencia_hasta'),
					'porcentaje_descuento'=>$this->input->post('porcentaje_descuento'),
					'monto_descuento'=>$this->input->post('monto_descuento'),
					'monto_minimo'=>$this->input->post('monto_minimo'),
					'meses_sin_intereses'=>$this->input->post('meses_sin_intereses'),
					'participantes_categorias'=>@$categorias_array,
					'participantes_productos'=>@$productos_array,
					'participantes_accesorios'=>@$accesorios_array,
					'participantes_consumibles'=>@$consumibles_array,
					'regalos_productos'=>@$productosr_array,
					'regalos_accesorios'=>@$accesoriosr_array,
					'regalos_consumibles'=>@$consumiblesr_array,
					'regalos_alianzas'=>@$alianzas_array,
				);

				bc_log(5,"Agregó una nueva promoción.", $datos_log);
				
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('promociones/index');
			}else
			{
				$datos['flashdata']['error']='Error de captura, por favor verifique los datos.';
			}
		}
		
		$datos['titulo']='Agregar promoción';
		$this->load->view('promociones/agregar',$datos);
	}
	
	public function activar($promocion_id,$activo)
	{
		$this->base->verifica('promociones_activar');
		 
		$data['id']=$promocion_id;
		$data['activo']=!$activo;
		$this->base->guarda('promociones',$data);
		$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('promociones/index');
	}
	
	public function editar($promocion_id)
	{
		$this->base->verifica('promociones_editar');
		
		if(!empty($_POST))
		{
			$reglas=$this->form_validation->_config_rules['promociones'];

			$reglas[]=array(
				'field' => 'participantes',
				'label' => 'Participantes',
				'rules' =>  'callback_validacion_participantes_promociones'
			);

			$reglas[]=array(
				'field' => 'regalos',
				'label' => 'Regalos',
				'rules' =>  'callback_validacion_regalos_promociones'
			);

			$reglas[]=array(
				'field' => 'vigencia_desde',
				'label' => 'Válido desde',
				'rules' =>  'callback_validacion_fechas'
			);

			$reglas[]=array(
				'field' => 'vigencia_hasta',
				'label' => 'Válido hasta',
				'rules' =>  'callback_validacion_fechas'
			);

			if(!empty($_POST['regaloAlianzas']))
			{
				$reglas[]=array(
					'field' => 'alianza',
					'label' => 'Alianza',
					'rules' => 'callback_validacion_existencias'
				);
			}

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'id'=>$promocion_id,
					'nombre'=>$this->input->post('nombre'),
					'vigencia_desde'=>$this->input->post('vigencia_desde'),
					'vigencia_hasta'=>$this->input->post('vigencia_hasta'),
					'porcentaje_descuento'=>$this->input->post('porcentaje_descuento'),
					'monto_descuento'=>$this->input->post('monto_descuento'),
					'monto_minimo'=>$this->input->post('monto_minimo'),
					'meses_sin_intereses'=>$this->input->post('meses_sin_intereses'),
				);
				
				$promocion_id=$this->base->guarda('promociones', $data);

				// Promociones
				$this->db->delete('promociones_accesorios', array('promociones_id' => $promocion_id));
				$this->db->delete('promociones_categorias', array('promociones_id' => $promocion_id));
				$this->db->delete('promociones_productos', array('promociones_id' => $promocion_id));
				// Regalos
				$this->db->delete('promociones_alianzas_regalo', array('promociones_id' => $promocion_id));
				$this->db->delete('promociones_accesorios_regalo', array('promociones_id' => $promocion_id));
				$this->db->delete('promociones_productos_regalo', array('promociones_id' => $promocion_id));

				// Promociones - Categorias
				$categorias_array = array();
				if(!empty($_POST['categorias']))
				{
					$categorias_promocion=$this->combinar_arreglos($_POST['categorias'],$_POST['cantidadCategorias']);
					foreach ($categorias_promocion as $k=>$v)
					{
						$data=array(
								'promociones_id'=>$promocion_id,
								'productos_categorias_id'=>$k,
								'cantidad'=>$v,
						);

						$categorias_array[] = $data;

						$this->base->guarda('promociones_categorias',$data);
					}
				}

				// Promociones - Productos / eventos
				if(!empty($_POST['productos']) && !empty($_POST['cantidadProductos']))
				{
					$productos_promocion=$this->combinar_arreglos($_POST['productos'],$_POST['cantidadProductos']);
					foreach ($productos_promocion as $k=>$v)
					{
						$data=array(
								'promociones_id'=>$promocion_id,
								'productos_id'=>$k,
								'cantidad'=>$v,
						);
						$this->base->guarda('promociones_productos',$data);
					}
				}

				// Promociones - Accesorios
				if(!empty($_POST['accesorios']))
				{
					$accesorios_promocion=$this->combinar_arreglos($_POST['accesorios'],$_POST['cantidadAccesorios']);
					foreach ($accesorios_promocion as $k=>$v)
					{
						$data=array(
								'promociones_id'=>$promocion_id,
								'accesorios_id'=>$k,
								'consumible'=>0,
								'cantidad'=>$v,
						);
						$this->base->guarda('promociones_accesorios',$data);
					}
				}

				// Promociones - Consumibles
				if(!empty($_POST['consumibles']))
				{
					$consumibles_promocion=$this->combinar_arreglos($_POST['consumibles'],$_POST['cantidadConsumibles']);
					foreach ($consumibles_promocion as $k=>$v)
					{
						$data=array(
								'promociones_id'=>$promocion_id,
								'accesorios_id'=>$k,
								'consumible'=>1,
								'cantidad'=>$v,
						);
						$this->base->guarda('promociones_accesorios',$data);
					}
				}

				// Regalos - Productos
				if(!empty($_POST['regaloProductos']))
				{
					$productos_regalo_promocion=$this->combinar_arreglos($_POST['regaloProductos'],$_POST['cantidadRegalosProductos'],$_POST['porcentajeRegalosProductos']);
					foreach ($productos_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'productos_id'=>$k,
							'cantidad'=>$v['cantidad'],
							'porcentaje_regalo'=>$v['porcentaje'],
						);
						$this->base->guarda('promociones_productos_regalo',$data);
					}
				}

				// Regalos - Accesorios
				if(!empty($_POST['regaloAccesorios']))
				{
					$accesorios_regalo_promocion=$this->combinar_arreglos($_POST['regaloAccesorios'],$_POST['cantidadRegalosAccesorios'],$_POST['porcentajeRegalosAccesorios']);
					foreach ($accesorios_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'accesorios_id'=>$k,
							'consumible'=>0,
							'cantidad'=>$v['cantidad'],
							'porcentaje_regalo'=>$v['porcentaje']
						);
						$this->base->guarda('promociones_accesorios_regalo',$data);
					}
				}

				// Regalos - Consumibles
				if(!empty($_POST['regaloConsumibles']))
				{
					$consumibles_regalo_promocion=$this->combinar_arreglos($_POST['regaloConsumibles'],$_POST['cantidadRegalosConsumibles']);
					foreach ($consumibles_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'accesorios_id'=>$k,
							'consumible'=>1,
							'cantidad'=>$v,
						);
						$this->base->guarda('promociones_accesorios_regalo',$data);
					}
				}

				// Regalos - Alianzas
				if(!empty($_POST['regaloAlianzas']))
				{
					$alianzas_regalo_promocion=$this->combinar_arreglos($_POST['regaloAlianzas'],$_POST['cantidadRegalosAlianzas']);
					foreach ($alianzas_regalo_promocion as $k=>$v)
					{
						$data=array(
							'promociones_id'=>$promocion_id,
							'alianzas_id'=>$k,
							'cantidad'=>$v,
						);
						$this->base->guarda('promociones_alianzas_regalo',$data);
					}
				}

				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('promociones/index');
			}else
			{
				$datos['flashdata']['error']='Error de captura, por favor verifique los datos.';
			}
			
			$datos['promocion'] = new stdClass();
			$datos['promocion']->nombre=@$_POST['nombre'];
			$datos['promocion']->vigencia_desde=@$_POST['vigencia_desde'];
			$datos['promocion']->vigencia_hasta=@$_POST['vigencia_hasta'];
			$datos['promocion']->porcentaje_descuento=@$_POST['porcentaje_descuento'];
			$datos['promocion']->monto_descuento=@$_POST['monto_descuento'];
			$datos['promocion']->monto_minimo=@$_POST['monto_minimo'];
			$datos['promocion']->meses_sin_intereses=@$_POST['meses_sin_intereses'];
			//Promoción participantes
			$datos['categorias_promocion']=@$this->combinar_arreglos($_POST['categorias'],$_POST['cantidadCategorias']);
			$datos['productos_promocion']=@$this->combinar_arreglos($_POST['productos'],$_POST['cantidadProductos']);
			$datos['consumibles_promocion']=@$this->combinar_arreglos($_POST['consumibles'],$_POST['cantidadConsumibles']);
			$datos['accesorios_promocion']=@$this->combinar_arreglos($_POST['accesorios'],$_POST['cantidadAccesorios']);
			//Promoción Regalos
			$datos['alianzas_regalo_promocion']=@$this->combinar_arreglos($_POST['regaloAlianzas'],$_POST['cantidadRegalosAlianzas']);
			$datos['productos_regalo_promocion']=@$this->combinar_arreglos($_POST['regaloProductos'],$_POST['cantidadRegalosProductos'],$_POST['porcentajeRegalosProductos']);
			$datos['accesorios_regalo_promocion']=@$this->combinar_arreglos($_POST['regaloAccesorios'],$_POST['cantidadRegalosAccesorios'],$_POST['porcentajeRegalosAccesorios']);
			$datos['consumibles_regalo_promocion']=@$this->combinar_arreglos($_POST['regaloConsumibles'],$_POST['cantidadRegalosConsumibles']);

		}else
		{
			// Promoción participantes
			$datos['promocion']=$this->base->read('promociones',$promocion_id);
			$datos['categorias_promocion']=$this->Promocion->get_categorias($promocion_id);
			$datos['productos_promocion']=$this->Promocion->get_productos($promocion_id);
			$datos['accesorios_promocion']=$this->Promocion->get_accesorios($promocion_id);
			$datos['consumibles_promocion']=$this->Promocion->get_accesorios($promocion_id, 1);
			// Promoción regalos
			$datos['productos_regalo_promocion']=$this->Promocion->get_productos($promocion_id, 1);
			$datos['accesorios_regalo_promocion']=$this->Promocion->get_accesorios($promocion_id, 0, 1);
			$datos['consumibles_regalo_promocion']=$this->Promocion->get_accesorios($promocion_id, 1, 1);
			$datos['alianzas_regalo_promocion']=$this->Promocion->get_alianzas($promocion_id);
		}

		$datos['alianzas']=$this->AP->get_alianzas_disponibles();
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre','ASC');
		$datos['productos']=$this->base->lista('productos','id','modelo',TRUE,NULL,'ASC',array('ocultar'=>0));
		$datos['accesorios']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'0'));
		$datos['consumibles']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'1'));
	
		$datos['titulo']='Editar promoción';
		$this->load->view('promociones/editar',$datos);
	}

	/**
	 * Valida que el usuario haya seleccionado al menos un participante en la promoción
	 * @return bool
	 */
	function validacion_participantes_promociones()
	{
		unset($_POST['categorias'][0]);
		unset($_POST['cantidadCategorias'][0]);
		unset($_POST['productos'][0]);
		unset($_POST['cantidadProductos'][0]);
		unset($_POST['accesorios'][0]);
		unset($_POST['cantidadAccesorios'][0]);
		unset($_POST['consumibles'][0]);
		unset($_POST['cantidadConsumibles'][0]);

		if (empty($_POST['categorias']) && empty($_POST['productos']) && empty($_POST['accesorios']) && empty($_POST['consumibles']) && empty($_POST['monto_minimo'])) {
			$this->form_validation->set_message('validacion_participantes_promociones', 'Debe seleccionar al menos una categor&iacute;a, producto/evento, accesorio, consumible o definir un monto mínimo al que se le asignar&aacute; la promoci&oacute;n.');
			return false;
		}

		return true;

	}

	/**
	 * Valida que el usuario haya seleccionado al menos un regalo participante en la promoción
	 * @return bool
	 */
	function validacion_regalos_promociones()
	{
		unset($_POST['regaloProductos'][0]);
		unset($_POST['cantidadRegalosProductos'][0]);
		unset($_POST['regaloAccesorios'][0]);
		unset($_POST['cantidadRegalosAccesorios'][0]);
		unset($_POST['regaloConsumibles'][0]);
		unset($_POST['cantidadRegaloConsumibles'][0]);
		unset($_POST['regaloAlianzas'][0]);
		unset($_POST['cantidadRegaloAlianzas'][0]);

		if(empty($_POST['meses_sin_intereses']) && empty($_POST['porcentaje_descuento']) && empty($_POST['monto_descuento']) && empty($_POST['regaloProductos']) && empty($_POST['regaloAccesorios']) && empty($_POST['regaloConsumibles'])  && empty($_POST['regaloAlianzas']))
		{
			$this->form_validation->set_message('validacion_regalos_promociones', 'Debe seleccionar al menos una de las opciones de regalos.');
			return false;
		}

		return true;

	}

	/**
	 * Combina los arreglos producto, accesorio, consumible, alianza y categorias con sus respectivos arreglos de cantidades
	 * @param array $arreglo1
	 * @param array $arreglo2
	 * @return array|bool
	 */
	function combinar_arreglos($arreglo1, $arreglo2, $arreglo3=array())
	{
		if(empty($arreglo1))
			return false;

		$arreglo = array();
		foreach($arreglo1 as $clave=>$valor)
		{
			if(@$arreglo[$valor])
			{
				if($arreglo3)
				{
					$arreglo[$valor]['cantidad']+=(@$arreglo2[$clave])?$arreglo2[$clave]:1;
					$arreglo[$valor]['porcentaje']=(@$arreglo3[$clave])?$arreglo3[$clave]:100;
				}
				else
				{
					$arreglo[$valor]+=(@$arreglo2[$clave])?$arreglo2[$clave]:1;
				}
			}
			else
			{
				if($arreglo3)
				{
					$arreglo[$valor]['cantidad']=(@$arreglo2[$clave])?$arreglo2[$clave]:1;
					$arreglo[$valor]['porcentaje']=(@$arreglo3[$clave])?$arreglo3[$clave]:100;
				}
				else
				{
					$arreglo[$valor]=(@$arreglo2[$clave])?$arreglo2[$clave]:1;
				}
			}
		}

		return $arreglo;
	}

	/**
	 * Valida que la fecha de Valido Desde sea menor que la fecha de Valido hasta
	 * @return bool
	 */
	function validacion_fechas()
	{
		if(empty($_POST['vigencia_desde']) || empty($_POST['vigencia_hasta']))
			return false;

		$datetime1 = new DateTime($_POST['vigencia_desde']);
		$datetime2 = new DateTime($_POST['vigencia_hasta']);
		$interval = $datetime1->diff($datetime2);

		if(!$interval->invert && ($interval->d>0 || $interval->m>0 || $interval->y>0))
			return true;
		else
		{
			$this->form_validation->set_message('validacion_fechas', 'La fecha Válida desde debe ser menor a la fecha Válida hasta.');
			return false;
		}
	}

	/**
	 * Valida que la cantidad folios seleccionada en una alianza este disponible
	 * @return bool
	 */
	function validacion_existencias()
	{
		// Combina el arreglo de alianzas con su arreglo de existencias
		$alianzas_regalo_promocion=$this->combinar_arreglos($_POST['regaloAlianzas'],$_POST['cantidadRegalosAlianzas']);

		if($alianzas_regalo_promocion)
		{
			$errores='';
			$i=1;
			foreach ($alianzas_regalo_promocion as $k=>$v)
			{
				// Busca los folios disponibles
				$total_diponibles = $this->base->get_dato('count(usado)','alianzas_folios',array('alianzas_id'=>$k, 'usado'=>0));
				$existencia = $total_diponibles - $v;

				if($existencia<0)
				{
					$errores.='La cantidad disponible de folios para la alizanza de la fila '.$i. ' es '.$total_diponibles.'. ';
				}
				$i++;
			}

			if(empty($errores))
				return true;
			else
			{
				$this->form_validation->set_message('validacion_existencias', $errores);
				return false;
			}
		}
		return true;
	}
}