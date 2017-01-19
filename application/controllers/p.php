<?php
class P extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('Frontend');

		$sku = $this->uri->segment(2);
		$redirect = $this->config->item('shop_url').'frontends/index';
		if($sku)
		{
			$this->db->select('id');
			$this->db->where('item',$sku);
			$this->db->where('activo',1);
			$this->db->where('eliminado',0);
			$this->db->where('ocultar',0);
			$this->db->limit(1);
			$this->db->order_by('id','DESC');
			$id=$this->db->get('productos')->row('id');

			if($id)	// SI ENCONTRO UN PRODUCTO CON EL SKU LO REGRESA
			{
				$redirect = $this->config->item('shop_url') . 'frontends/productos/' . $id;
			}
			else	// SINO VERIFICA SI ES UN ACCESORIO O CONSUMIBLE
			{
				$this->db->select('id, consumible');
				$this->db->where('item',$sku);
				$this->db->where('activo',1);
				$this->db->where('eliminado',0);
				$this->db->limit(1);
				$this->db->order_by('id','DESC');
				$id=$this->db->get('accesorios')->row();

				if(!empty($id))
					$redirect = $this->config->item('shop_url') . 'frontends/cotizacion_agregar_producto' .(@$id->consumible?'/0/0/1':'');
			}

			if(empty($id))
				$this->session->set_flashdata('error', 'El n&uacute;mero de material no se encuentra registrado.');
		}
		else
			$this->session->set_flashdata('error', 'El n&uacute;mero de material no se encuentra registrado.');

		redirect($redirect);
	}
}