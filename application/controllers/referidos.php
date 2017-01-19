<?php
require_once('main.php');
class Referidos extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('referidos');
		$this->load->model('Referido');
	}
	
	public function index()
	{
		$b=bc_buscador();

		if (!empty($b['cond']['exp']))
		{
			$_POST['fecha_inicial'] = @$b['cond']['fecha_inicial'];
			$_POST['fecha_final'] = @$b['cond']['fecha_final'];
			if (!$this->form_validation->run('referidos_exportar'))
				$datos['flashdata']['error']='Por favor verifique los datos.';
			else
			{
				unset($b['cond']['exp']);
				$this->base->verifica('referidos_exportar');
				$this->Referido->exportar($b['cond']);
			}
		}

		$datos['r']=$this->Referido->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Referido->count($b['cond']);
		$paginador['uri_segment']=$b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();	

		$datos['distribuidores'] = $this->Referido->get_distribuidores();
		$this->load->Model('Sepomex_model','SP');
		$datos['estados']=$this->SP->get_estados();
		if(!empty($b['cond']['instalacion_estado']))
			$datos['municipios_instalacion']=$this->SP->get_municipios($b['cond']['instalacion_estado']);
		$datos['puede_agregar'] = $this->base->tiene_permiso('referidos_agregar');
		$datos['puede_editar'] = $this->base->tiene_permiso('referidos_editar');
		$datos['mostrar_propios'] = $this->base->tiene_permiso('referidos_mostrar_propios');
		$datos['mostrar_por_distribuidor'] = $this->base->tiene_permiso('referidos_mostrar_por_distribuidor');
		$datos['mostrar_todos'] = $this->base->tiene_permiso('referidos_mostrar_todos');
		$datos['puede_exportar'] = $this->base->tiene_permiso('referidos_exportar');
		$datos['si_no'] = catalogo('si_no');
		$datos['titulo']='Referidos';
		$this->load->view('referidos/index',$datos);
	}

	public function referidos_buscar($buscar_por)
	{
		$b=bc_buscador();
		
		if(isset($b['cond']['buscar_por']))
		{
			$buscar_por=$b['cond']['buscar_por'];
			unset($b['cond']['buscar_por']);
		}
		$datos['r']=$this->Referido->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Referido->count($b['cond']);
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$b['cond']['buscar_por']=$buscar_por;
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		$datos['distribuidores'] = $this->Referido->get_distribuidores();
		$this->load->Model('Sepomex_model','SP');
		$datos['estados']=$this->SP->get_estados();
		if(!empty($b['cond']['instalacion_estado']))
			$datos['municipios_instalacion']=$this->SP->get_municipios($b['cond']['instalacion_estado']);
		$datos['titulo']='Buscar Referidos';
		$this->load->view('referidos/buscar',$datos);
		
	}
	
	public function referidos_agregar()
	{
		$referidos_id=$_POST['id'];
		$datos['vendedor_nombre']=$this->base->value_get('referidos',$referidos_id,'vendedor_nombre');
		$datos['vendedor_paterno']=$this->base->value_get('referidos',$referidos_id,'vendedor_paterno');
		$datos['vendedor_materno']=$this->base->value_get('referidos',$referidos_id,'vendedor_materno');
		$datos['distribuidores_id']=$this->base->value_get('referidos',$referidos_id,'distribuidores_id');
		
		$msg = json_encode($datos);
		$this->output->set_output($msg);
	}
	
	public function agregar()
	{
		$this->base->verifica('referidos_agregar');
		$this->load->Model('Sepomex_model','SP');
		if(!empty($_POST))
		{
			if($this->form_validation->run('referidos'))
			{
				$data=array(
					'nombre'=>$this->input->post('nombre'),
					'apellido_paterno'=>$this->input->post('apellido_paterno'),
					'apellido_materno'=>$this->input->post('apellido_materno'),
					'email'=>$this->input->post('email'),
					'distribuidores_id'=>$this->input->post('distribuidores_id'),
					'vendedores_id'=>$this->session->userdata('id'),
					'vendedor_nombre'=>$this->input->post('vendedor_nombre'),
					'vendedor_paterno'=>$this->input->post('vendedor_paterno'),
					'vendedor_materno'=>$this->input->post('vendedor_materno'),
					'vendedor_email'=>$this->input->post('vendedor_email'),
					'instalacion_codigo_postal'=>$this->input->post('instalacion_codigo_postal'),
					'instalacion_estado'=>$this->input->post('instalacion_estado'),
					'instalacion_municipio'=>$this->input->post('instalacion_municipio'),
					'instalacion_asentamiento'=>$this->input->post('instalacion_asentamiento'),
					'instalacion_calle'=>$this->input->post('instalacion_calle'),
					'instalacion_numero_exterior'=>$this->input->post('instalacion_numero_exterior'),
					'instalacion_numero_interior'=>$this->input->post('instalacion_numero_interior'),
				);
				
				$referidos_id=$this->base->guarda($this->Referido->table,$data);
				//Capturamos la vigencia del  referido
				$fecha_vigencia = $this->Referido->get_vigencia($referidos_id,'created');
				$vigencia['vigencia'] = $fecha_vigencia;
				$vigencia['id']= $referidos_id;
				$this->base->guarda($this->Referido->table,$vigencia);
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('referidos/index');
			}
			else
			{
				$data['r']=$_POST;
				$datos['municipios_instalacion']=$this->SP->get_municipios($_POST['instalacion_estado']);
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		
		$datos['estados']=$this->SP->get_estados();
		$datos['titulo']='Agregar referido';
		$datos['distribuidores'] = $this->Referido->get_distribuidores();
		//$datos['vendedores'] = $this->Referido->get_vendedores();
		
		$datos['mostrar_distribuidores'] = $this->base->tiene_permiso('referidos_mostrar_distribuidores');
		$datos['mostrar_vendedores'] = $this->base->tiene_permiso('referidos_mostrar_vendedores');
		$this->load->view('referidos/agregar',$datos);
	}
	
	public function editar($referidos_id)
	{
		$this->base->verifica('referidos_agregar');
		$this->load->Model('Sepomex_model','SP');
		if(!empty($_POST))
		{
			if($this->form_validation->run('referidos'))
			{
				$data=array(
						'id'=>$referidos_id,
						'nombre'=>$this->input->post('nombre'),
						'apellido_paterno'=>$this->input->post('apellido_paterno'),
						'apellido_materno'=>$this->input->post('apellido_materno'),
						'email'=>$this->input->post('email'),
						'distribuidores_id'=>$this->input->post('distribuidores_id'),
						'vendedores_id'=>$this->session->userdata('id'),
						'vendedor_nombre'=>$this->input->post('vendedor_nombre'),
						'vendedor_paterno'=>$this->input->post('vendedor_paterno'),
						'vendedor_materno'=>$this->input->post('vendedor_materno'),
						'vendedor_email'=>$this->input->post('vendedor_email'),
						'instalacion_codigo_postal'=>$this->input->post('instalacion_codigo_postal'),
						'instalacion_estado'=>$this->input->post('instalacion_estado'),
						'instalacion_municipio'=>$this->input->post('instalacion_municipio'),
						'instalacion_asentamiento'=>$this->input->post('instalacion_asentamiento'),
						'instalacion_calle'=>$this->input->post('instalacion_calle'),
						'instalacion_numero_exterior'=>$this->input->post('instalacion_numero_exterior'),
						'instalacion_numero_interior'=>$this->input->post('instalacion_numero_interior'),
				);
				$referidos_id=$this->base->guarda($this->Referido->table,$data);
				//Capturamos la vigencia del referido
				$fecha_vigencia = $this->Referido->get_vigencia($referidos_id,'created');
				$vigencia['vigencia'] = $fecha_vigencia;
				$vigencia['id']= $referidos_id;
				$this->base->guarda($this->Referido->table,$vigencia);
				
				$this->session->set_flashdata('done', 'El registro fue creado correctamente.');
				redirect('referidos/index');
			}
			else
			{
				$data['r']=$_POST;
				$datos['municipios_instalacion']=$this->SP->get_municipios($_POST['instalacion_estado']);
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		else
		{
			$datos['r']=$this->base->read($this->Referido->table,$referidos_id);
			$datos['municipios_instalacion']=$this->SP->get_municipios($datos['r']->instalacion_estado);
		}
		
		$datos['estados']=$this->SP->get_estados();
		$datos['distribuidores'] = $this->Referido->get_distribuidores();
		//$datos['vendedores'] = $this->Referido->get_vendedores();
		$datos['mostrar_distribuidores'] = $this->base->tiene_permiso('referidos_mostrar_distribuidores');
		$datos['mostrar_vendedores'] = $this->base->tiene_permiso('referidos_mostrar_vendedores');
		$datos['titulo']='Editar referido';
		$this->load->view('referidos/editar',$datos);
	}
	
	public function get_vendedores()
	{
		$this->Referido->get_vendedor($_POST['distribuidores_id'],TRUE);
	}
	
	public function buscar()
	{
	    $email = $this->input->post('email');
	    $referido = $this->Referido->por_email($email);
	    if(!empty($referido))
	    {
	        $referido->distribuidor = $this->base->value_get('cuentas', $referido->distribuidores_id,'nombre');
	        $referido->vendedor = $referido->vendedor;
		}
	    $msg = json_encode($referido);
        $this->output->set_output($msg);
	}
}