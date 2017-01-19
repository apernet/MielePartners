<?php
require_once('main.php');
class Banners extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Banner');
	}
	
	public function index()
	{
		$this->base->verifica('banners');
		$b=bc_buscador();
		$this->db->order_by('id','DESC');	
		$datos['r']=$this->Banner->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Banner->count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();		
		$datos['titulo']='Banners';
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['productos']=$this->base->lista('productos','id','nombre',TRUE,NULL,'DESC',array('ocultar'=>0));
		$this->load->view('banners/index',$datos);
	}

	public function agregar()
	{
		$this->base->verifica('banners_agregar');
		
		if(!empty($_POST))
		{
			if ($this->form_validation->run('banners') && !empty($_FILES['imagen']['tmp_name']))
			{
				$data=array(
					'banner_principal'=>$this->input->post('banner_principal'),
					'productos_id'=>$this->input->post('productos_id'),
					'categorias_id'=>$this->input->post('categorias_id'),
					'url'=>$this->input->post('url'),
					'activo'=>1,
				);
				$banners_id=$this->base->guarda('banners',$data);
				
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('banners',$banners_id);
					$orden_data = array('id' => $r->id);
				
					if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$orden=$r->imagen_orden?'_'.$r->imagen_orden:'';
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/banners",$banners_id."_".$r->imagen_orden,NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
				
					$this->base->guarda('banners', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
						$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/banners/',$banners_id,FALSE,FALSE);
				}
				
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('banners/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				if($_FILES['imagen']['error'])
					$datos['flashdata']['error'].='El campo de imagen es obligatorio.';
			}
		}
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['productos']=$this->base->lista('productos','id','nombre',TRUE,NULL,'DESC',array('ocultar'=>0));
		$datos['titulo']='Agregar banner';
		$this->load->view('banners/agregar',$datos);
	}

	public function editar($banners_id)
	{
		$this->base->verifica('banners_editar');
		
		if(!empty($_POST))
		{
			if ($this->form_validation->run('banners'))
			{
				$data=array(
					'id'=>$banners_id,
					'banner_principal'=>$this->input->post('banner_principal'),
					'productos_id'=>$this->input->post('productos_id'),
					'categorias_id'=>$this->input->post('categorias_id'),
					'url'=>$this->input->post('url'),
				);
				$this->base->guarda('banners',$data);
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('banners',$banners_id);
					$orden_data = array('id' => $r->id);
				
				if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$orden=$r->imagen_orden?'_'.$r->imagen_orden:'';
						$guarda=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/banners",$banners_id."_".$r->imagen_orden,NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
				
					$this->base->guarda('banners', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
						$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/banners/',$banners_id,FALSE,FALSE);
				}
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('banners/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				if($_FILES['imagen']['error'])
					$datos['flashdata']['error'].='El campo de imagen es obligatorio.';
				$datos['r']=(object)$_POST;

			}
		}
		else
		{
			$datos['r']=$this->base->read('banners',$banners_id);
		}
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['productos']=$this->base->lista('productos','id','nombre',TRUE,NULL,'DESC',array('ocultar'=>0));
		$datos['titulo']='Editar banner';
		$this->load->view('banners/editar',$datos);
	}

	public function eliminar($banners_id)
    {
    	$this->base->verifica('banners_eliminar');
    	
    	$data['id']=$banners_id;
    	$data['eliminado']=1;
    	$this->base->guarda('banners',$data);
    	bc_log(1,'Elimino banner id: '.$banners_id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('banners/index');
    }
    
	public function activar($banners_id,$activo)
    {
    	$this->base->verifica('banners_activar');
    	$data['id']=$banners_id;
    	$data['activo']=!$activo;
    	$this->base->guarda('banners',$data);
    	bc_log(1,'Desactivo banner id: '.$banners_id);
    	$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('banners/index');
    }
}