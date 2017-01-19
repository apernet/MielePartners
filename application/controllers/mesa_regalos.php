<?php
require_once('main.php');
class Mesa_regalos extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('mesa_regalos');
		$this->load->model('Mesa_regalo');
	}
	
	public function index()
	{
		$this->base->verifica('mesa_regalos');
		$b=bc_buscador();
		$datos['r']=$this->Mesa_regalo->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Mesa_regalo->count($b['cond']);
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		$datos['titulo']='Mesa de Regalos';
		$this->load->view('mesa_regalos/index', $datos);
	}


	public function agregar()
	{
		$this->base->verifica('mesa_regalos');
		if(!empty($_POST))
		{
			if($this->form_validation->run('mesa_agregar'))
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'folio'=>$this->input->post('folio'),
					'email'=>$this->input->post('email'),
					'telefono'=>$this->input->post('telefono'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'contrasena'=>$this->input->post('contrasena'),
				);
				
				$mesa_id=$this->base->guarda($this->Mesa_regalo->table,$data);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('mesa_regalos/index');
			}
			else
			{
				$datos['titulo']='Agregar un usuario para el Boletin Newsletters';
				$this->load->view('mesa_regalos/agregar', $datos);
			}
		}
		else
		{
			$datos['titulo']='Agregar un usuario para el Boletin Newsletters';
			$this->load->view('mesa_regalos/agregar', $datos);
		}
	}

	public function editar($id)
	{
		$this->base->verifica('mesa_regalos');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('mesa_agregar'))
			{
				$data=array(
					'id'=>$id,
					'nombre'=>$this->input->post('nombre'),
					'folio'=>$this->input->post('folio'),
					'email'=>$this->input->post('email'),
					'telefono'=>$this->input->post('telefono'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'contrasena'=>$this->input->post('contrasena'),
				);
				$mesa_id=$this->base->guarda($this->Mesa_regalo->table,$data);
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('mesa_regalos/index');
			}else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}	
		}
		else
		{
			$datos['r']=$this->base->read($this->Mesa_regalo->table,$id);
			$datos['id']=$id;	
			$datos['titulo']='Editar una mesa de regalos.';
			$this->load->view('mesa_regalos/editar',$datos);
		}
	}

	public function activar($id,$activo)
    {
    	$this->base->verifica('mesa_regalos');
    	$data['id']=$id;
    	$data['activo']=!$activo;
    	$this->base->guarda($this->Mesa_regalo->table,$data);
    	$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('mesa_regalos/index');
    }

	public function eliminar($id)
    {
    	$this->base->verifica('mesa_regalos');
    	$data['id']=$id;
    	$data['eliminado']=1;
    	$this->base->guarda($this->Mesa_regalo->table,$data);
    	bc_log(1,'Elimino destacado id: '.$id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('mesa_regalos/index');
    }
    
    public function ver_detalles($id)
    {
    	$datos['productos']=$this->Mesa_regalo->get_productos_mesa($id);
    	$datos['titulo']='Ver detalles de la mesa de regalos';
    	$this->load->view('mesa_regalos/ver_detalles',$datos);
    }
}