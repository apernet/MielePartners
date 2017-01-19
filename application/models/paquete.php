<?php 
require_once('base.php');
class Paquete extends Base {
	var $table='paquetes';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	private function pconditions($cond)
	{
		if(!empty($cond['nombre']))
			$this->db->like('nombre',$cond['nombre']);
	}
	
	public function find($cond,$limit,$offset)
	{
		$this->pconditions($cond);
		$this->db->select("
	    		p.id,
	    		p.nombre,
	   			p.descuento,
		    	p.descuento_distribuidor,
		    	p.comision_vendedor,
				p.imagen_orden,
				p.fotos_id",FALSE);
		$this->db->where('eliminado',0);
		$this->db->from('paquetes as p');
		$this->db->order_by('id','DESC');
	
		if($limit)
			$this->db->limit($limit,$offset);
	
		$r=$this->db->get()->result();
	
		foreach($r as &$k)
		{
			if(file_exists(FCPATH.'files/paquetes/'.$k->id.'.jpg') && $k->fotos_id)
				$imagen=TRUE;
			else
				$imagen=FALSE;
			$k->imagen=$imagen;
		}
		
		return $r;
	}
	
	public function count($conditions)
	{
		$this->pconditions($conditions);
		$this->db->where('eliminado',0);
		$this->db->from('paquetes as p');
		$r=$this->db->count_all_results();
		 
		return $r;
	}
	
	/*public function get_paquete_producto($paquetes_id)
	{
		$CI = &get_instance();
		$CI->load->model('Producto');
		
		$this->db->select('pp.id,pp.productos_id,pp.cantidad,p.precio,p.nombre,p.modelo,p.item,p.descripcion');
		$this->db->where('pp.eliminado',0);
		$this->db->where('pp.paquetes_id',$paquetes_id);
		$this->db->join('productos as p','p.id=pp.productos_id','left outer');
		$productos = $this->db->get('paquetes_productos  as pp')->result_array();
		
		foreach($productos as &$k)
		{
			$k['imagen_producto'] =$CI->Producto->get_imagen_principal($k['productos_id']);
		}
		
		return $productos;
		
	}*/
	
	public function paquete_categorias_get($paquetes_id)
	{
		$this->db->select('p.id,p.categorias_id,p.cantidad,p.indice,pc.nombre,pc.descripcion,pc.descuento_opcional,pc.imagen_orden');
		$this->db->join('productos_categorias as pc','pc.id=p.categorias_id','left');
		$this->db->where('p.eliminado',0);
		$this->db->where('p.paquetes_id',$paquetes_id);
		$categorias = $this->db->get('paquetes_categorias as p')->result_array();
	
		foreach($categorias as &$k)
			$k['imagen_categoria']= FCPATH.'files/categorias/'.$k['categorias_id'].'.jpg';
	
		return $categorias;
	}
	
	public function categorias_indice_mayor($paquetes_id)
	{
		$this->db->select('MAX(indice) AS indice');
		$this->db->where('eliminado',0);
		$this->db->where('paquetes_id',$paquetes_id);
		$indice = $this->db->get('paquetes_categorias')->row('indice');
	
		return $indice;
	}
	
	public function categorias_elimina_anteriores($paquetes_id)
	{
		$data=array();
		$data['eliminado']=1;
		$this->db->where('paquetes_id',$paquetes_id);
		$this->db->update('paquetes_categorias',$data);
	}
	
	public function get_paquetes($limit = NULL)
	{
		$this->db->select('id,nombre,descripcion,fotos_id,descuento,imagen_orden');
		$this->db->limit($limit);
		$this->db->order_by('descuento','ASC');
		$this->db->order_by('nombre','ASC');
		$this->db->where('eliminado',0);
		$paquetes=$this->db->get('paquetes')->result();
		
		foreach($paquetes as &$p)
		{
			$categorias = $this->paquete_categorias_get($p->id);
			$p->categorias[$p->id] = $categorias;
		}
		return $paquetes;
	}
}
