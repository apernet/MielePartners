<?php
require_once('main.php');
class Productos extends Main {

var $estados =  array(
	'Aguascalientes',
	'Baja California',
	'Baja California Sur',
	'Campeche',
	'Chiapas',
	'Chihuahua',
	'Coahuila De Zaragoza',
	'Colima',
	'Distrito Federal',
	'Durango',
	'Guanajuato',
	'Guerrero',
	'Hidalgo',
	'Jalisco',
	'Mexico',
	'Michoacan De Ocampo',
	'Morelos',
	'Nayarit',
	'Nuevo Leon',
	'Oaxaca',
	'Puebla',
	'Queretaro',
	'Quintana Roo',
	'San Luis Potosi',
	'Sinaloa',
	'Sonora',
	'Tabasco',
	'Tamaulipas',
	'Tlaxcala',
	'Veracruz De Ignacio De La Llave',
	'Yucatan',
	'Zacatecas'
	);

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('productos');
		$this->load->model('Producto');
	}
	
	public function index()
	{
		$this->base->verifica('productos');
		$b=bc_buscador();	
		$datos['r']=$this->Producto->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Producto->count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	
		$datos['categorias'] = $this->base->lista('productos_categorias','id','nombre',FALSE,'nombre','ASC');
		$datos['titulo']='Productos';
		$this->load->view('productos/index',$datos);
	}

	public function agregar()
	{
		$this->base->verifica('productos_agregar');
		if(!empty($_POST))
		{
			$reglas = $this->form_validation->_config_rules['productos'];

			if(@$this->input->post('unidad_id')==2)
			{
				$reglas[]=array(
					'field' => 'filas_estados',
					'label' => 'filas_estados',
					'rules' => 'callback_no_vacio'
				);
				$this->validar_filas_estados();
			}

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'categorias_id'=>$this->input->post('categorias_id'),
					'modelo'=>$this->input->post('modelo'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'costo'=>$this->input->post('costo'),
					'precio'=>$this->input->post('precio'),
					'unidad_id'=>$this->input->post('unidad_id'),
					'tipo_moneda_id'=>$this->input->post('tipo_moneda_id'),
					'item'=>$this->input->post('item'),
					'activo'=>$this->input->post('activo'),
					'sin_envio'=>$this->input->post('sin_envio'),
					'ocultar'=>$this->input->post('ocultar'),
				);

				$productos_id=$this->base->guarda($this->Producto->table,$data);

				// GUARDA HOME CARE PROGRAM
				if(@$this->input->post('unidad_id')==2)
				{
					$estados = $this->estados;
					$datos = array();
					$datos['productos_id'] = $productos_id;

					foreach($estados as $k=>$v)
					{
						$postfijo = strtolower(str_replace(' ','_',$v));
						$datos['horas_iniciales_'.$postfijo] = $this->input->post('horas_iniciales_'.$postfijo);
						$datos['horas_maximas_'.$postfijo] = $this->input->post('horas_maximas_'.$postfijo);
						$datos['precio_inicial_'.$postfijo] = $this->input->post('precio_inicial_'.$postfijo);
						$datos['precio_horas_extra_'.$postfijo] = $this->input->post('precio_horas_extra_'.$postfijo);
					}
					$this->base->guarda('gastos_cursos',$datos);
				}

				//GUARDA TIPOS DE ACCESORIOS
				if(isset($_POST['accesorios']['tipo_accesorio_id']))
				{
					foreach($_POST['accesorios']['tipo_accesorio_id'] as $k=>$v)
					{
						if(!empty($_POST['accesorios']['tipo_accesorio_id'][$k]))
						{
							$data=array();
							$data['tipos_accesorios_id']=$v;
							$data['productos_id']=$productos_id;
							$data['obligatorio_id']=isset($_POST['accesorios']['obligatorio_id'][$k]) ? $_POST['accesorios']['obligatorio_id'][$k] : 0;
							$id=$this->base->guarda('productos_tipos_accesorios',$data);
						}
					}
				}
	
				// GUÍAS MECÁNICAS
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('productos',$productos_id);
					$orden_data = array('id' => $r->id);
					if($_FILES['guia_mecanica']['size']>0)
					{
						$r->guia_mecanica_orden++;
						$orden=$r->guia_mecanica_orden?'_'.$r->guia_mecanica_orden:'';
						$extension = explode('.',$_FILES['guia_mecanica']['name']);
						$extension = $extension[1];
				
						if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
							$foto_id=$this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}","guia_mecanica{$orden}",NULL,APPPATH);
						elseif($extension=='pdf' || $extension=='PDF')
							$archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}","guia_mecanica{$orden}.{$extension}",NULL,APPPATH);
						
						$orden_data['guia_mecanica_orden'] = $r->guia_mecanica_orden;
						$orden_data['guia_mecanica_extension'] = $extension;
						$this->base->guarda('productos', $orden_data);
					}
				}
				else
				{
                    if($_FILES['guia_mecanica']['size']>0)
					{
                        $orden_data = array('id' => $productos_id);
						$extension = explode('.',$_FILES['guia_mecanica']['name']);
						$extension = $extension[1];
						
						if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
							$foto_id = $this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}",'guia_mecanica',FALSE,FALSE);
						elseif($extension=='pdf' || $extension=='PDF')
							$archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}","guia_mecanica.{$extension}",FALSE,FALSE);
						
						$orden_data['guia_mecanica_extension'] = $extension;
						$this->base->guarda('productos', $orden_data);
					}
				}
				
                // MANUALES
                if($this->config->item('cloudfiles'))
                {
                    $r = $this->base->read('productos',$productos_id);
                    $orden_data = array('id' => $r->id);
                    if($_FILES['manual']['size']>0)
                    {
                        $r->manual_orden++;
                        $orden=$r->manual_orden?'_'.$r->manual_orden:'';
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];

                        if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
                        	$foto_id=$this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}","manual{$orden}",NULL,APPPATH);
                        elseif($extension=='pdf' || $extension=='PDF')
                        	$archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}","manual{$orden}.{$extension}",NULL,APPPATH);
                        
                        $orden_data['manual_orden'] = $r->manual_orden;
                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('productos', $orden_data);
                    }
                }
                else
                {
                    if($_FILES['manual']['size']>0)
                    {
                        $orden_data = array('id' => $productos_id);
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];
                        
                        if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
                        	$foto_id = $this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}",'manual',FALSE,FALSE);
                        elseif($extension=='pdf' || $extension=='PDF')
                        	$archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}","manual.{$extension}",FALSE,FALSE);
                        
                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('productos', $orden_data);
                    }
                }

                // AUTOCAD
                if($this->config->item('cloudfiles'))
                {
                	$r = $this->base->read('productos',$productos_id);
                	$orden_data = array('id' => $r->id);
                	if($_FILES['autocad']['size']>0)
                	{
                		$r->autocad_orden++;
                		$orden=$r->autocad_orden?'_'.$r->autocad_orden:'';
                		$extension = explode('.',$_FILES['autocad']['name']);
                		$extension = $extension[1];
                
                		if($extension=='zip' || $extension=='ZIP')
                			$archivo_id=$this->base->guarda_archivo($_FILES['autocad']['tmp_name'],"files/productos/{$productos_id}","autocad{$orden}.{$extension}",NULL,APPPATH);
                		
                		$orden_data['autocad_orden'] = $r->autocad_orden;
                		$orden_data['autocad_extension'] = $extension;
                		$this->base->guarda('productos', $orden_data);
                	}
                }
                else
                {
                	if($_FILES['autocad']['size']>0)
                	{
                		$orden_data = array('id' => $productos_id);
                		$extension = explode('.',$_FILES['autocad']['name']);
                		$extension = $extension[1];
                		
                		if($extension=='zip' || $extension=='ZIP')
                			$archivo_id=$this->base->guarda_archivo($_FILES['autocad']['tmp_name'],"files/productos/{$productos_id}","autocad.{$extension}",FALSE,FALSE);
                		
                		$orden_data['autocad_extension'] = $extension;
                		$this->base->guarda('productos', $orden_data);
                	}
                }
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('productos/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['estados']=$this->estados;
		$datos['unidades']=catalogo('unidades',FALSE);
		$datos['unidades']=$this->base->lista('elementos','clave','valor',TRUE,'id','ASC',array('catalogos_id'=>8));
		$datos['obligatorio_optativo']=catalogo('obligatorio_optativo',FALSE);
		$datos['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');	
		$datos['titulo']='Agregar producto';
		$this->load->view('productos/agregar',$datos);
	}
	
	public function editar($productos_id)
	{
		$this->base->verifica('productos_editar');
		$cursos = $this->base->get_datos('*','gastos_cursos',array('productos_id'=>$productos_id));

		if(!empty($_POST))
		{
			$reglas = $this->form_validation->_config_rules['productos'];

			if(@$this->input->post('unidad_id')==2)
			{
				$reglas[]=array(
					'field' => 'filas_estados',
					'label' => 'filas_estados',
					'rules' => 'callback_no_vacio'
				);
				$this->validar_filas_estados();
			}

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'categorias_id'=>$this->input->post('categorias_id'),
					'modelo'=>$this->input->post('modelo'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'costo'=>$this->input->post('costo'),
					'precio'=>$this->input->post('precio'),
					'unidad_id'=>$this->input->post('unidad_id'),
					'tipo_moneda_id'=>$this->input->post('tipo_moneda_id'),
					'item'=>$this->input->post('item'),
					'activo'=>$this->input->post('activo'),
					'sin_envio'=>$this->input->post('sin_envio'),
					'ocultar'=>$this->input->post('ocultar'),
				);

				$productos_id=$this->base->guarda($this->Producto->table,$data);

				if(!empty($cursos->id))
					$datos['id'] = $cursos->id;

				// GUARDA HOME CARE PROGRAM
				if(@$this->input->post('unidad_id')==2)
				{
					$estados = $this->estados;
					$datos = array();
					$datos['id'] = $cursos->id;

					foreach($estados as $k=>$v)
					{
						$postfijo = strtolower(str_replace(' ','_',$v));
						$datos['horas_iniciales_'.$postfijo] = $this->input->post('horas_iniciales_'.$postfijo);
						$datos['horas_maximas_'.$postfijo] = $this->input->post('horas_maximas_'.$postfijo);
						$datos['precio_inicial_'.$postfijo] = $this->input->post('precio_inicial_'.$postfijo);
						$datos['precio_horas_extra_'.$postfijo] = $this->input->post('precio_horas_extra_'.$postfijo);
					}

					$this->base->guarda('gastos_cursos',$datos);
				}

				//Elimina los accesorios actuales
				$this->Producto->productos_accesorios_eliminar($productos_id);
				if(isset($_POST['accesorios']['tipo_accesorio_id']))
				{		
					foreach($_POST['accesorios']['tipo_accesorio_id'] as $k=>$v)
					{
						if(!empty($_POST['accesorios']['tipo_accesorio_id'][$k]))
						{
							$data=array();
							$data['tipos_accesorios_id']=$v;
							$data['productos_id']=$productos_id;
							$data['obligatorio_id']=isset($_POST['accesorios']['obligatorio_id'][$k]) ? $_POST['accesorios']['obligatorio_id'][$k] : 0;
							$id=$this->base->guarda('productos_tipos_accesorios',$data);
						}
					}
				}
				
				// ACOMODA Y GUARDA FOTOS
				$i=0;	
				if($_POST['foto_id'])
				{		
					foreach($_POST['foto_id'] as $k=>$v)
					{
						$data=array();
						$data['id']=$_POST['foto_id'][$k];
						$data['orden']=$i;
						$data['tipos_id']=$_POST['foto_tipo_id'][$k];
						$data['descripcion']=$_POST['foto_descripcion'][$k];
						$data['productos_id']=$productos_id;
			
						if(!empty($_POST['foto_id']))
							$id=$this->base->guarda('fotografias_productos',bc_null_empty($data));
						$i++;
					}
				}

				// GUÍAS MECÁNICAS
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('productos',$productos_id);
					$orden_data = array('id' => $r->id);
					if($_FILES['guia_mecanica']['size']>0)
					{
						$r->guia_mecanica_orden++;
						$orden=$r->guia_mecanica_orden?'_'.$r->guia_mecanica_orden:'';
						$extension = explode('.',$_FILES['guia_mecanica']['name']);
						$extension = $extension[1];
						
						if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
							$foto_id=$this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}","guia_mecanica{$orden}",NULL,APPPATH);
						elseif($extension=='pdf' || $extension=='PDF')
							$archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}","guia_mecanica{$orden}.{$extension}",NULL,APPPATH);
					
						$orden_data['guia_mecanica_orden'] = $r->guia_mecanica_orden;
						$orden_data['guia_mecanica_extension'] = $extension;
						$this->base->guarda('productos', $orden_data);
					}
				}
				else
				{
                    if($_FILES['guia_mecanica']['size']>0)
					{
                        $orden_data = array('id' => $productos_id);
						$extension = explode('.',$_FILES['guia_mecanica']['name']);
						$extension = $extension[1];
						
                        unlink(FCPATH."files/productos/{$productos_id}/guia_mecanica.jpg");
                        unlink(FCPATH."files/productos/{$productos_id}/guia_mecanica.JPG");
                        unlink(FCPATH."files/productos/{$productos_id}/guia_mecanica.jpeg");
                        unlink(FCPATH."files/productos/{$productos_id}/guia_mecanica.JPEG");
                        unlink(FCPATH."files/productos/{$productos_id}/guia_mecanica.pdf");
                        unlink(FCPATH."files/productos/{$productos_id}/guia_mecanica.PDF");
                        
						if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
							$foto_id = $this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}",'guia_mecanica',FALSE,FALSE);
						elseif($extension=='pdf' || $extension=='PDF')
							$archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/productos/{$productos_id}","guia_mecanica.{$extension}",FALSE,FALSE);
						
						$orden_data['guia_mecanica_extension'] = $extension;
						$this->base->guarda('productos', $orden_data);
					}
				}

                // MANUALES
                if($this->config->item('cloudfiles'))
                {
                    $r = $this->base->read('productos',$productos_id);
                    $orden_data = array('id' => $r->id);
                    if($_FILES['manual']['size']>0)
                    {
                        $r->manual_orden++;
                        $orden=$r->manual_orden?'_'.$r->manual_orden:'';
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];

                        if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
                            $foto_id=$this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}","manual{$orden}",NULL,APPPATH);
                        elseif($extension=='pdf' || $extension=='PDF')
                            $archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}","manual{$orden}.{$extension}",NULL,APPPATH);
                        
                        $orden_data['manual_orden'] = $r->manual_orden;
                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('productos', $orden_data);
                    }
                }
                else
                {
                    if($_FILES['manual']['size']>0)
                    {
                        $orden_data = array('id' => $productos_id);
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];
                        
                        unlink(FCPATH."files/productos/{$productos_id}/manual.jpg");
                        unlink(FCPATH."files/productos/{$productos_id}/manual.JPG");
                        unlink(FCPATH."files/productos/{$productos_id}/manual.jpeg");
                        unlink(FCPATH."files/productos/{$productos_id}/manual.JPEG");
                        unlink(FCPATH."files/productos/{$productos_id}/manual.pdf");
                        unlink(FCPATH."files/productos/{$productos_id}/manual.PDF");
                        
                        if($extension=='jpg' || $extension=='JPG' || $extension=='jpeg' || $extension=='JPEG')
                            $foto_id = $this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}",'manual',FALSE,FALSE);
                        elseif($extension=='pdf' || $extension=='PDF')
                            $archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/productos/{$productos_id}","manual.{$extension}",FALSE,FALSE);
                        
                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('productos', $orden_data);
                    }
                }
                
                // AUTOCAD
                if($this->config->item('cloudfiles'))
                {
                	$r = $this->base->read('productos',$productos_id);
                	$orden_data = array('id' => $r->id);
                	if($_FILES['autocad']['size']>0)
                	{
                		$r->autocad_orden++;
                		$orden=$r->autocad_orden?'_'.$r->autocad_orden:'';
                		$extension = explode('.',$_FILES['autocad']['name']);
                		$extension = $extension[1];
                
                		if($extension=='zip' || $extension=='ZIP')
                			$archivo_id=$this->base->guarda_archivo($_FILES['autocad']['tmp_name'],"files/productos/{$productos_id}","autocad{$orden}.{$extension}",NULL,APPPATH);
                		
                		$orden_data['autocad_orden'] = $r->autocad_orden;
                		$orden_data['autocad_extension'] = $extension;
                		$this->base->guarda('productos', $orden_data);
                	}
                }
                else
                {
                	if($_FILES['autocad']['size']>0)
                	{
                		$orden_data = array('id' => $productos_id);
                		$extension = explode('.',$_FILES['autocad']['name']);
                		$extension = $extension[1];
                		
                		unlink(FCPATH."files/productos/{$productos_id}/autocad.zip");
                		unlink(FCPATH."files/productos/{$productos_id}/autocad.ZIP");
                		
                		if($extension=='zip' || $extension=='ZIP')
                			$archivo_id=$this->base->guarda_archivo($_FILES['autocad']['tmp_name'],"files/productos/{$productos_id}","autocad.{$extension}",FALSE,FALSE);
                			
                		$orden_data['autocad_extension'] = $extension;
                		$this->base->guarda('productos', $orden_data);
                	}
                }

				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('productos/index');
			}
			else
			{
				$offset = $this->uri->total_segments() == 3 ? 0 : $this->uri->segment($this->uri->total_segments());
				$datos['fotos']=$this->Producto->get_fotografias($productos_id,$this->config->item('por_pagina'),$offset);
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}	
		}
		else
		{
			$datos['r']=$this->base->read($this->Producto->table,$productos_id);
		}

		$datos['cursos'] = $cursos;
		$datos['unidades']=$this->base->lista('elementos','clave','valor',TRUE,'id','ASC',array('catalogos_id'=>8));
		$datos['accesorios']=$this->Producto->get_tipos_accesorios($productos_id);
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['estados']=$this->estados;
		$datos['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');
		$this->load->library('session'); 
		$datos['sesion'] = $this->input->cookie($this->session->sess_cookie_name);

