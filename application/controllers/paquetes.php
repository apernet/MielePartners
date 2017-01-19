<?php
require_once('main.php');
class Paquetes extends Main {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Paquete');
	}

	function index()
	{
		$this->base->verifica('paquetes');
		$b=bc_buscador();
		$datos['r']=$this->Paquete->find($b['cond'],$this->config->item('por_pagina'),$b['offset']);
		$paginador = $this->config->item('paginador_config');
		$paginador['base_url'] = site_url($b['base_url']);
		$paginador['total_rows'] = $datos['total'] = $this->Paquete->count($b['cond']);
		$paginador['uri_segment'] = $b['uri_segment'];
		$this->load->library('pagination');
		$this->pagination->initialize($paginador);
		$datos['cond']=$b['cond'];
		$datos['paginador'] = $this->pagination->create_links();
		
		$datos['productos']=$this->base->lista('productos','id','nombre',TRUE,NULL,'ASC',array('ocultar'=>0));
		$datos['puede_editar']=$this->base->tiene_permiso('paquetes_editar');
		$datos['puede_eliminar']=$this->base->tiene_permiso('paquetes_eliminar');
		$datos['puede_agregar']=$this->base->tiene_permiso('paquetes_agregar');
		
		$datos['titulo']='Paquetes';
		$this->load->view('paquetes/index',$datos);
	}
	
	function agregar()
	{
		if(!empty($_POST))
		{
			if($this->form_validation->run('paquetes'))
			{
				$data=array(
						'nombre'=>$this->input->post('nombre'),
						'descuento'=>$this->input->post('descuento'),
						'descuento_distribuidor'=>$this->input->post('descuento_distribuidor'),
						'comision_vendedor'=>$this->input->post('comision_vendedor'),
						'descuento_exhibicion'=>$this->input->post('descuento_exhibicion'),
						'descripcion'=>$this->input->post('descripcion'),
				);
				$paquetes_id = $this->base->guarda('paquetes',$data);
				//GUARDO FOTOGRFAÍA
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('paquetes',$paquetes_id);
					$orden_data = array('id' => $r->id);
					if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$orden=$r->imagen_orden?'_'.$r->imagen_orden:'';
						$foto_id=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/paquetes/{$paquetes_id}","paquete{$r->imagen_orden}",NULL,APPPATH);
						//$foto_id=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/paquetes",$paquetes_id.$orden,NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
				
					$this->base->guarda('paquetes', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
						$foto_id = $this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/paquetes',$paquetes_id,FALSE,FALSE);
				}
				
				$this->db->where('id',$paquetes_id);
				$foto['fotos_id']=($foto_id)?1:0;
				$this->db->update('paquetes',$foto);
				
				//GUARDO CATEGORÍAS QUE CONFORMAN EL PAQUETE
				foreach($_POST['categorias']['categorias_id'] as $k=>$v)
				{
					$categorias=array();
					if(!empty($v) && !empty($_POST['categorias']['cantidad'][$k]))
					{
						$categorias['categorias_id']=$v;
						$categorias['paquetes_id']=$paquetes_id;
						$categorias['cantidad']=$_POST['categorias']['cantidad'][$k];
						$categorias['indice']=$_POST['categorias']['indice'][$k];
					}
					if(!empty($categorias['categorias_id']) && !empty($categorias['cantidad']))
					   $id=$this->base->guarda('paquetes_categorias',$categorias);
				}
				$this->session->set_flashdata('done','El paquete fue creado correctamente.');
				redirect('paquetes/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
			}
		}
		
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['puede_eliminar_categoria']=$this->base->tiene_permiso('paquetes_productos_eliminar');
		$datos['titulo']= 'Nuevo paquete';
		$this->load->view('paquetes/agregar',$datos);
	}
	
	function editar($paquetes_id)
	{
		if(!empty($_POST))
		{
			if($this->form_validation->run('paquetes'))
			{
				$data=array(
						'id'=>$paquetes_id,
						'nombre'=>$this->input->post('nombre'),
						'descuento'=>$this->input->post('descuento'),
						'descuento_distribuidor'=>$this->input->post('descuento_distribuidor'),
						'comision_vendedor'=>$this->input->post('comision_vendedor'),
						'descuento_exhibicion'=>$this->input->post('descuento_exhibicion'),
						'descripcion'=>$this->input->post('descripcion'),
				);

				$foto_id=FALSE;
				if($this->config->item('cloudfiles'))
				{
					$r = $this->base->read('paquetes',$paquetes_id);
					$orden_data = array('id' => $r->id);
				
					if($_FILES['imagen']['size']>0)
					{
						$r->imagen_orden++;
						$foto_id=$this->base->guarda_imagen($_FILES['imagen']['tmp_name'],"files/paquetes/{$paquetes_id}","paquete{$r->imagen_orden}",NULL,APPPATH);
						$orden_data['imagen_orden'] = $r->imagen_orden;
					}
				
					$this->base->guarda('paquetes', $orden_data);
				}
				else
				{
					if(!empty($_FILES))
						$foto_id = $this->base->guarda_imagen($_FILES['imagen']['tmp_name'],'files/paquetes',$paquetes_id,FALSE,FALSE);
				}
				
				$data['fotos_id']=($foto_id)?1:0;
				$this->base->guarda('paquetes',$data);

				//ELIMINO CATEGORIAS ANTERIORES
				$this->Paquete->categorias_elimina_anteriores($paquetes_id);
				
				//GUARDO CATEGORÍAS QUE CONFORMAN EL PAQUETE

				foreach($_POST['categorias']['categorias_id'] as $k=>$v)
				{
					$categorias=array();
					if(!empty($v) && !empty($_POST['categorias']['cantidad'][$k]))
					{
						$categorias['categorias_id']=$v;
						$categorias['paquetes_id']=$paquetes_id;
						$categorias['cantidad']=$_POST['categorias']['cantidad'][$k];
						$categorias['indice']=$_POST['categorias']['indice'][$k];
					}
					if(!empty($categorias['categorias_id']) && !empty($categorias['cantidad']))
					   $id=$this->base->guarda('paquetes_categorias',$categorias);
				}

				$this->session->set_flashdata('done','Los cambios del paquete fueron guardados correctamente.');
				redirect('paquetes/index');
			}
			else
			{
				$datos['flashdata']['error']='Por favor verifique los datos.';
				$i=0;
				$datos['p']=array();
				foreach($_POST['categorias'] as $elemento=>$el)
				{
					foreach($el as $k=>$v)
					{
						if(!empty($v))
						{
							$datos['p'][$i][$elemento]=$v;
							$i++;
						}
					}
					$i=0;
				}
			}
		}
		else
			$datos['p']=$this->Paquete->paquete_categorias_get($paquetes_id);
		
		$datos['indices']=array();
		foreach($datos['p'] as $cat)
		{
			if(!array_key_exists($cat['indice'],$datos['indices']))
				$datos['indices'][$cat['indice']]=1;
			else
				$datos['indices'][$cat['indice']]++;
		}
		
		$datos['paquetes_id'] = $paquetes_id;
		$datos['indice_mayor']=$this->Paquete->categorias_indice_mayor($paquetes_id);
		$datos['r']=$this->base->read('paquetes',$paquetes_id);
		if(file_exists(FCPATH.'files/paquetes/'.$paquetes_id.'.jpg') && $datos['r']->fotos_id)
			$imagen=TRUE;
		else
			$imagen=FALSE;
		$datos['imagen']=$imagen;
		$datos['categorias']=$this->base->lista('productos_categorias','id','nombre');
		$datos['puede_eliminar_categoria']=$this->base->tiene_permiso('paquetes_productos_eliminar');
		$datos['titulo']= 'Editar paquete';
		$this->load->view('paquetes/editar',$datos);
	}
	
	function paquetes_eliminar($id)
	{
		$data['eliminado']=1;
		$this->db->where('id',$id);
		$this->db->update('paquetes',$data);
		$this->load->library('user_agent');
		$this->session->set_flashdata('done', 'El paquete fue eliminado correctamente.');
		redirect($this->agent->referrer());
	}
	function eliminar_producto($id)
	{
		$data['eliminado']=1;
		$this->db->where('id',$id);
		$this->db->update('paquetes_productos',$data);
		$this->load->library('user_agent');
		$this->session->set_flashdata('done', 'El producto fue eliminado correctamente.');
		redirect($this->agent->referrer());
	}
	
	public function imagen_eliminar($paquetes_id)
	{
		$this->db->where('id',$paquetes_id);
		$data['fotos_id']=0;
		$this->db->update('paquetes',$data);
		$this->load->library('user_agent');
		$this->session->set_flashdata('done', 'La imagen fue eliminado correctamente.');
		redirect($this->agent->referrer());
	}
	
}