<?php
class Sepomex_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();    
	}
    
    function get_estados()
    {
    	$this->db->distinct();
    	$this->db->select('clave_estado,estado');
    	$this->db->order_by('estado','ASC');
    	$q=$this->db->get('sepomex')->result_array();
    	$result=array();
    	foreach($q as $r)
    	{
    		$result[$r['clave_estado']]=$r['estado'];
    	}
    	
    	return $result;
    }
    
    function get_municipios($estado,$json=FALSE)
    {
    	$this->db->distinct();
    	$this->db->select('municipio');
    	$this->db->where('estado',str_replace('_',' ',$estado));
    	$this->db->order_by('municipio','ASC');
    	$q=$this->db->get('sepomex')->result_array();
    	$result=array();
    	foreach($q as $r)
    	{
    		$result[]=$r['municipio'];
    	}
    	if($json)
    	{
    		$msg = json_encode($result);
    		$this->output->set_output($msg);
    	}	
    	else
    		return $result;
    }
    
	function get_colonias($estado,$municipio,$codigo_postal,$json=FALSE)
    {
    	$this->db->distinct();
    	$this->db->select('asentamiento');
    	if($estado!='')
    		$this->db->where('estado',str_replace('_',' ',$estado));
    	if($municipio!='')
    		$this->db->where('municipio',str_replace('_',' ',$municipio));
    	if($codigo_postal!='')
    		$this->db->where('codigo_postal',str_replace('_',' ',$codigo_postal));
    	$this->db->order_by('asentamiento','ASC');
    	$q=$this->db->get('sepomex')->result_array();
    	    	    	   
    	$result=array();
    	foreach($q as $r)
	    {
	    		$result[]=$r['asentamiento'];
	    }
    	if($json)
    	{
	    	$msg = implode(",\n",$result);
	    	$this->output->set_output(json_encode($result));
    	}
    	else
    	{
	    	return $result;
    	}
    		
    }
    
	function get_codigo_postal($estado,$municipio,$asentamiento,$json=FALSE)
    {
    	$this->db->distinct();
    	$this->db->select('codigo_postal');
    	$this->db->where('estado',str_replace('_',' ',$estado));
    	$this->db->where('municipio',str_replace('_',' ',$municipio));
    	$this->db->where('asentamiento',str_replace('_',' ',$asentamiento));
    	$q=$this->db->get('sepomex')->row();    	    	   	
    	if($q->codigo_postal)
    	{
	    	if($json)
	    	{
		    	$msg = '{"cp":"'.$q->codigo_postal.'"}';
		    	$this->output->set_output($msg);
	    	}
	    	else
	    		return $q->codigo_postal;
    	}
    	else
    		return FALSE;	
    }
    
    function get_direccion($cp,$json=FALSE)
    {
    	$this->db->distinct();
    	$this->db->select('estado, municipio, asentamiento');
    	$this->db->where('codigo_postal',$cp);
    	$this->db->order_by('asentamiento','ASC');
    	$q=$this->db->get('sepomex')->result_array();
	    if(count($q)!=1)
	        unset($q[0]['asentamiento']);
	    if($json)
	    {
	        $msg = json_encode($q[0]);
	        $this->output->set_output($msg);
	    }
	    else
	    {
	        return $q[0];
	    }
    }
 	
    function get_clave_estado($estado)
    {
    	$estado=str_replace('_',' ',$estado);
    	$this->db->select('clave_estado');
    	$this->db->where('estado',$estado);
    	$q=$this->db->get('sepomex',1)->row();
    	return $q->clave_estado;	
    }
    
	function get_estado($clave_estado)
    {
    	$this->db->select('estado');
    	$this->db->where('clave_estado',$clave_estado);
    	$q=$this->db->get('sepomex',1)->row();
    	return $q->estado;	
    }
    
    function get_clave_municipio($estado,$municipio)
    {
    	$municipio=str_replace('_',' ',$municipio);
    	$estado=str_replace('_',' ',$estado);
    	$this->db->select('clave_municipio',FALSE);
    	$this->db->where('estado',$estado);
    	$this->db->where('municipio',$municipio);
    	$q=$this->db->get('sepomex',1)->row();
    	if(!empty($q))
    		return $q->clave_municipio;
    	return FALSE;		
    }
    
	function get_municipio($clave_estado,$clave_municipio)
    {
    	$this->db->select('municipio');
    	$this->db->where('clave_municipio',$clave_municipio);
    	$this->db->where('clave_estado',$clave_estado);
    	$q=$this->db->get('sepomex',1)->row();
    	return $q->municipio;	
    }
}

/* End of file avaluos.php */
/* Location: ./system/application/models/avaluos.php */