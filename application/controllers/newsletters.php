<?php
require_once('main.php');
class Newsletters extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('newsletters');
		$this->load->model('Newsletter');
	}
	
	public function index()
	{
		$this->base->verifica('newsletters');
		$b=bc_buscador();
		$datos['r']=$this->Newsletter->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Newsletter->count($b['cond']);
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		$datos['titulo']='Boletin Newsletters';
		$this->load->view('newsletters/index', $datos);
	}

	
	public function agregar()
	{
		$this->base->verifica('newsletters');
		if(!empty($_POST))
		{
			if($this->form_validation->run('newsletter_agregar'))
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'email'=>$this->input->post('email'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'telefono'=>$this->input->post('telefono'),
				);
				
				$designer_id=$this->base->guarda($this->Newsletter->table,$data);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('newsletters/index');
			}
			else
			{
				$datos['titulo']='Agregar un usuario para el Boletin Newsletters';
				$this->load->view('newsletters/agregar', $datos);
			}
		}
		else
		{
			$datos['titulo']='Agregar un usuario para el Boletin Newsletters';
			$this->load->view('newsletters/agregar', $datos);
		}
	}
	
	public function editar($id)
	{
		$this->base->verifica('newsletters');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('newsletter_agregar'))
			{
				$data=array(
					'id'=>$id,
					'nombre'=>$this->input->post('nombre'),
					'email'=>$this->input->post('email'),
					'codigo_postal'=>$this->input->post('codigo_postal'),
					'telefono'=>$this->input->post('telefono'),
					'activo'=>$this->input->post('activo'),
				);
				$designer_id=$this->base->guarda($this->Newsletter->table,$data);
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('newsletters/index');
			}else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}	
		}
		else
		{
			$datos['r']=$this->base->read($this->Newsletter->table,$id);
			$datos['id']=$id;	
			$datos['titulo']='Editar un usuario del Boletin Newsletters';
			$this->load->view('newsletters/editar',$datos);
		}
	}
	
	public function activar($id,$activo)
    {
    	$this->base->verifica('newsletters');
    	$data['id']=$id;
    	$data['activo']=!$activo;
    	$this->base->guarda($this->Newsletter->table,$data);
    	$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('newsletters/index');
    }

	public function eliminar($id)
    {
    	$this->base->verifica('newsletters');
    	$data['id']=$id;
    	$data['eliminado']=1;
    	$this->base->guarda($this->Newsletter->table,$data);
    	bc_log(1,'Elimino destacado id: '.$id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('newsletters/index');
    }
    
    public function exportar_excel()
    {
    	$datos['registros']=$this->Newsletter->get_usuarios_registrados();
    	$tabla=$this->load->view('newsletters/tabla_datos',$datos);
   	 	header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=boletin.xls");
    }
}