<?php
require_once('main.php');
class Comisiones extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->base->verifica('comisiones');
		$this->load->model('Comision');
	}
	
	public function index()
	{
		$b=bc_buscador();

		if (!empty($b['cond']['exp']))
		{
			$_POST['fecha_inicial'] = @$b['cond']['fecha_inicial'];
			$_POST['fecha_final'] = @$b['cond']['fecha_final'];
			if (!$this->form_validation->run('comisiones_exportar'))
				$datos['flashdata']['error']='Por favor verifique los datos.';
			else
			{
				unset($b['cond']['exp']);
				$this->base->verifica('comisiones_exportar');
				$this->Comision->exportar($b['cond']);
			}
		}

		$datos['r']=$this->Comision->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);	
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Comision->count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();		
		$datos['titulo']='Comisiones';
		$datos['status']=$this->Comision->status;
		$datos['puede_editar']=$this->base->tiene_permiso('comisiones_editar');
		$datos['puede_pagar']=$this->base->tiene_permiso('comisiones_pagar');
		$datos['puede_exportar']=$this->base->tiene_permiso('comisiones_exportar');
		$datos['puede_cancelar']=$this->base->tiene_permiso('comisiones_cancelar');
		$datos['distribuidores']=$this->base->lista('cuentas','id','nombre',FALSE,'nombre','ASC');
		$datos['vendedores']=$this->base->lista('usuarios','id',array('nombre','apellido_paterno','apellido_materno'));
		$this->load->view('comisiones/index',$datos);
	}

	public function cambiar_status($comisiones_id,$status_id)
	{
		$datos['status_id']=$status_id;
    	$datos['id']=$comisiones_id;
    	$res=$this->base->guarda('comisiones',$datos);
		if($res)
		{
			$status=$this->Comision->status;
			$this->session->set_flashdata('done', 'La comisión ha cambiado su status correctamente a '.$status[$status_id].'.');
		}
		else
			$this->session->set_flashdata('error', 'La comisión no se ha podido cambiar de status.');
		
		redirect('comisiones/index/id/'.$comisiones_id);
	}
	
	/*public function agregar()
	{
		$this->base->verifica('categorias_agregar');
		if(!empty($_POST))
		{
			if ($this->form_validation->run('categorias'))
			{
				$data=array(
					'parent_id'=>$this->input->post('parent'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'activo'=>$this->input->post('activo'),
				);
				$categorias_id=$this->base->guarda('productos_categorias',mayuscula_array($data));
				if(!empty($_FILES))
					$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/categorias',$categorias_id,FALSE,FALSE);
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
			if($this->form_validation->run('categorias'))
			{
				$data=array(
					'id'=>$categorias_id,
					'parent_id'=>$this->input->post('parent'),
					'nombre'=>$this->input->post('nombre'),
					'descripcion'=>$this->input->post('descripcion'),
					'activo'=>$this->input->post('activo'),
				);
				
				if(!empty($_FILES))
					$foto_id=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/categorias',$categorias_id,FALSE,FALSE);
				$data['foto_id']=($foto_id)?1:0;
				$categorias_id=$this->base->guarda('productos_categorias',mayuscula_array($data));
					
				$this->session->set_flashdata('done', 'El registro fue guardado correctamente.');
				redirect('categorias/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		else
		{
			$datos['r']=$this->base->read('productos_categorias',$categorias_id);
		}
		
		if(file_exists(FCPATH.'files/categorias/'.$categorias_id.'.jpg') && $datos['r']->foto_id)
			$imagen=TRUE;
		else
			$imagen=FALSE;
			
		$datos['imagen']=$imagen;
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
    }*/
    
   
}