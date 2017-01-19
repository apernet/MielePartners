<?php 
require_once('main.php');
class Noticias extends Main {
		
	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('noticias');
		$this->load->model('noticia');    
	}
	
	function index()
	{
		$b=bc_buscador();
		$this->db->order_by('id','DESC');
		$datos['r']=$this->noticia->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->noticia->count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	
		$datos['titulo']='Noticias';
		$this->load->view('noticias/index',$datos);
	}
	
	function activar($id,$activo)
    {
    	$data['id']=$id;
    	$data['activo']=!$activo;
    	$this->base->guarda($this->Noticia->table,$data);
    	$this->session->set_flashdata('done', 'Cambios realizados correctamente.');
		redirect('noticias/index');
    }
	
	function agregar()
	{
		if(!empty($_POST))
		{
			if ($this->form_validation->run('noticias'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'titulo'=>$this->input->post('titulo'),
					'activo'=>$this->input->post('activo'),
					'inicio'=>$this->input->post('inicio'),
					'fecha'=>$this->input->post('fecha'),
					'contenido'=>$this->input->post('contenido'),
				);
				$this->base->guarda($this->noticia->table,$data);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('noticias/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		$datos['titulo']='Agregar noticia';
		$this->load->view('noticias/agregar',$datos);
	}
	
	function editar($id)
	{
		if(!empty($_POST))
		{
			if ($this->form_validation->run('noticias'))
			{
				$data=array(
					'id'=>$this->input->post('id'),
					'titulo'=>$this->input->post('titulo'),
					'activo'=>$this->input->post('activo'),
					'inicio'=>$this->input->post('inicio'),
					'fecha'=>$this->input->post('fecha'),
					'contenido'=>$this->input->post('contenido')
				);
				$this->base->guarda($this->noticia->table,$data);
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('noticias/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}	
		}
		else
		{
			$datos['r']=$this->base->read($this->noticia->table,$id);
			
		}		
		$datos['titulo']='Editar noticia';
		$this->load->view('noticias/editar',$datos);
	}
	
	function eliminar($id)
    {
    	$data['id']=$id;
    	$data['eliminado']=1;
    	$this->base->guarda($this->noticia->table,$data);
    	bc_log(1,'Elimino noticia id: '.$id);
    	$this->session->set_flashdata('done', 'El registro se ha eliminado correctamente.');
		redirect('noticias/index');
   	}
    
}