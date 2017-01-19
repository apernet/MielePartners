<?php
require_once('main.php');
class Cupones extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('cupones');
		$this->load->model('Cupon');
	}
	
	public function index()
	{
		$this->base->verifica('cupones');
		$b=bc_buscador();
		$datos['r']=$this->Cupon->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Cupon->count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	
		$datos['alianzas']=catalogo('alianzas',FALSE);
		$datos['titulo']='Cupones';
		$this->load->view('cupones/index',$datos);
	}

	public function agregar()
	{
		$this->base->verifica('cupones_agregar');
		if(!empty($_POST))
		{
			// Borra datos innecesarios
			unset($_POST['montosIniciales'][0]);
			unset($_POST['montosFinales'][0]);
			unset($_POST['idsProductos'][0]);
			unset($_FILES['imagenes']['name'][0]);
			unset($_FILES['imagenes']['type'][0]);
			unset($_FILES['imagenes']['tmp_name'][0]);
			unset($_FILES['imagenes']['error'][0]);
			unset($_FILES['imagenes']['size'][0]);

			$reglas = $this->form_validation->_config_rules['cupones'];

			$reglas[]=array(
				'field' => 'cupones_imagenes',
				'label' => 'Imágenes para cupones',
				'rules' =>  'callback_cupones_montos_validacion'
			);

			if(!empty($_POST['montosIniciales']))
			{
				$this->cupones_imagenes_filas_validacion();
			}

			$reglas[]=array(
				'field' => 'cupones_descuentos',
				'label' => 'Descuentos',
				'rules' =>  'callback_cupones_descuentos_validacion'
			);

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'alianza_id'=>$this->input->post('alianza_id'),
					'vigencia_desde'=>$this->input->post('vigencia_desde'),
					'vigencia_hasta'=>$this->input->post('vigencia_hasta'),
					'porcentaje_descuento'=>$this->input->post('porcentaje_descuento'),
					'descuento_distribuidor'=>$this->input->post('descuento_distribuidor'),
					'meses_sin_intereses'=>$this->input->post('meses_sin_intereses'),
					'numero_folios'=>$this->input->post('numero_folios'),
					'activo'=>1,
				);
			
				$cupon_id = $this->base->guarda('cupones',$data);
				$this->Cupon->crear_folios_cupon($cupon_id, $_POST['alianza_id'], $_POST['numero_folios']);
				
				if(!empty($_POST['categorias_ids']))
				{
					foreach ($_POST['categorias_ids'] as $id)
					{
						$data=array(
							'cupones_id'=>$cupon_id,
							'productos_categorias_id'=>$id,
						);
						$this->base->guarda('cupones_categorias',$data);
					}
				}
				
				if(!empty($_POST['productos_ids']))
				{
					foreach ($_POST['productos_ids'] as $id)
					{
						$data=array(
								'cupones_id'=>$cupon_id,
								'productos_id'=>$id,
						);
						$this->base->guarda('cupones_productos',$data);
					}
				}
				
				if(!empty($_POST['accesorios_ids']))
				{
					foreach ($_POST['accesorios_ids'] as $id)
					{
						$data=array(
								'cupones_id'=>$cupon_id,
								'accesorios_id'=>$id,
								'consumible'=>0,
						);
						$this->base->guarda('cupones_accesorios',$data);
					}
				}
				
				if(!empty($_POST['consumibles_ids']))
				{
					foreach ($_POST['consumibles_ids'] as $id)
					{
						$data=array(
								'cupones_id'=>$cupon_id,
								'accesorios_id'=>$id,
								'consumible'=>1,
						);
						$this->base->guarda('cupones_accesorios',$data);
					}
				}

				if(!empty($_POST['cuentas_ids']))
				{
					foreach ($_POST['cuentas_ids'] as $id)
					{
						$data=array(
							'cupones_id'=>$cupon_id,
							'cuentas_id'=>$id,
						);
						$this->base->guarda('cupones_cuentas',$data);
					}
				}

				$cupones_imagenes = $this->combinarArreglos($_POST['montosIniciales'],$_POST['montosFinales'],@$_POST['idsImagenes'],@$_POST['extensionesImagenes'],@$_POST['idsProductos'],$cupon_id);
				foreach ($cupones_imagenes as $k=>$v)
				{
					$data = array(
						'cupones_id' => $cupon_id,
						'monto_inicial' => $v->monto_inicial,
						'monto_final' => $v->monto_final,
						'productos_id' => $v->productos_id,
					);

					$id = $this->base->guarda('cupones_imagenes', $data);
					$this->Cupon->cupones_imagen_agregar($cupon_id,$_FILES['imagenes']['tmp_name'][$k],$_FILES['imagenes']['name'][$k],$id);
				}

				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('cupones/index');
			}
			$datos['categorias_cupon']=@$_POST['categorias_ids'];
			$datos['productos_cupon']=@$_POST['productos_ids'];
			$datos['accesorios_cupon']=@$_POST['accesorios_ids'];
			$datos['consumibles_cupon']=@$_POST['consumibles_ids'];
			$datos['cuentas_cupon']=@$_POST['cuentas_ids'];
		}

		$datos['cuentas']=$this->base->lista('cuentas','id','nombre','ASC');
		$datos['alianzas']=catalogo('alianzas',FALSE);
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre','ASC');
		$datos['productos']=$this->base->lista('productos','id','modelo',TRUE,NULL,'ASC',array('ocultar'=>0));
		$datos['productosRegalo']=$this->base->lista('productos','id','nombre',TRUE,NULL,'ASC',array('ocultar'=>'1'));
		$datos['accesorios']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'0'));
		$datos['consumibles']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'1'));
		
		$datos['titulo']='Agregar cupón';
		$this->load->view('cupones/agregar',$datos);
	}
	
	public function activar($cupon_id,$activo)
	{
		$this->base->verifica('cupones_activar');
		 
		$data['id']=$cupon_id;
		$data['activo']=!$activo;
		$this->base->guarda('cupones',$data);
		$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('cupones/index');
	}
	
	public function editar($cupon_id)
	{
		$this->base->verifica('cupones_editar');
		
		if(!empty($_POST))
		{
			// Borra datos innecesarios
			unset($_POST['montosIniciales'][0]);
			unset($_POST['montosFinales'][0]);
			unset($_POST['idsProductos'][0]);
			unset($_FILES['imagenes']['name'][0]);
			unset($_FILES['imagenes']['type'][0]);
			unset($_FILES['imagenes']['tmp_name'][0]);
			unset($_FILES['imagenes']['error'][0]);
			unset($_FILES['imagenes']['size'][0]);

			$reglas = $this->form_validation->_config_rules['cupones'];

			$reglas[]=array(
				'field' => 'cupones_imagenes',
				'label' => 'Imágenes para cupones',
				'rules' =>  'callback_cupones_montos_validacion'
			);

			if(!empty($_POST['montosIniciales']))
			{
				$this->cupones_imagenes_filas_validacion();
			}

			$reglas[]=array(
				'field' => 'cupones_descuentos',
				'label' => 'Descuentos',
				'rules' =>  'callback_cupones_descuentos_validacion'
			);

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				
				$data=array(
						'id'=>$cupon_id,
						'alianza_id'=>$this->input->post('alianza_id'),
						'vigencia_desde'=>$this->input->post('vigencia_desde'),
						'vigencia_hasta'=>$this->input->post('vigencia_hasta'),
						'porcentaje_descuento'=>$this->input->post('porcentaje_descuento'),
						'descuento_distribuidor'=>$this->input->post('descuento_distribuidor'),
						'meses_sin_intereses'=>$this->input->post('meses_sin_intereses'),
						//'numero_folios'=>$this->input->post('numero_folios'),
				);
				
				$cupon_id=$this->base->guarda('cupones', $data);
				//$this->db->delete('cupones_folios', array('cupones_id' => $cupon_id));
				$this->db->delete('cupones_accesorios', array('cupones_id' => $cupon_id));
				$this->db->delete('cupones_categorias', array('cupones_id' => $cupon_id));
				$this->db->delete('cupones_productos', array('cupones_id' => $cupon_id));
				$this->db->delete('cupones_cuentas', array('cupones_id' => $cupon_id));

				//$this->Cupon->crear_folios_cupon($cupon_id, $_POST['alianza_id'], $_POST['numero_folios']);
				
				if(!empty($_POST['categorias_ids']))
				{
					foreach ($_POST['categorias_ids'] as $id)
					{
						$data=array(
								'cupones_id'=>$cupon_id,
								'productos_categorias_id'=>$id,
						);
						$this->base->guarda('cupones_categorias',$data);
					}
				}
	
				if(!empty($_POST['productos_ids']))
				{
					foreach ($_POST['productos_ids'] as $id)
					{
						$data=array(
								'cupones_id'=>$cupon_id,
								'productos_id'=>$id,
						);
						$this->base->guarda('cupones_productos',$data);
					}
				}
	
				if(!empty($_POST['accesorios_ids']))
				{
					foreach ($_POST['accesorios_ids'] as $id)
					{
						$data=array(
								'cupones_id'=>$cupon_id,
								'accesorios_id'=>$id,
								'consumible'=>0,
						);
						$this->base->guarda('cupones_accesorios',$data);
					}
				}
	
				if(!empty($_POST['consumibles_ids']))
				{
					foreach ($_POST['consumibles_ids'] as $id)
					{
						$data=array(
								'cupones_id'=>$cupon_id,
								'accesorios_id'=>$id,
								'consumible'=>1,
						);
						$this->base->guarda('cupones_accesorios',$data);
					}
				}

				if(!empty($_POST['cuentas_ids']))
				{
					foreach ($_POST['cuentas_ids'] as $id)
					{
						$data=array(
							'cupones_id'=>$cupon_id,
							'cuentas_id'=>$id,
							//'consumible'=>0,
						);
						$this->base->guarda('cupones_cuentas',$data);
					}
				}

				if(!empty($_POST['montosIniciales']) && !empty($_POST['montosFinales']))
				{
					$cupones_imagenes = $this->combinarArreglos($_POST['montosIniciales'],$_POST['montosFinales'],@$_POST['idsImagenes'],@$_POST['extensionesImagenes'],@$_POST['idsProductos'],$cupon_id);
					$ids_originales = $this->Cupon->get_imagenes($cupon_id,true);
					$ids_finales[] = null;
					unset($ids_finales[0]);

					foreach ($cupones_imagenes as $k=>$v)
					{
						if(empty($_FILES['imagenes']['tmp_name'][$k]))
						{
							$data = array(
								'id' => $v->id,
								'cupones_id' => $cupon_id,
								'monto_inicial' => $v->monto_inicial,
								'monto_final' => $v->monto_final,
								'productos_id' => $v->productos_id,
							);
							$this->base->guarda('cupones_imagenes', $data);
							$ids_finales[] = $v->id;
						}else
						{
							if(!empty($v->id))
								$this->Cupon->cupones_imagenes_eliminar($v->id);

							$data = array(
								'cupones_id' => $cupon_id,
								'monto_inicial' => $v->monto_inicial,
								'monto_final' => $v->monto_final,
								'productos_id' => $v->productos_id,
							);
							$id = $this->base->guarda('cupones_imagenes', $data);
							$ids_finales[] = $id;
							$this->Cupon->cupones_imagen_agregar($cupon_id,$_FILES['imagenes']['tmp_name'][$k],$_FILES['imagenes']['name'][$k],$id);
						}
					}

					$arreglo = array_diff($ids_originales,$ids_finales);

					if(!empty($arreglo))
						$this->Cupon->cupones_imagenes_eliminar($arreglo);

				}else
					$this->Cupon->cupones_imagenes_eliminar_todas($cupon_id);
	
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('cupones/index');
			}
			
			$datos['cupon'] = new stdClass();
			$datos['cupon']->alianza_id=@$_POST['alianza_id'];
			$datos['cupon']->vigencia_desde=@$_POST['vigencia_desde'];
			$datos['cupon']->vigencia_hasta=@$_POST['vigencia_hasta'];
			$datos['cupon']->porcentaje_descuento=@$_POST['porcentaje_descuento'];
			$datos['cupon']->descuento_distribuidor=@$_POST['descuento_distribuidor'];
			$datos['cupon']->meses_sin_intereses=@$_POST['meses_sin_intereses'];
			$datos['cupon']->numero_folios=@$_POST['numero_folios'];
			$datos['cupon']->tipos_id=@$_POST['tipos_id'];
			$datos['categorias_cupon']=@$_POST['categorias_ids'];
			$datos['productos_cupon']=@$_POST['productos_ids'];
			$datos['accesorios_cupon']=@$_POST['accesorios_ids'];
			$datos['consumibles_cupon']=@$_POST['consumibles_ids'];
			$datos['cuentas_cupon']=@$_POST['cuentas_ids'];
			$datos['cupones_imagenes']=$this->combinarArreglos($_POST['montosIniciales'],$_POST['montosFinales'],@$_POST['idsImagenes'],@$_POST['extensionesImagenes'],@$_POST['idsProductos'],$cupon_id);
		}else
		{
			$datos['cupon']=$this->base->read('cupones',$cupon_id);
			$datos['accesorios_cupon']=$this->Cupon->get_accesorios($cupon_id);
			$datos['consumibles_cupon']=$this->Cupon->get_accesorios($cupon_id, 1);
			$datos['categorias_cupon']=$this->Cupon->get_categorias($cupon_id);
			$datos['productos_cupon']=$this->Cupon->get_productos($cupon_id);
			$datos['cuentas_cupon']=$this->Cupon->get_cuentas($cupon_id);
			$datos['cupones_imagenes']=$this->Cupon->get_imagenes($cupon_id);
		}

		$datos['cuentas']=$this->base->lista('cuentas','id','nombre','ASC');
		$datos['alianzas']=catalogo('alianzas',FALSE);
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre','ASC');
		$datos['productos']=$this->base->lista('productos','id','modelo',TRUE,NULL,'ASC',array('ocultar'=>0));
		$datos['productosRegalo']=$this->base->lista('productos','id','nombre',TRUE,NULL,'ASC',array('ocultar'=>'1'));
		$datos['accesorios']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'0'));
		$datos['consumibles']=$this->base->lista('accesorios','id','modelo',TRUE,NULL,'ASC',array('consumible'=>'1'));
	
		$datos['titulo']='Editar cupón';
		$this->load->view('cupones/editar',$datos);
	}
	
	public function exportar($cupon_id)
	{
		$this->base->verifica('cupones_exportar');
		$this->Cupon->exportar($cupon_id);
	}

	/**
	 * @name cupones_imagenes_filas_validacion
	 * @description agrega validaciones para los montos iniciales y finales
	 * @return bool
	 */
	function cupones_imagenes_filas_validacion()
	{
		foreach($_POST['montosIniciales'] as $clave=>$valor)
		{
			$this->form_validation->set_rules("montosIniciales[{$clave}]", 'Monto Inicial', "numeric|required|callback_menor_o_igual_que[{$_POST['montosIniciales'][$clave]},{$_POST['montosFinales'][$clave]}]");
			$this->form_validation->set_rules("montosFinales[{$clave}]", 'Monto Final', "numeric|required|callback_mayor_o_igual_que[{$_POST['montosFinales'][$clave]},{$_POST['montosIniciales'][$clave]}]");

			if(empty($_FILES['imagenes']['tmp_name'][$clave]) && empty($_POST['idsImagenes'][$clave]))
				$this->form_validation->set_rules("imagenes[{$clave}]", 'Imagen', "required");

			$this->form_validation->set_rules("idsProductos[{$clave}]", 'Producto Regalo', 'integer');
		}
	}

	/**
	 * @name cupones_montos_validacion
	 * @description valida que exista al menos una fila en imagenes cupones
	 * @return bool
	 */
	function cupones_montos_validacion()
	{
		if(empty($_POST['montosIniciales']) || empty($_POST['montosFinales']))
		{
			$this->form_validation->set_message('cupones_montos_validacion', 'Debe agregar al menos una fila.');
			return false;
		}
		return true;
	}

	/**
	 * @name cupones_descuentos_validacion
	 * @description valida que el usuario seleccione al menos una categoria, producto, accesorio o consumible al que se le asignará el descuento del cupòn
	 * @return bool
	 */
	function cupones_descuentos_validacion()
	{
		if(empty($_POST['categorias_ids']) && empty($_POST['productos_ids']) && empty($_POST['accesorios_ids']) && empty($_POST['consumibles_ids']))
		{
			$this->form_validation->set_message('cupones_descuentos_validacion', 'Debe seleccionar al menos una categor&iacute;a, producto, accesorio o consumible al que se le asignar&aacute; el descuento del cup&oacute;n.');
			return false;
		}
		return true;
	}

	/**
	 * @name combinarArreglos
	 * @description combina los arreglos relacionados con la tabla cupones_imagenes en uno sólo para su insercion en la base de datos
	 * @param $arreglo1
	 * @param $arreglo2
	 * @param $arreglo3
	 * @param $arreglo4
	 * @param $arreglo5
	 * @param $cupones_id
	 * @return array|bool
	 */
	function combinarArreglos($arreglo1,$arreglo2,$arreglo3,$arreglo4,$arreglo5,$cupones_id)
	{
		$datos['cupon'] = new stdClass();
		$datos['cupon']->alianza_id=@$_POST['alianza_id'];

		$arreglo = array();
		foreach($arreglo1 as $clave=>$valor)
		{
			$arreglo[$clave] = new stdClass();
			$arreglo[$clave]->monto_inicial = $arreglo1[$clave];
			$arreglo[$clave]->monto_final = $arreglo2[$clave];
			$arreglo[$clave]->productos_id = $arreglo5[$clave];
			$arreglo[$clave]->cupones_id = $cupones_id;
			$arreglo[$clave]->id = @$arreglo3[$clave];
			$arreglo[$clave]->extension = @$arreglo4[$clave];
			$arreglo[$clave]->eliminado = 0;
		}

		return $arreglo;
	}

	public function menor_o_igual_que($campo, $parametros)
	{
		list($valor, $maximo) = explode(",", $parametros, 2);

		if($valor>=$maximo)
		{
			$this->form_validation->set_message('menor_o_igual_que', 'El monto inicial debe ser menor que el monto final.');
			return false;
		}
		return true;
	}

	public function mayor_o_igual_que($campo, $parametros)
	{
		list($valor, $maximo) = explode(",", $parametros, 2);

		if($valor<=$maximo)
		{
			$this->form_validation->set_message('mayor_o_igual_que', 'El monto final debe ser mayor que el monto incial.');
			return false;
		}
		return true;
	}

}