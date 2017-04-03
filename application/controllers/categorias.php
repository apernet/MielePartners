<?php
require_once('main.php');
class Categorias extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('categorias_productos');
		$this->load->model('Categoria');
	}
	
	public function index()
	{
		$this->base->verifica('categorias_productos');
		$b=bc_buscador();
		$datos['r']=$this->Categoria->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Categoria->count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();		
		$datos['titulo']='Categor&iacute;as';
		$datos['editar']=$this->base->tiene_permiso('categorias_editar');
		$datos['activar']=$this->base->tiene_permiso('categorias_activar');
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre',FALSE);
		$this->load->view('categorias/index',$datos);
	}

	public function agregar()
	{
		$this->base->verifica('categorias_agregar');
		if(!empty($_POST))
		{
			$reglas = $this->form_validation->_config_rules['categorias'];

			$reglas[]=array(
				'field' => 'cargo_recuperacion',
				'label' => 'Cargo Por Recuperación',
				'rules' => 'trim|numeric|max_length[6]|callback_menor_o_igual_que['.$_POST['cargo_recuperacion'].',100,Cargo Por Recuperación]'
			);

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'parent_id'=>$this->input->post('parent'),
				    'clave'=>$this->input->post('clave'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'descuento_base'=>$this->input->post('descuento_base'),
					'descuento_exhibicion'=>$this->input->post('descuento_exhibicion'),
					'descuento_opcional'=>$this->input->post('descuento_opcional'),
					'comision_vendedor'=>$this->input->post('comision_vendedor'),
                    'informacion_general'=>$this->input->post('informacion_general'),
					'cargo_recuperacion'=>$this->input->post('cargo_recuperacion'),
                    'activo'=>$this->input->post('activo'),
				);
				$categorias_id=$this->base->guarda('productos_categorias',$data);
				
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('productos_categorias',$categorias_id);
					$orden_data = array('id' => $r->id);
					$error='';

					// Carga de la imagen
					$orden_data['foto_id'] = 0;
					if(!empty($_FILES['imagen']['tmp_name']))
					{
						$extension = explode('.',$_FILES['imagen']['name']);
						$extension = $extension[1];
						if ($_FILES['imagen']['size'] > 0 && $_FILES['video']['size'] < $this->config->item('max_size') && ($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG')) {
							$r->imagen_orden++;
							$orden = $r->imagen_orden ? '_' . $r->imagen_orden : '';
							$foto_id = $this->base->guarda_imagen($_FILES['imagen']['tmp_name'], "files/categorias", $categorias_id . $orden, NULL, APPPATH);
							$orden_data['imagen_orden'] = $r->imagen_orden;
							$orden_data['foto_id'] = 1;
						} else
							$error .= "La imagen no se guardó debido a que no cuenta con extensión jpg ó su peso supera los 10MB.<br>";
					}

					// Carga del vídeo
					$orden_data['video_id'] = 0;
					if(!empty($_FILES['video']['tmp_name']))
					{
						$extension = explode('.', $_FILES['video']['name']);
						$extension = $extension[1];
						if ($_FILES['video']['size'] > 0 && $_FILES['video']['size'] < $this->config->item('max_size') && ($extension == 'mp4' || $extension == 'MP4')) {
							$r->video_orden++;
							$orden = $r->video_orden ? '_' . $r->video_orden : '';
							$video_id = $this->base->guarda_archivo($_FILES['video']['tmp_name'], "files/categorias", $categorias_id . $orden . '.' . $extension, NULL, APPPATH);
							$orden_data['video_orden'] = $r->video_orden;
							$orden_data['video_id'] = 1;
						} else
							$error .= "El vídeo no se guardó debido a que no cuenta con extensión mp4 ó su peso supera los 10MB.";
					}

					if(!empty($error))
						$this->session->set_flashdata('error', $error);
						
					$this->base->guarda('productos_categorias', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
					{
						$foto_id=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/categorias',$categorias_id,FALSE,FALSE);
						$video_id=$this->base->guarda_archivo($_FILES['video']['tmp_name'],'files/categorias',$categorias_id,FALSE,FALSE);
					}
				}
				
				$data['foto_id']=($foto_id)?1:0;
				$data['video_id']=($video_id)?1:0;
				$data['id']=$categorias_id;
				$categorias_id=$this->base->guarda('productos_categorias',$data);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('categorias/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		$datos['titulo']='Agregar categor&iacute;a';
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$this->load->view('categorias/agregar',$datos);
	}
	
	public function editar($categorias_id)
	{
		$this->base->verifica('categorias_editar');
		if(!empty($_POST))
		{

			$reglas = $this->form_validation->_config_rules['categorias'];

			$reglas[]=array(
				'field' => 'cargo_recuperacion',
				'label' => 'Cargo Por Recuperación',
				'rules' => 'trim|numeric|max_length[6]|callback_menor_o_igual_que['.$_POST['cargo_recuperacion'].',100,Cargo Por Recuperación]'
			);

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'id'=>$categorias_id,
					'parent_id'=>$this->input->post('parent'),
				    'clave'=>$this->input->post('clave'),
					'nombre'=>$this->input->post('nombre'),
					'descuento_base'=>$this->input->post('descuento_base'),
					'descuento_exhibicion'=>$this->input->post('descuento_exhibicion'),
					'descuento_opcional'=>$this->input->post('descuento_opcional'),
					'comision_vendedor'=>$this->input->post('comision_vendedor'),
					'descripcion'=>$this->input->post('descripcion'),
                    'informacion_general'=>$this->input->post('informacion_general'),
                    'cargo_recuperacion'=>$this->input->post('cargo_recuperacion'),
					'activo'=>$this->input->post('activo'),
				);
				
				$categorias_id=$this->base->guarda('productos_categorias',$data);

				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('productos_categorias',$categorias_id);
					$orden_data = array('id' => $r->id);
					$error='';

					// Carga de la imagen
					$orden_data['foto_id'] = 0;
					if(!empty($_FILES['imagen']['tmp_name']))
					{
						$extension = explode('.',$_FILES['imagen']['name']);
						$extension = $extension[1];
						if ($_FILES['imagen']['size'] > 0 && $_FILES['video']['size'] < $this->config->item('max_size') && ($extension == 'jpg' || $extension == 'JPG' || $extension == 'jpeg' || $extension == 'JPEG')) {
							$r->imagen_orden++;
							$orden = $r->imagen_orden ? '_' . $r->imagen_orden : '';
							$foto_id = $this->base->guarda_imagen($_FILES['imagen']['tmp_name'], "files/categorias", $categorias_id . $orden, NULL, APPPATH);
							$orden_data['imagen_orden'] = $r->imagen_orden;
							$orden_data['foto_id'] = 1;
						} else
							$error .= "La imagen no se guardó debido a que no cuenta con extensión jpg ó su peso supera los 10MB.<br>";
					}

					// Carga del vídeo
					$orden_data['video_id'] = 0;
					if(!empty($_FILES['video']['tmp_name']))
					{
						$extension = explode('.', $_FILES['video']['name']);
						$extension = $extension[1];
						if ($_FILES['video']['size'] > 0 && $_FILES['video']['size'] < $this->config->item('max_size') && ($extension == 'mp4' || $extension == 'MP4')) {
							$r->video_orden++;
							$orden = $r->video_orden ? '_' . $r->video_orden : '';
							$video_id = $this->base->guarda_archivo($_FILES['video']['tmp_name'], "files/categorias", $categorias_id . $orden, NULL, APPPATH);
							$orden_data['video_orden'] = $r->video_orden;
							$orden_data['video_id'] = 1;
						} else
							$error .= "El vídeo no se guardó debido a que no cuenta con extensión mp4 ó su peso supera los 10MB.";
					}

					if(!empty($error))
						$this->session->set_flashdata('error', $error);
						
					$this->base->guarda('productos_categorias', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
					{
						$foto_id=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/categorias',$categorias_id,FALSE,FALSE);
						$video_id=$this->base->guarda_archivo($_FILES['video']['tmp_name'],'files/categorias',$categorias_id,FALSE,FALSE);
					}
				}
				$data['foto_id']=($foto_id)?1:0;
				$data['video_id']=($video_id)?1:0;
				
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('categorias/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
    	$datos['r']=$this->base->read('productos_categorias',$categorias_id);

		$orden = $datos['r']->video_orden?'_'.$datos['r']->video_orden:'';
        $path = (!empty($orden))?
                    ((isset($this->cloud_files)) ? 
                        $this->cloud_files->url_publica("files/categorias/{$datos['r']->id}{$orden}") : site_url("files/categorias/{$datos['r']->id}{$orden}"))
               : "";
        $path_play = site_url('img/categorias/play_icon.png');
		$path_play_hover = site_url('img/categorias/play_icon_over.png');

		if(!empty($path))
		{
			$video=TRUE;
			$datos['path_video'] = $path;
			$datos['path_play'] = $path_play;
			$datos['path_play_hover'] = $path_play_hover;
		}else
			$video=FALSE;

		$datos['video']=$video;
		$datos['categorias_id']=$categorias_id;
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['titulo']='Editar categor&iacute;a';

		$this->load->view('categorias/editar',$datos);
	}

	public function eliminar($categorias_id)
    {
    	$this->base->verifica('categorias_eliminar');
    	
    	$data['id']=$categorias_id;
    	$data['eliminado']=1;
    	$this->base->guarda('productos_categorias',$data);
    	bc_log(1,'Elimino destacado id: '.$categorias_id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('categorias/index');
    }
    
	public function activar($categorias_id,$activo)
    {
    	$this->base->verifica('categorias_activar');
    	$data['id']=$categorias_id;
    	$data['activo']=!$activo;
    	$this->base->guarda('productos_categorias',$data);
    	$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('categorias/index');
    }
    
    public function fotos_eliminar($categorias_id)
    {
    	$r=$this->Categoria->fotos_eliminar($categorias_id);
    	$this->load->library('user_agent');
    	$this->session->set_flashdata('done', 'La fotograf&iacute;a ha sido eliminada correctamente.');
    	redirect($this->agent->referrer());
    }

	public function reproductor($categorias_id)
	{
		$video_orden = $this->base->get_dato('video_orden','productos_categorias',array('id'=>$categorias_id));

		$orden = (!empty($video_orden))?'_'.$video_orden:'';
		$enlace = (!empty($orden))?$this->cloud_files->url_publica("files/categorias/{$categorias_id}{$orden}"):'';

		$datos['enlace']=$enlace;

		$this->load->view('categorias/reproductor',$datos);
	}

	public function menor_o_igual_que($campo, $parametros)
	{
		if(empty($parametros))
			return true;

		$datos = explode(",", $parametros);

		$valor = $datos[0];
		$maximo = $datos[1];
		$campo = $datos[2];

		if($valor>$maximo)
		{
			$this->form_validation->set_message('menor_o_igual_que', 'El campo '.$campo.' debe ser menor o igual a '.$maximo.'.');
			return false;
		}
		return true;
	}

}