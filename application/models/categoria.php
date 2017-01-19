<?php
require_once('base.php');
class Categoria extends Base {	
	
	var $table='productos_categorias';
	
	var $like=array(
		'nombre',
	);
	
	public function __construct()
	{
		parent::__construct();    
	}

	private function pconditions($cond)
	{
		if (!empty($cond['activo'])) {
			if ($cond['activo'] == 1)
				$this->db->where('activo', 1);
			if ($cond['activo'] == 2)
				$this->db->where('activo', 0);
		}
		if (!empty($cond['nombre']))
			$this->db->like('nombre', $cond['nombre']);

		if (!empty($cond['parent_id']))
			$this->db->where('parent_id', $cond['parent_id']);
	}

	public function find($conditions,$limit,$offset)
    {
		$this->pconditions($conditions);
		$this->db->select("
	    		pc.id,
	    		pc.nombre,
	    		pc.imagen_orden,
	   			pc.parent_id,
		    	pc.activo",FALSE);
		$this->db->where('eliminado',0);
		$this->db->from('productos_categorias as pc');
		$this->db->order_by('pc.id','DESC');

		if($limit)
			$this->db->limit($limit,$offset);

		$r=$this->db->get()->result();

		return $r;
    }
	
    public function count($conditions)
    {
		$this->pconditions($conditions);
		$this->db->where('eliminado',0);
		$this->db->from('productos_categorias as pc');
		$r=$this->db->count_all_results();

		return $r;
    }
    
    public function get_categorias($categorias_id=NULL, $activo=FALSE)
    {
    	if(!$categorias_id)
    		$this->db->where('parent_id IS NULL');
    	else
    		$this->db->where('parent_id',$categorias_id);

		if($activo)
			$this->db->where('activo',1);

    	$r=$this->db->get('productos_categorias')->result();
    	
    	//CONSULTO SI TIENE SUBCATEGORIAS
    	foreach($r as &$ro)
    	{
			if($activo)
				$this->db->where('activo',1);

    		$this->db->where('parent_id',$ro->id);
    		$this->db->select('id, nombre');
    		$ro->subcategorias=$this->db->get('productos_categorias')->result();
    	}

    	return $r;
    }
    
    public function fotos_eliminar($categorias_id)
    {
    	$data['id']=$categorias_id;
    	$data['foto_id']=0;
    	return parent::guarda('productos_categorias', $data);
    }
    
    public function get_subcategorias($categorias_id)
    {
    	$this->db->where('parent_id',$categorias_id);
    	$this->db->where('activo',1);
    	$this->db->select('id, nombre, descripcion, imagen_orden, video_orden');
    	$subcategorias=$this->db->get('productos_categorias')->result();
    	
    	return $subcategorias;
    }
}