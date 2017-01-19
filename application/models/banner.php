<?php
require_once('base.php');
class Banner extends Base {	
	
	var $table='productos_categorias';
	
	var $like=array(
		'id',
		'parent_id',
	);
	
	public function __construct()
	{
		parent::__construct();    
	}
	
	private function pconditions($cond)
    {
   		 if(!empty($cond['categorias_id']))
    		$this->db->like('categorias_id',$cond['categorias_id']);
    	if(!empty($cond['productos_id']))
    		$this->db->like('productos_id',$cond['productos_id']);
    }
    
    public function find($cond,$limit,$offset)
    {
    	$this->pconditions($cond);
		$this->db->where('eliminado',0);
		$this->db->from('banners');
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
    	$this->db->from('banners');
    	$r=$this->db->count_all_results();	
    	return $r;
    }
    
    
}