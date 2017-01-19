<?php
require_once('base.php');
class Mesa_regalo extends Base {	
	
	var $table='mesas';
	
	public function __construct()
	{
		parent::__construct();    
	}
	
	private function pconditions($cond)
    {
   		 if(!empty($cond['nombre']))
    		$this->db->like('nombre',$cond['nombre']);
    	
    	if(!empty($cond['folio']))
    		$this->db->like('folio',$cond['folio']);
    		
    	if(!empty($cond['email']))
    		$this->db->like('email',$cond['email']);
    		
    	if(!empty($cond['contrasena']))
    		$this->db->like('contrasena',$cond['contrasena']);
    }
    
    public function find($cond,$limit,$offset)
    {
    	$this->pconditions($cond);
		$this->db->select('*');
		$this->db->where('eliminado',0);
		$this->db->from('mesas');
    	$this->db->order_by('id','DESC');
				
		if($limit)
			$this->db->limit($limit,$offset);

		$r=$this->db->get()->result_object();
		
    	return $r;
    }
	
    public function count($conditions)
    {
    	$this->pconditions($conditions);
    	$this->db->where('eliminado',0);
    	$this->db->from('mesas');
    	$r=$this->db->count_all_results();	
    	return $r;
    }
    
    public function get_marcas_productos()
    {
    	
    }
    
    public function get_productos_mesa($id)
    {
    	$string=array(
    		'p.id AS productos_id','p.item',
    		"(SELECT marcas.nombre FROM marcas WHERE marcas.id=p.marcas_id AND marcas.activo=1 AND marcas.eliminado=0) as marca_nombre",
	    	"(SELECT productos_categorias.nombre FROM productos_categorias WHERE productos_categorias.id=p.categorias_id AND productos_categorias.activo=1 AND productos_categorias.eliminado=0) as categoria_producto",
    		"(SELECT designers.nombre FROM designers WHERE designers.id=p.designer_id AND designers.activo=1 AND designers.eliminado=0) as designer_nombre",
    		'p.nombre','p.costo','p.precio',
    		'fp.id AS foto_id','fp.tipos_id',
    	);
    	$this->db->select($string);
    	$this->db->where('m.id',$id);
    	
    	$this->db->where('p.activo',1);
    	$this->db->where('p.eliminado',0);
		
    	$this->db->where('fp.activo',1);
    	$this->db->where('fp.eliminado',0);
    	
    	$this->db->where('fp.activo',1);
    	
    	$this->db->join('mesas_productos AS mp','mp.mesas_id=m.id');
    	$this->db->join('productos AS p','p.id=mp.productos_id');
    	$this->db->join('fotografias_productos AS fp','fp.productos_id=p.id');
    	$query=$this->db->get('mesas AS m')->result();
    	
    	return $query;
    }
    
}