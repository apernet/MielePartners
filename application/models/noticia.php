<?php
require_once('base.php');
class Noticia extends Base {
	var $table='noticias';
	
	var $like=array(
		'titulo',
		'contenido',
	);
		
	public function __construct()
	{
		parent::__construct();    
	}
    
    function get_noticias()
    {
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$n=$this->db->get('noticias')->result_object();
    	return $n;
    }
    
	function get_noticias_inicio()
    {
    	$this->db->where('eliminado',0);
    	$this->db->where('activo',1);
    	$this->db->where('inicio',1);
    	$n=$this->db->get('noticias')->result_object();
    	return $n;
    }
}