//		$b=bc_buscador();
		$offset = $this->uri->total_segments() == 3 ? 0 : $this->uri->segment($this->uri->total_segments());
		$datos['fotos']=$this->Producto->get_fotografias($productos_id,$this->config->item('por_pagina'),$offset);	
		$datos['acciones_fotos']=$this->Producto->get_acciones_fotos();
		$datos['productos_id']=$productos_id;
		
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url('productos/editar/'.$productos_id);
		$paginador['total_rows'] = $datos['total'] = $this->Producto->count_fotografias($productos_id);
		$paginador['uri_segment'] = 4;
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['paginador'] = $this->pagination->create_links();

		$datos['titulo']='Editar producto';
		$this->load->view('productos/editar',$datos);
	}
	
	public function activar($productos_id,$activo)
    {
    	$this->base->verifica('productos_activar');
    	
    	$data['id']=$productos_id;
    	$data['activo']=!$activo;
    	$this->base->guarda($this->Producto->table,$data);
    	$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('productos/index');
    }

	public function eliminar($productos_id)
    {
    	$this->base->verifica('productos_eliminar');
    	$data['id']=$productos_id;
    	$data['eliminado']=1;
    	$this->base->guarda($this->Producto->table,$data);
    	bc_log(3,'Elimino producto id: '.$productos_id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('productos/index');
    }
    
	public function fotos_agregar($productos_id)
	{
		$nombre_original=$_FILES["Filedata"]["name"];
		$es_imagen=TRUE;
		$extension=explode(".",$_FILES["Filedata"]["name"]);
	
		if($extension['1']=='pdf')
			$es_imagen=FALSE;
			
		$id=$this->Producto->agregar_foto($productos_id,$_FILES["Filedata"]["tmp_name"],$es_imagen);
		$data=array();
		$data['id']=$id;
		$data['nombre_original']=$nombre_original;
		$id=$this->base->guarda('fotografias_productos',$data);
		
		
		if(!$id)
			$this->output->set_output("La fotografía no fue almacenada, intente nuevamente.");
		else	
			$this->output->set_output("FILEID:".$id);
	}
	
	public function fotos_eliminar($id)
	{
		$r=$this->Producto->fotos_eliminar($id);
		$this->load->library('user_agent');
		$this->session->set_flashdata('done', 'La fotograf&iacute;a ha sido eliminada correctamente.');
		redirect($this->agent->referrer());
	}


	public function fotos_eliminar_varios($ids)
	{
		$ids=explode('_',$ids);
		foreach($ids as $id)
		{	
			$this->Producto->fotos_eliminar($id);
		}	
		$msg='La imagen se ha eliminado correctamente.';
		if(count($ids)>1)
    		$msg='Las imagenes se han eliminado correctamente.';	
    	$this->session->set_flashdata('done', $msg);	
		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}
	
	public function productos_exportar()
	{
		$this->base->verifica('productos_exportar');
		$b=bc_buscador();
		$productos=$this->Producto->find($b['cond'],NULL,NULL);
		
		$this->Producto->exportar($productos);
	}

	public function validar_filas_estados()
	{
		$estados = $this->estados;

		foreach($estados as $k=>$v)
		{
			$postfijo = strtolower(str_replace(' ','_',$v));

			if(!empty($_POST['precio_inicial_'.$postfijo]) || !empty($_POST['horas_iniciales_'.$postfijo]) || !empty($_POST['horas_maximas_'.$postfijo]) || !empty($_POST['precio_horas_extra_'.$postfijo]))
			{
				$this->form_validation->set_rules('precio_inicial_'.$postfijo, 'precio de '.$v, 'numeric|required');
				$this->form_validation->set_rules('horas_iniciales_'.$postfijo, 'horas iniciales de '.$v, 'numeric|integer|required');
				$this->form_validation->set_rules('horas_maximas_'.$postfijo, 'horas maximas de '.$v, 'numeric|integer|required');
				$this->form_validation->set_rules('precio_horas_extra_'.$postfijo, 'horas extras de '.$v, 'numeric|numeric|required');
			}
		}
	}

	public function no_vacio()
	{
		$estados = $this->estados;


		$i=0;
		foreach($estados as $k=>$v)
		{
			$postfijo = strtolower(str_replace(' ','_',$v));

			if(empty($_POST['precio_inicial_'.$postfijo]) && empty($_POST['horas_iniciales_'.$postfijo]) && empty($_POST['horas_maximas_'.$postfijo]) && empty($_POST['precio_horas_extra_'.$postfijo]))
				$i++;
		}

		if($i==32)
		{
			$this->form_validation->set_message('no_vacio', 'Debe capturar al menos la fila de un estado.');
			return false;
		}

		return true;
	}

}