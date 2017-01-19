<?php
require_once('main.php');
class Alianzas_promociones extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('cupones');
		$this->load->model('Alianza_promocion','AP');
	}
	
	public function index()
	{
		$this->base->verifica('alianzas_promociones');
		$b=bc_buscador();
		$datos['r']=$this->AP->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->AP->count($b['cond']);
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		$datos['alianzas']=$this->AP->get_alianzas();
		$datos['titulo']='Alianzas para promociones';
		$this->load->view('alianzas_promociones/index',$datos);
	}

	public function agregar()
	{
		$this->base->verifica('alianzas_promociones_agregar');

		if(!empty($_POST))
		{

			$reglas=$this->form_validation->_config_rules['alianzas_promociones'];

			$reglas[]=array(
				'field' => 'prefijo',
				'label' => 'Prefijo',
				'rules' => 'callback_validacion_prefijo'
			);

			$this->form_validation->set_rules($reglas);
			$valido = $this->form_validation->run();

			if($valido)
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'prefijo'=>$this->input->post('prefijo'),
					'descripcion'=>$this->input->post('descripcion'),
					'numero_folios'=>$this->input->post('numero_folios'),
					'activo'=>1,
				);
			
				$alianzas_promociones_id=$this->base->guarda('alianzas_promociones',$data);
				$this->AP->crear_folios_alianza($alianzas_promociones_id, $_POST['prefijo'], $_POST['numero_folios']);
				
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('alianzas_promociones/index');
			}
		}
		
		$datos['titulo']='Agregar alianza para promociones';
		$this->load->view('alianzas_promociones/agregar',$datos);
	}
	
	public function activar($alianzas_promociones_id,$activo)
	{
		$this->base->verifica('alianzas_promociones_activar');
		 
		$data['id']=$alianzas_promociones_id;
		$data['activo']=!$activo;
		$this->base->guarda('alianzas_promociones',$data);
		$this->session->set_flashdata('done', 'Los cambios fueron realizados correctamente.');
		redirect('alianzas_promociones/index');
	}
	
	public function editar($alianzas_promociones_id)
	{
		$this->base->verifica('alianzas_promociones_editar');
		
		if(!empty($_POST))
		{
			if($this->form_validation->run('alianzas_promociones'))
			{
				
				$data=array(
						'id'=>$alianzas_promociones_id,
						'nombre'=>$this->input->post('nombre'),
						//'prefijo'=>$this->input->post('prefijo'),
						'descripcion'=>$this->input->post('descripcion'),
						//'numero_folios'=>$this->input->post('numero_folios'),
				);

				$this->base->guarda('alianzas_promociones', $data);
	
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('alianzas_promociones/index');
			}
			
			$datos['alianzas_promociones'] = new stdClass();
			$datos['alianzas_promociones']->nombre=@$_POST['nombre'];
			$datos['alianzas_promociones']->numero_folios=@$_POST['numero_folios'];
			
		}else
			$datos['alianzas_promociones']=$this->base->read('alianzas_promociones',$alianzas_promociones_id);
	
		$datos['titulo']='Editar alianza para promociones';
		$this->load->view('alianzas_promociones/editar',$datos);
	}
	
	public function exportar($alianzas_promociones_id)
	{
		$this->base->verifica('alianzas_promociones_exportar');
		$this->AP->exportar($alianzas_promociones_id);
	}

	/**
	 * Valida que el prefijo sólo acepte números y letras
	 * @return bool
	 */
	function validacion_prefijo()
	{
		if(empty($_POST['prefijo']))
		{
			$this->form_validation->set_message('validacion_prefijo', 'El campo Prefijo es obligatorio.');
			return false;
		}

		if(!preg_match("/^[0-9a-zA-ZñÑ]{1,50}$/",$_POST['prefijo']))
		{
			$this->form_validation->set_message('validacion_prefijo', 'El campo Prefijo no puede ser mayor a 50 caracteres y únicamente acepta números y letras.');
			return false;
		}

		return true;
	}
}