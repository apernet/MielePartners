<?php
require_once('main.php');
class Accesorios extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('accesorios');
		$this->load->model('Accesorio');
	}
	
	public function index()
	{
		$this->base->verifica('accesorios');
		$b=bc_buscador();	
		$datos['r']=$this->Accesorio->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Accesorio->count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	
		$datos['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre',FALSE);
		$datos['editar']=$this->base->tiene_permiso('accesorios_editar');
		$datos['activar']=$this->base->tiene_permiso('accesorios_activar');
		$datos['eliminar']=$this->base->tiene_permiso('accesorios_eliminar');
		$datos['titulo']='Accesorios';
		$this->load->view('accesorios/index',$datos);
	}

	public function agregar()
	{
		$this->base->verifica('accesorios_agregar');
		if(!empty($_POST))
		{
			if($this->form_validation->run('accesorios'))
			{
				$data=array(
					'tipos_accesorios_id'=>$this->input->post('tipos_accesorios_id'),
					'modelo'=>$this->input->post('modelo'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'precio'=>$this->input->post('precio'),
					'item'=>$this->input->post('item'),
					'consumible'=>$this->input->post('consumible'),
					'activo'=>$this->input->post('activo'),
				);
			
				$accesorios_id=$this->base->guarda('accesorios',$data);
				
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('accesorios',$accesorios_id);
					$orden_data = array('id' => $r->id);
						
					if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$orden=$r->imagen_orden?'_'.$r->imagen_orden:'';
						$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/accesorios",$accesorios_id.$orden,NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
						
					$this->base->guarda('accesorios', $orden_data);
				}
				else
				{
					if(!empty($_FILES))	
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/accesorios',$accesorios_id,$this->config->item('avaluo_image_size'));
				}

                // GUÍAS MECÁNICAS
                if($this->config->item('cloudfiles'))
                {
                    $r = $this->base->read('accesorios',$accesorios_id);
                    $orden_data = array('id' => $r->id);
                    if($_FILES['guia_mecanica']['size']>0)
                    {
                        $r->guia_mecanica_orden++;
                        $orden=$r->guia_mecanica_orden?'_'.$r->guia_mecanica_orden:'';
                        $extension = explode('.',$_FILES['guia_mecanica']['name']);
                        $extension = $extension[1];

                        if($extension=='jpg')
                            $foto_id=$this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}","guia_mecanica{$orden}",NULL,APPPATH);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}","guia_mecanica{$orden}.{$extension}",NULL,APPPATH);

                        $orden_data['guia_mecanica_orden'] = $r->guia_mecanica_orden;
                        $orden_data['guia_mecanica_extension'] = $extension;
                        $this->base->guarda('accesorios', $orden_data);
                    }
                }
                else
                {
                    if($_FILES['guia_mecanica']['size']>0)
                    {
                        $orden_data = array('id' => $accesorios_id);
                        $extension = explode('.',$_FILES['guia_mecanica']['name']);
                        $extension = $extension[1];
                        if($extension=='jpg')
                            $foto_id = $this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}",'guia_mecanica',FALSE,FALSE);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}","guia_mecanica.{$extension}",FALSE,FALSE);

                        $orden_data['guia_mecanica_extension'] = $extension;
                        $this->base->guarda('accesorios', $orden_data);
                    }
                }

                // MANUALES
                if($this->config->item('cloudfiles'))
                {
                    $r = $this->base->read('accesorios',$accesorios_id);
                    $orden_data = array('id' => $r->id);
                    if($_FILES['manual']['size']>0)
                    {
                        $r->manual_orden++;
                        $orden=$r->manual_orden?'_'.$r->manual_orden:'';
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];

                        if($extension=='jpg')
                            $foto_id=$this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}","manual{$orden}",NULL,APPPATH);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}","manual{$orden}.{$extension}",NULL,APPPATH);

                        $orden_data['manual_orden'] = $r->manual_orden;
                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('accesorios', $orden_data);
                    }
                }
                else
                {
                    if($_FILES['manual']['size']>0)
                    {
                        $orden_data = array('id' => $accesorios_id);
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];
                        if($extension=='jpg')
                            $foto_id = $this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}",'manual',FALSE,FALSE);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}","manual.{$extension}",FALSE,FALSE);

                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('accesorios', $orden_data);
                    }
                }
				
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('accesorios/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
	
		$datos['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');
		$datos['titulo']='Agregar accesorio';
		$this->load->view('accesorios/agregar',$datos);
	}
	
	public function editar($accesorios_id)
	{
		$this->base->verifica('accesorios_editar');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('accesorios'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'tipos_accesorios_id'=>$this->input->post('tipos_accesorios_id'),
					'modelo'=>$this->input->post('modelo'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'precio'=>$this->input->post('precio'),
					'item'=>$this->input->post('item'),
					'consumible'=>$this->input->post('consumible'),
					'activo'=>$this->input->post('activo'),
				);
				$accesorios_id=$this->base->guarda('accesorios',$data);

				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('accesorios',$accesorios_id);
					$orden_data = array('id' => $r->id);
						
					if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$orden=$r->imagen_orden?'_'.$r->imagen_orden:'';
						$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/accesorios",$accesorios_id.$orden,NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
						
					$this->base->guarda('accesorios', $orden_data);
				}
				else
				{
					if(!empty($_FILES))	
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/accesorios',$accesorios_id,$this->config->item('avaluo_image_size'));
				}

                // GUÍAS MECÁNICAS
                if($this->config->item('cloudfiles'))
                {
                    $r = $this->base->read('accesorios',$accesorios_id);
                    $orden_data = array('id' => $r->id);
                    if($_FILES['guia_mecanica']['size']>0)
                    {
                        $r->guia_mecanica_orden++;
                        $orden=$r->guia_mecanica_orden?'_'.$r->guia_mecanica_orden:'';
                        $extension = explode('.',$_FILES['guia_mecanica']['name']);
                        $extension = $extension[1];

                        if($extension=='jpg')
                            $foto_id=$this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}","guia_mecanica{$orden}",NULL,APPPATH);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}","guia_mecanica{$orden}.{$extension}",NULL,APPPATH);

                        $orden_data['guia_mecanica_orden'] = $r->guia_mecanica_orden;
                        $orden_data['guia_mecanica_extension'] = $extension;
                    }

                    $this->base->guarda('accesorios', $orden_data);
                }
                else
                {
                    if($_FILES['guia_mecanica']['size']>0)
                    {
                        $orden_data = array('id' => $accesorios_id);
                        $extension = explode('.',$_FILES['guia_mecanica']['name']);
                        $extension = $extension[1];
                        unlink(FCPATH."files/accesorios/{$accesorios_id}/guia_mecanica.jpg");
                        unlink(FCPATH."files/accesorios/{$accesorios_id}/guia_mecanica.pdf");
                        if($extension=='jpg')
                            $foto_id = $this->base->guarda_imagen($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}",'guia_mecanica',FALSE,FALSE);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['guia_mecanica']['tmp_name'],"files/accesorios/{$accesorios_id}","guia_mecanica.{$extension}",FALSE,FALSE);

                        $orden_data['guia_mecanica_extension'] = $extension;
                        $this->base->guarda('accesorios', $orden_data);
                    }
                }

                // MANUALES
                if($this->config->item('cloudfiles'))
                {
                    $r = $this->base->read('accesorios',$accesorios_id);
                    $orden_data = array('id' => $r->id);
                    if($_FILES['manual']['size']>0)
                    {
                        $r->manual_orden++;
                        $orden=$r->manual_orden?'_'.$r->manual_orden:'';
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];

                        if($extension=='jpg')
                            $foto_id=$this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}","manual{$orden}",NULL,APPPATH);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}","manual{$orden}.{$extension}",NULL,APPPATH);

                        $orden_data['manual_orden'] = $r->manual_orden;
                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('accesorios', $orden_data);
                    }
                }
                else
                {
                    if($_FILES['manual']['size']>0)
                    {
                        $orden_data = array('id' => $accesorios_id);
                        $extension = explode('.',$_FILES['manual']['name']);
                        $extension = $extension[1];
                        unlink(FCPATH."files/accesorios/{$accesorios_id}/manual.jpg");
                        unlink(FCPATH."files/accesorios/{$accesorios_id}/manual.pdf");
                        if($extension=='jpg')
                            $foto_id = $this->base->guarda_imagen($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}",'manual',FALSE,FALSE);
                        else
                            $archivo_id=$this->base->guarda_archivo($_FILES['manual']['tmp_name'],"files/accesorios/{$accesorios_id}","manual.{$extension}",FALSE,FALSE);

                        $orden_data['manual_extension'] = $extension;
                        $this->base->guarda('accesorios', $orden_data);
                    }
                }
				
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('accesorios/index');
			}
			else
			{
				$datos['fotos']=$this->Accesorio->get_fotografias($accesorios_id);
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}	
		}
		else
		{
			$datos['r']=$this->base->read('accesorios',$accesorios_id);
		}

		$datos['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');
		$datos['tipo_moneda']=catalogo('tipo_moneda',FALSE);
		$datos['fotos']=$this->Accesorio->get_fotografias($accesorios_id);	
		$datos['acciones_fotos']=$this->Accesorio->get_acciones_fotos();
		$datos['accesorios_id']=$accesorios_id;
		$datos['titulo']='Editar accesorio';
		$this->load->view('accesorios/editar',$datos);
	}
	
	public function activar($accesorios_id,$activo)
    {
    	$this->base->verifica('accesorios_activar');
    	
    	$data['id']=$accesorios_id;
    	$data['activo']=!$activo;
    	$this->base->guarda('accesorios',$data);
    	$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('accesorios/index');
    }

	public function eliminar($accesorios_id)
    {
    	$this->base->verifica('accesorios_eliminar');
    	$data['id']=$accesorios_id;
    	$data['eliminado']=1;
    	$this->base->guarda('accesorios',$data);
    	bc_log(4,'Elimino accesorio id: '.$accesorios_id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('accesorios/index');
    }
	
	public function tipos_accesorios()
	{
		$this->base->verifica('tipos_accesorios');
		$b=bc_buscador();	
		$datos['r']=$this->Accesorio->find_tipos_accesorios($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Accesorio->count_tipos_accesorios($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	
		$datos['tipos_accesorios']=$this->base->lista('tipos_accesorios','id','nombre');
		$datos['titulo']='Tipos de accesorios';
		$datos['editar']=$this->base->tiene_permiso('tipos_accesorios_editar');
		$datos['activar']=$this->base->tiene_permiso('tipos_accesorios_activar');
		$this->load->view('tipos_accesorios/index',$datos);
	}
	
	public function tipos_accesorios_agregar()
	{
		$this->base->verifica('tipos_accesorios_agregar');
		if(!empty($_POST))
		{
			if($this->form_validation->run('tipos_accesorios'))
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'descuento_base'=>$this->input->post('descuento_base'),
					'descuento_opcional'=>$this->input->post('descuento_opcional'),
					'activo'=>$this->input->post('activo'),
				);
				$tipos_accesorios_id=$this->base->guarda('tipos_accesorios',$data);
		
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('tipos_accesorios',$tipos_accesorios_id);
					$orden_data = array('id' => $r->id);
				
					if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$orden=$r->imagen_orden?'_'.$r->imagen_orden:'';
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/tipos_accesorios",$tipos_accesorios_id.$orden,NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
				
					$this->base->guarda('tipos_accesorios', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/tipos_accesorios/',$tipos_accesorios_id,$this->config->item('avaluo_image_size'));
				}
				
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('accesorios/tipos_accesorios');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
	
		$datos['titulo']='Agregar tipos accesorios';
		$this->load->view('tipos_accesorios/agregar',$datos);
	}
	
	public function tipos_accesorios_editar($tipos_accesorios_id)
	{
		$this->base->verifica('tipos_accesorios_editar');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('tipos_accesorios'))
			{
				$data=array(
					'id'=>$tipos_accesorios_id,
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'descuento_base'=>$this->input->post('descuento_base'),
					'descuento_opcional'=>$this->input->post('descuento_opcional'),
					'activo'=>$this->input->post('activo'),
				);
				$tipos_accesorios_id=$this->base->guarda('tipos_accesorios',$data);
				
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('tipos_accesorios',$tipos_accesorios_id);
					$orden_data = array('id' => $r->id);
				
					if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$orden=$r->imagen_orden?'_'.$r->imagen_orden:'';
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/tipos_accesorios",$tipos_accesorios_id.$orden,NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
				
					$this->base->guarda('tipos_accesorios', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/tipos_accesorios/',$tipos_accesorios_id,$this->config->item('avaluo_image_size'));
				}
		
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('accesorios/tipos_accesorios');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$datos['tipos_accesorios_id']=$tipos_accesorios_id;
				//$datos['r']= (object) $_POST;

			}	
		}else
		{
			$datos['r']=$this->base->read('tipos_accesorios',$tipos_accesorios_id);
		}
		
		$datos['titulo']='Editar tipos accesorios';
		$datos['tipos_accesorios_id']=$tipos_accesorios_id;
	
		$this->load->view('tipos_accesorios/editar',$datos);
	}
	
	public function tipos_accesorios_activar($tipos_accesorios_id,$activo)
    {
    	$this->base->verifica('accesorios_activar');
    	
    	$data['id']=$tipos_accesorios_id;
    	$data['activo']=!$activo;
    	$this->base->guarda('tipos_accesorios',$data);
    	$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('accesorios/tipos_accesorios');
    }


}